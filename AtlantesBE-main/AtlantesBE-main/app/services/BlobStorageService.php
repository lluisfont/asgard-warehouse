<?php

/**
 * Azure Blob Storage service for ATE-GAS images.
 * Uses REST API directly (no SDK dependency). Compatible PHP 5.4+.
 *
 * Required constants (define in .env.php):
 *   azure_blob_enabled           - bool, master switch
 *   azure_blob_auth_mode         - 'connection_string' | 'sas'
 *   azure_blob_connection_string - full connection string (auth_mode=connection_string)
 *   azure_blob_account_name      - account name (auth_mode=sas)
 *   azure_blob_sas_token         - SAS token without leading '?' (auth_mode=sas)
 *   azure_blob_container         - container name
 *
 * @param callable|null $transport Optional HTTP transport for testing.
 *   Signature: function($method, $url, array $flatHeaders, $body) : array
 *   Returns:   array('httpCode'=>int, 'body'=>string, 'headerSize'=>int, 'error'=>string)
 */
class BlobStorageService
{
    private $accountName = '';
    private $accountKey  = '';
    private $container   = '';
    private $baseUrl     = '';
    private $authMode    = 'connection_string';
    private $sasToken    = '';
    private $transport   = null;

    public function __construct($transport = null)
    {
        $this->transport = $transport;

        $this->authMode  = defined('azure_blob_auth_mode') ? azure_blob_auth_mode : 'connection_string';
        $this->container = defined('azure_blob_container') ? azure_blob_container : '';

        if ($this->authMode === 'connection_string') {
            $connStr           = defined('azure_blob_connection_string') ? azure_blob_connection_string : '';
            $parts             = $this->parseConnectionString($connStr);
            $this->accountName = isset($parts['AccountName'])              ? $parts['AccountName']              : '';
            $this->accountKey  = isset($parts['AccountKey'])               ? $parts['AccountKey']               : '';
            $protocol          = isset($parts['DefaultEndpointsProtocol']) ? $parts['DefaultEndpointsProtocol'] : 'https';
            $suffix            = isset($parts['EndpointSuffix'])           ? $parts['EndpointSuffix']           : 'core.windows.net';
            $this->baseUrl     = $protocol . '://' . $this->accountName . '.blob.' . $suffix;
        } elseif ($this->authMode === 'sas') {
            $this->accountName = defined('azure_blob_account_name') ? azure_blob_account_name : '';
            $this->sasToken    = defined('azure_blob_sas_token')    ? azure_blob_sas_token    : '';
            $this->baseUrl     = 'https://' . $this->accountName . '.blob.core.windows.net';
        }
    }

    public function isConfigured()
    {
        if ($this->authMode === 'connection_string') {
            return $this->accountName !== '' && $this->accountKey !== '' && $this->container !== '';
        }
        if ($this->authMode === 'sas') {
            return $this->accountName !== '' && $this->sasToken !== '' && $this->container !== '';
        }
        return false;
    }

    /**
     * Upload binary content as a BlockBlob. Returns true on 201 Created.
     */
    public function uploadBlob($blobName, $content, $contentType)
    {
        if (!$this->isConfigured()) {
            error_log('[AzureBlob] uploadBlob: service not configured');
            return false;
        }

        $url           = $this->blobUrl($blobName);
        $date          = gmdate('D, d M Y H:i:s') . ' GMT';
        $contentLength = (string) strlen($content);

        $msHeaders = array(
            'x-ms-date'      => $date,
            'x-ms-version'   => '2020-10-02',
            'x-ms-blob-type' => 'BlockBlob',
        );

        $allHeaders = array_merge($msHeaders, array(
            'Content-Type'   => $contentType,
            'Content-Length' => $contentLength,
        ));

        if ($this->authMode === 'connection_string') {
            $allHeaders['Authorization'] = $this->sharedKeyAuth(
                'PUT', $blobName, $contentType, $contentLength, $msHeaders
            );
        } elseif ($this->authMode === 'sas') {
            $url .= '?' . ltrim($this->sasToken, '?');
        }

        $result = $this->callHttp('PUT', $url, $this->flattenHeaders($allHeaders), $content);

        if ($result['error']) {
            error_log('[AzureBlob] uploadBlob curl error blob=' . $blobName . ' err=' . $result['error']);
            return false;
        }
        if ($result['httpCode'] !== 201) {
            error_log('[AzureBlob] uploadBlob HTTP=' . $result['httpCode'] . ' blob=' . $blobName);
            return false;
        }
        return true;
    }

    /**
     * Upload a local file as a BlockBlob.
     */
    public function uploadBlobFromFile($blobName, $localPath, $contentType)
    {
        if (!is_file($localPath)) {
            error_log('[AzureBlob] uploadBlobFromFile: file not found=' . $localPath);
            return false;
        }
        $content = file_get_contents($localPath);
        if ($content === false) {
            error_log('[AzureBlob] uploadBlobFromFile: cannot read file=' . $localPath);
            return false;
        }
        return $this->uploadBlob($blobName, $content, $contentType);
    }

    /**
     * Download a blob. Returns array('content'=>..., 'contentType'=>...) or null.
     */
    public function getBlob($blobName)
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $url       = $this->blobUrl($blobName);
        $date      = gmdate('D, d M Y H:i:s') . ' GMT';
        $msHeaders = array(
            'x-ms-date'    => $date,
            'x-ms-version' => '2020-10-02',
        );

        $allHeaders = $msHeaders;
        if ($this->authMode === 'connection_string') {
            $allHeaders['Authorization'] = $this->sharedKeyAuth('GET', $blobName, '', '', $msHeaders);
        } elseif ($this->authMode === 'sas') {
            $url .= '?' . ltrim($this->sasToken, '?');
        }

        $result = $this->callHttp('GET', $url, $this->flattenHeaders($allHeaders), null);

        if ($result['error']) {
            error_log('[AzureBlob] getBlob curl error blob=' . $blobName . ' err=' . $result['error']);
            return null;
        }
        if ($result['httpCode'] !== 200) {
            return null;
        }

        $rawHeaders  = substr($result['body'], 0, $result['headerSize']);
        $body        = substr($result['body'], $result['headerSize']);
        $contentType = 'application/octet-stream';
        foreach (explode("\r\n", $rawHeaders) as $line) {
            if (stripos($line, 'Content-Type:') === 0) {
                $contentType = trim(substr($line, 13));
                break;
            }
        }

        return array('content' => $body, 'contentType' => $contentType);
    }

    /**
     * Check if a blob exists (HEAD request).
     */
    public function exists($blobName)
    {
        if (!$this->isConfigured()) {
            return false;
        }

        $url       = $this->blobUrl($blobName);
        $date      = gmdate('D, d M Y H:i:s') . ' GMT';
        $msHeaders = array(
            'x-ms-date'    => $date,
            'x-ms-version' => '2020-10-02',
        );

        $allHeaders = $msHeaders;
        if ($this->authMode === 'connection_string') {
            $allHeaders['Authorization'] = $this->sharedKeyAuth('HEAD', $blobName, '', '', $msHeaders);
        } elseif ($this->authMode === 'sas') {
            $url .= '?' . ltrim($this->sasToken, '?');
        }

        $result = $this->callHttp('HEAD', $url, $this->flattenHeaders($allHeaders), null);
        return $result['httpCode'] === 200;
    }

    /**
     * Delete a blob.
     */
    public function deleteBlob($blobName)
    {
        if (!$this->isConfigured()) {
            return false;
        }

        $url       = $this->blobUrl($blobName);
        $date      = gmdate('D, d M Y H:i:s') . ' GMT';
        $msHeaders = array(
            'x-ms-date'    => $date,
            'x-ms-version' => '2020-10-02',
        );

        $allHeaders = $msHeaders;
        if ($this->authMode === 'connection_string') {
            $allHeaders['Authorization'] = $this->sharedKeyAuth('DELETE', $blobName, '', '', $msHeaders);
        } elseif ($this->authMode === 'sas') {
            $url .= '?' . ltrim($this->sasToken, '?');
        }

        $result = $this->callHttp('DELETE', $url, $this->flattenHeaders($allHeaders), null);
        return $result['httpCode'] === 202;
    }

    // ── Private / protected helpers ──────────────────────────────────────────

    /**
     * Central HTTP dispatch. Uses injected transport when available, cURL otherwise.
     * Returns array('httpCode'=>int, 'body'=>string, 'headerSize'=>int, 'error'=>string).
     */
    protected function callHttp($method, $url, array $flatHeaders, $body)
    {
        if ($this->transport !== null) {
            return call_user_func($this->transport, $method, $url, $flatHeaders, $body);
        }

        if (function_exists('curl_init')) {
            return $this->callHttpCurl($method, $url, $flatHeaders, $body);
        }

        return $this->callHttpStream($method, $url, $flatHeaders, $body);
    }

    private function callHttpCurl($method, $url, array $flatHeaders, $body)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER,         true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,     $flatHeaders);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        if ($method === 'HEAD') {
            curl_setopt($ch, CURLOPT_NOBODY, true);
        }

        $raw        = curl_exec($ch);
        $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $error      = curl_error($ch);
        curl_close($ch);

        return array(
            'httpCode'   => $httpCode,
            'body'       => $raw !== false ? $raw : '',
            'headerSize' => $headerSize,
            'error'      => $error,
        );
    }

    private function callHttpStream($method, $url, array $flatHeaders, $body)
    {
        $headerArr = array();
        foreach ($flatHeaders as $h) {
            $headerArr[] = $h;
        }

        $opts = array(
            'http' => array(
                'method'        => $method,
                'header'        => implode("\r\n", $headerArr),
                'content'       => $body !== null ? $body : '',
                'ignore_errors' => true,
            ),
            'ssl' => array(
                'verify_peer'      => true,
                'verify_peer_name' => true,
            ),
        );

        $context     = stream_context_create($opts);
        $responseRaw = @file_get_contents($url, false, $context);
        $error       = '';

        if ($responseRaw === false) {
            $error = 'stream request failed';
            return array('httpCode' => 0, 'body' => '', 'headerSize' => 0, 'error' => $error);
        }

        // Parse status code from $http_response_header (populated by file_get_contents)
        $httpCode    = 0;
        $headerLines = isset($http_response_header) ? $http_response_header : array();
        if (!empty($headerLines)) {
            if (preg_match('/HTTP\/\S+\s+(\d+)/', $headerLines[0], $m)) {
                $httpCode = (int) $m[1];
            }
        }

        // Build raw header block to mimic cURL output structure
        $headerBlock = implode("\r\n", $headerLines) . "\r\n\r\n";
        $headerSize  = strlen($headerBlock);

        return array(
            'httpCode'   => $httpCode,
            'body'       => $headerBlock . $responseRaw,
            'headerSize' => $headerSize,
            'error'      => '',
        );
    }

    private function blobUrl($blobName)
    {
        $segments = explode('/', $blobName);
        $encoded  = array_map('rawurlencode', $segments);
        return $this->baseUrl . '/' . $this->container . '/' . implode('/', $encoded);
    }

    private function sharedKeyAuth($verb, $blobName, $contentType, $contentLength, $msHeaders)
    {
        $canon = array();
        foreach ($msHeaders as $k => $v) {
            if (stripos($k, 'x-ms-') === 0) {
                $canon[strtolower($k)] = trim($v);
            }
        }
        ksort($canon);
        $canonHeaderStr = '';
        foreach ($canon as $k => $v) {
            $canonHeaderStr .= $k . ':' . $v . "\n";
        }

        $canonResource = '/' . $this->accountName . '/' . $this->container . '/' . $blobName;

        $stringToSign = implode("\n", array(
            $verb,
            '',
            '',
            $contentLength,
            '',
            $contentType,
            '',
            '',
            '',
            '',
            '',
            '',
        )) . "\n" . $canonHeaderStr . $canonResource;

        $signature = base64_encode(
            hash_hmac('sha256', $stringToSign, base64_decode($this->accountKey), true)
        );

        return 'SharedKey ' . $this->accountName . ':' . $signature;
    }

    private function flattenHeaders($headers)
    {
        $out = array();
        foreach ($headers as $k => $v) {
            $out[] = $k . ': ' . $v;
        }
        return $out;
    }

    private function parseConnectionString($connStr)
    {
        $parts = array();
        foreach (explode(';', $connStr) as $segment) {
            $pos = strpos($segment, '=');
            if ($pos !== false) {
                $parts[substr($segment, 0, $pos)] = substr($segment, $pos + 1);
            }
        }
        return $parts;
    }
}
