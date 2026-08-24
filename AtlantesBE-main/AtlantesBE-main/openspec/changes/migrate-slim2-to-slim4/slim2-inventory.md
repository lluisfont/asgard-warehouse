# Slim 2 migration inventory

| File | Line | Pattern | Evidence | Suggested action |
|---|---:|---|---|---|
| `app/start.php` | 9 | `slim2_app` | `new \Slim\Slim(` | Replace with Slim\Factory\AppFactory::create(). |
| `app/start.php` | 21 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/start.php` | 21 | `colon_route_params` | `", $username, $password);     $conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/start.php` | 42 | `colon_route_params` | `');  $verifyToken = function (){          $app = \Slim\Slim::getInstance` | Convert route placeholders from :param to {param}. |
| `app/start.php` | 50 | `colon_route_params` | `'];     try {          $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/start.php` | 60 | `colon_route_params` | `'         )));         $app->stop();              }      };  $verifyRole = function ($idmodulo,$tipoPermiso) use($conexi` | Convert route placeholders from :param to {param}. |
| `app/start.php` | 74 | `colon_route_params` | `'];         $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/start.php` | 92 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/start.php` | 48 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/start.php` | 72 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/start.php` | 10 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/start.php` | 47 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/start.php` | 57 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/start.php` | 71 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/start.php` | 101 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/start.php` | 28 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/start.php` | 55 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/start.php` | 10 | `headers_set` | `headers->set(` | Use withHeader(). |
| `app/start.php` | 47 | `headers_set` | `headers->set(` | Use withHeader(). |
| `app/start.php` | 71 | `headers_set` | `headers->set(` | Use withHeader(). |
| `scripts/strip-phpunit-eager-load.php` | 34 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `scripts/strip-phpunit-eager-load.php` | 51 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `tests/E2E/AteGasImagesE2ETest.php` | 89 | `colon_route_params` | `'/^data:image` | Convert route placeholders from :param to {param}. |
| `tests/E2E/AteGasImagesE2ETest.php` | 91 | `colon_route_params` | `'itemImageSrc debe tener formato data:image` | Convert route placeholders from :param to {param}. |
| `tests/E2E/AteGasImagesE2ETest.php` | 225 | `colon_route_params` | `'data:image` | Convert route placeholders from :param to {param}. |
| `tests/Unit/BlobStorageServiceTest.php` | 307 | `colon_route_params` | `', $capturedUrl);     } }  /**  * Subclase que permite inyectar la configuración directamente sin depender de  * constan` | Convert route placeholders from :param to {param}. |
| `tests/Unit/BlobStorageServiceTest.php` | 323 | `colon_route_params` | `'    => $authMode) as $prop => $val) {             $p = $ref->getProperty($prop);             $p->setAccessible(true);  ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/index.php` | 60 | `colon_route_params` | `';         QRcode::png` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/index.php` | 66 | `colon_route_params` | `';             QRcode::png` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/index.php` | 89 | `colon_route_params` | `';              // benchmark     QRtools::timeBenchmark` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/index.php` | 25 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/index.php` | 66 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/index.php` | 72 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/index.php` | 75 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/index.php` | 86 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/index.php` | 88 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/phpqrcode.php` | 96 | `colon_route_params` | `',  1); 	 	class qrstr { 		public static function set(&$srctab, $x, $y, $repl, $replLen = false) { 			$srctab[$y] = subs` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 182 | `colon_route_params` | `';                              if (count($mode) > 1) {                 $eccLevel = $mode[1];             }             ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 199 | `colon_route_params` | `'][] = $arrAdd;             }                                  return $barcode_array;         }                  //-----` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 214 | `colon_route_params` | `'); 			 			$mask = new QRmask();             for ($a=1; $a <= QRSPEC_VERSION_MAX; $a++) {                 $frame = QRspe` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 220 | `colon_route_params` | `';                     QRimage::png(self::binarize($frame), $fileName, 1, 0);                 } 				 				$width = count(` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 239 | `colon_route_params` | `'Y-m-d H:i:s` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 241 | `colon_route_params` | `'Y-m-d H:i:s` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 267 | `colon_route_params` | `'][$markerId] = $time;         }                  //--------------------------------------------------------------------` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 280 | `colon_route_params` | `"text-align:center` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 285 | `colon_route_params` | `"text-align:right` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 295 | `colon_route_params` | `"text-align:right` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 297 | `colon_route_params` | `';         }              }          //##########################################################################       ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 352 | `colon_route_params` | `',           3);      class QRspec {              public static $capacity = array(             array(  0,    0, 0, array` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 599 | `colon_route_params` | `"             );                                                  $yStart = $oy-2;                      $xStart = $ox-2;` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 713 | `colon_route_params` | `"             );                                                      for($y=0; $y<7; $y++) {                 QRstr::set` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 725 | `colon_route_params` | `", $width);             $frame = array_fill(0, $width, $frameLine);              // Finder pattern             self::put` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 743 | `colon_route_params` | `", 8);                          QRstr::set($frame, 0, 7, $setPattern);             QRstr::set($frame, $width-8, 7, $setP` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 750 | `colon_route_params` | `", 9);             QRstr::set($frame, 0, 8, $setPattern);             QRstr::set` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 758 | `colon_route_params` | `";             }              // Timing pattern                            for($i=1; $i<$width-15; $i++) {              ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 873 | `colon_route_params` | `';                                  if (QR_CACHEABLE) {                     if (file_exists($fileName)) {               ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 936 | `colon_route_params` | `', true);      class QRimage {              //----------------------------------------------------------------------    ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 951 | `colon_route_params` | `");                     ImagePng($image);                 }else{                     ImagePng($image, $filename);       ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 1042 | `colon_route_params` | `', 16);      class QRinputItem {              public $mode;         public $size;         public $data;         public $` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 1060 | `colon_route_params` | `',$setData));                 return null;             }                          $this->mode = $mode;             $this` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 1094 | `colon_route_params` | `'));                     $bs->appendNum(7, $val);                 }                  $this->bstream = $bs;              ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 1405 | `colon_route_params` | `'))){                     return false;                 }             }              return true;         }          //-` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 1635 | `colon_route_params` | `');                     return -1;                 } else if($ver > $this->getVersion()) {                     $this->se` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 1990 | `colon_route_params` | `')));         }                  //----------------------------------------------------------------------         public` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 2189 | `colon_route_params` | `')                     return 0;                  $mode = $this->identifyMode(0);                                  switc` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 2324 | `colon_route_params` | `'t have more roots than symbol values!             if($pad < 0 \|\| $pad >= ((1<<$symsize) -1 - $nroots)) return $rs; // T` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 2503 | `colon_route_params` | `', 10);  	class QRmask { 	 		public $runLength = array(); 		 		//-------------------------------------------------------` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 2618 | `colon_route_params` | `';              if (QR_CACHEABLE) {                 if (file_exists($fileName)) {                     $bitMask = self::u` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 2626 | `colon_route_params` | `'.$maskNo);                     file_put_contents($fileName, self::serial` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 2873 | `colon_route_params` | `');             }              QRspec::getEccSpec($input->getVersion(), $input->getErrorCorrectionLevel(), $spec);      ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 2887 | `colon_route_params` | `');                 return null;             }              $this->count = 0;         }                  //-------------` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 2977 | `colon_route_params` | `');             }              $raw = new QRrawcode($input);                          QRtools::markTime` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 2982 | `colon_route_params` | `');                          $version = $raw->version;             $width = QRspec::getWidth($version);             $fra` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 3004 | `colon_route_params` | `');                          unset($raw);                          // remainder bits             $j = QRspec::getRemaind` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 3075 | `colon_route_params` | `');                 return NULL;             }              $input = new QRinput($version, $level);             if($inpu` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 3245 | `colon_route_params` | `':                         $enc->level = QR_ECLEVEL_H;                     break;             }                         ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 3281 | `colon_route_params` | `", QRtools::binarize($code->data)));             } else {                 return QRtools::binarize` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 3297 | `colon_route_params` | `')                     QRtools::log($outfile, $err);                                  $maxSize = (int)(QR_PNG_MAXIMUM_SI` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/phpqrcode.php` | 253 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/phpqrcode.php` | 279 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/phpqrcode.php` | 285 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/phpqrcode.php` | 294 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/phpqrcode.php` | 814 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/phpqrcode.php` | 815 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/phpqrcode.php` | 816 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/phpqrcode.php` | 846 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/phpqrcode.php` | 847 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/phpqrcode.php` | 848 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/qrconst.php` | 48 | `colon_route_params` | `',  1); 	 	class qrstr { 		public static function set(&$srctab, $x, $y, $repl, $replLen = false) { 			$srctab[$y] = subs` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrencode.php` | 65 | `colon_route_params` | `');             }              QRspec::getEccSpec($input->getVersion(), $input->getErrorCorrectionLevel(), $spec);      ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrencode.php` | 79 | `colon_route_params` | `');                 return null;             }              $this->count = 0;         }                  //-------------` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrencode.php` | 169 | `colon_route_params` | `');             }              $raw = new QRrawcode($input);                          QRtools::markTime` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrencode.php` | 174 | `colon_route_params` | `');                          $version = $raw->version;             $width = QRspec::getWidth($version);             $fra` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrencode.php` | 196 | `colon_route_params` | `');                          unset($raw);                          // remainder bits             $j = QRspec::getRemaind` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrencode.php` | 267 | `colon_route_params` | `');                 return NULL;             }              $input = new QRinput($version, $level);             if($inpu` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrencode.php` | 437 | `colon_route_params` | `':                         $enc->level = QR_ECLEVEL_H;                     break;             }                         ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrencode.php` | 473 | `colon_route_params` | `", QRtools::binarize($code->data)));             } else {                 return QRtools::binarize` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrencode.php` | 489 | `colon_route_params` | `')                     QRtools::log($outfile, $err);                                  $maxSize = (int)(QR_PNG_MAXIMUM_SI` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrimage.php` | 25 | `colon_route_params` | `', true);      class QRimage {              //----------------------------------------------------------------------    ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrimage.php` | 40 | `colon_route_params` | `");                     ImagePng($image);                 }else{                     ImagePng($image, $filename);       ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrinput.php` | 29 | `colon_route_params` | `', 16);      class QRinputItem {              public $mode;         public $size;         public $data;         public $` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrinput.php` | 47 | `colon_route_params` | `',$setData));                 return null;             }                          $this->mode = $mode;             $this` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrinput.php` | 81 | `colon_route_params` | `'));                     $bs->appendNum(7, $val);                 }                  $this->bstream = $bs;              ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrinput.php` | 392 | `colon_route_params` | `'))){                     return false;                 }             }              return true;         }          //-` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrinput.php` | 622 | `colon_route_params` | `');                     return -1;                 } else if($ver > $this->getVersion()) {                     $this->se` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrmask.php` | 31 | `colon_route_params` | `', 10);  	class QRmask { 	 		public $runLength = array(); 		 		//-------------------------------------------------------` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrmask.php` | 146 | `colon_route_params` | `';              if (QR_CACHEABLE) {                 if (file_exists($fileName)) {                     $bitMask = self::u` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrmask.php` | 154 | `colon_route_params` | `'.$maskNo);                     file_put_contents($fileName, self::serial` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrrscode.php` | 69 | `colon_route_params` | `'t have more roots than symbol values!             if($pad < 0 \|\| $pad >= ((1<<$symsize) -1 - $nroots)) return $rs; // T` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrspec.php` | 40 | `colon_route_params` | `',           3);      class QRspec {              public static $capacity = array(             array(  0,    0, 0, array` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrspec.php` | 287 | `colon_route_params` | `"             );                                                  $yStart = $oy-2;                      $xStart = $ox-2;` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrspec.php` | 401 | `colon_route_params` | `"             );                                                      for($y=0; $y<7; $y++) {                 QRstr::set` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrspec.php` | 413 | `colon_route_params` | `", $width);             $frame = array_fill(0, $width, $frameLine);              // Finder pattern             self::put` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrspec.php` | 431 | `colon_route_params` | `", 8);                          QRstr::set($frame, 0, 7, $setPattern);             QRstr::set($frame, $width-8, 7, $setP` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrspec.php` | 438 | `colon_route_params` | `", 9);             QRstr::set($frame, 0, 8, $setPattern);             QRstr::set` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrspec.php` | 446 | `colon_route_params` | `";             }              // Timing pattern                            for($i=1; $i<$width-15; $i++) {              ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrspec.php` | 561 | `colon_route_params` | `';                                  if (QR_CACHEABLE) {                     if (file_exists($fileName)) {               ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrspec.php` | 502 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/qrspec.php` | 503 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/qrspec.php` | 504 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/qrspec.php` | 534 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/qrspec.php` | 535 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/qrspec.php` | 536 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/qrsplit.php` | 53 | `colon_route_params` | `')));         }                  //----------------------------------------------------------------------         public` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrsplit.php` | 252 | `colon_route_params` | `')                     return 0;                  $mode = $this->identifyMode(0);                                  switc` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrtools.php` | 49 | `colon_route_params` | `';                              if (count($mode) > 1) {                 $eccLevel = $mode[1];             }             ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrtools.php` | 66 | `colon_route_params` | `'][] = $arrAdd;             }                                  return $barcode_array;         }                  //-----` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrtools.php` | 81 | `colon_route_params` | `'); 			 			$mask = new QRmask();             for ($a=1; $a <= QRSPEC_VERSION_MAX; $a++) {                 $frame = QRspe` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrtools.php` | 87 | `colon_route_params` | `';                     QRimage::png(self::binarize($frame), $fileName, 1, 0);                 } 				 				$width = count(` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrtools.php` | 106 | `colon_route_params` | `'Y-m-d H:i:s` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrtools.php` | 108 | `colon_route_params` | `'Y-m-d H:i:s` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrtools.php` | 134 | `colon_route_params` | `'][$markerId] = $time;         }                  //--------------------------------------------------------------------` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrtools.php` | 147 | `colon_route_params` | `"text-align:center` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrtools.php` | 152 | `colon_route_params` | `"text-align:right` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrtools.php` | 162 | `colon_route_params` | `"text-align:right` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrtools.php` | 164 | `colon_route_params` | `';         }              }          //##########################################################################       ` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/qrtools.php` | 120 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/qrtools.php` | 146 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/qrtools.php` | 152 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/qrtools.php` | 161 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/piramide-uploader/example.php` | 21 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/piramide-uploader/example.php` | 25 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/piramide-uploader/example.php` | 26 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `lib/phpqrcode/bindings/tcpdf/qrcode.php` | 226 | `colon_route_params` | `',  3);      /** 	 * Down point base value for case 3 mask pattern (1:1:3:1:1(dark:bright:dark:bright:dark` | Convert route placeholders from :param to {param}. |
| `lib/phpqrcode/bindings/tcpdf/qrcode.php` | 2104 | `colon_route_params` | `']); 			} 			return $bstream; 		}  		/** 		 * Returns a stream of bits. 		 * @param int $items 		 * @return array padded` | Convert route placeholders from :param to {param}. |
| `app/functions/integraciones_asgard.php` | 37 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/functions/integraciones_asgard.php` | 37 | `colon_route_params` | `", $username_asggard, $password_asgard);             $this->conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/functions/integraciones_asgard.php` | 66 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/functions/integraciones_asgard.php` | 75 | `colon_route_params` | `"INSERT INTO ocr_prm_atlantes (idcliente,    partida,    numero_prm,     factura,    descripcion,    valor,  created_at)` | Convert route placeholders from :param to {param}. |
| `app/functions/integraciones_asgard.php` | 79 | `colon_route_params` | `':idcliente` | Convert route placeholders from :param to {param}. |
| `app/functions/integraciones_asgard.php` | 80 | `colon_route_params` | `':partida` | Convert route placeholders from :param to {param}. |
| `app/functions/integraciones_asgard.php` | 81 | `colon_route_params` | `':numero_prm` | Convert route placeholders from :param to {param}. |
| `app/functions/integraciones_asgard.php` | 82 | `colon_route_params` | `':factura` | Convert route placeholders from :param to {param}. |
| `app/functions/integraciones_asgard.php` | 83 | `colon_route_params` | `':descripcion` | Convert route placeholders from :param to {param}. |
| `app/functions/integraciones_asgard.php` | 84 | `colon_route_params` | `':valor` | Convert route placeholders from :param to {param}. |
| `app/functions/integraciones_asgard.php` | 172 | `colon_route_params` | `";             return $resp;         }          // Subir (si dir existe, directo)         if (!$sftp->put($remotePath, $` | Convert route placeholders from :param to {param}. |
| `app/functions/logOVP.php` | 46 | `colon_route_params` | `";         //$result = mysql_query($query);         $result = $conexion->prepare($query);         $result->execute();   ` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 9 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 42 | `colon_route_params` | `");             $resultPagoDetalle->execute();             while ($rs =  $resultPagoDetalle ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 66 | `colon_route_params` | `");                     $resultExtra->execute();                      if (($resultExtra->rowCount()) > 0){              ` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 174 | `colon_route_params` | `"){                 while ($rowOVP =  $resultPago ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 176 | `colon_route_params` | `'H:i:s` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 355 | `colon_route_params` | `"){//verificar si puede guardar en ovp             if (($resultOVP->rowCount()) > 0){                 $detalles = $ovpDe` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 359 | `colon_route_params` | `'H:i:s` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 365 | `colon_route_params` | `');                 }                  //DETALLE productos                 while ($rowOVP =  $resultOVP ->fetch(PDO::FET` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 502 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 584 | `colon_route_params` | `"){//verificar si puede guardar en ovp                 if (($resultOVP->rowCount()) > 0){                     $detalles ` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 588 | `colon_route_params` | `'H:i:s` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 594 | `colon_route_params` | `');                     }                      //DETALLE productos                     while ($rowOVP =  $resultOVP ->fe` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 730 | `colon_route_params` | `");                         $resultXML->execute();                         while ($rsXML =  $resultXML ->fetch(PDO::FETC` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 947 | `colon_route_params` | `'H:i:s` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 1121 | `colon_route_params` | `'H:i:s` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 1340 | `colon_route_params` | `'H:i:s` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 1604 | `colon_route_params` | `'H:i:s` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 1750 | `colon_route_params` | `'H:i:s` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 1890 | `colon_route_params` | `'H:i:s` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 1946 | `colon_route_params` | `'H:i:s` | Convert route placeholders from :param to {param}. |
| `app/functions/ovp.php` | 1938 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/functions/sendmail.php` | 16 | `colon_route_params` | `",         ];          $mails = self::normalizarCorreos($to);         $mailscc = self::normalizarCorreos($tocc);        ` | Convert route placeholders from :param to {param}. |
| `app/functions/sendmail.php` | 23 | `colon_route_params` | `') {             $mails = self::filtrarDominio` | Convert route placeholders from :param to {param}. |
| `app/functions/sendmail.php` | 24 | `colon_route_params` | `');             $mailscc = self::filtrarDominio` | Convert route placeholders from :param to {param}. |
| `app/functions/sendmail.php` | 25 | `colon_route_params` | `');             $mailsbc = self::filtrarDominio` | Convert route placeholders from :param to {param}. |
| `app/functions/sendmail.php` | 49 | `colon_route_params` | `" />                 <title>Simple Transactional Email</title>                 <style>                   /* ------------` | Convert route placeholders from :param to {param}. |
| `app/functions/sendmail.php` | 474 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 16 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 33 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 55 | `colon_route_params` | `'/almacenes/:idalmacen` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 57 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 93 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 113 | `colon_route_params` | `'/almacenes/:idalmacen/:fechacorte` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 130 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 156 | `colon_route_params` | `");         while ($rowdetalle =  $resultdetalle ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 311 | `colon_route_params` | `'/almacenes/historial/detalle/:idingresodetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 333 | `colon_route_params` | `");         while ($rowsalidas =  $resultsalidas ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 371 | `colon_route_params` | `");         while ($rowdiv =  $resultdiv ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 385 | `colon_route_params` | `");                 while ($rowcant =  $resultcant ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 407 | `colon_route_params` | `");                 while ($rowcant =  $resultcant ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 473 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 492 | `colon_route_params` | `'/ingresos/:idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 494 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 501 | `colon_route_params` | `");     while ($rowcliente =  $resultcliente ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 572 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 621 | `colon_route_params` | `'/ingresos/pendientes/:idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 623 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 631 | `colon_route_params` | `");     while ($rowcliente =  $resultcliente ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 724 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 752 | `colon_route_params` | `'/ingresos/reporte/:idcliente/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 756 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 872 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 956 | `colon_route_params` | `'/ingresos/detalle/:idingreso` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 958 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1028 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1116 | `colon_route_params` | `'/ingresos/:idingreso` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1120 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1178 | `colon_route_params` | `");     while ($rowdelete =  $resultdelete ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1188 | `colon_route_params` | `");             while ($rowsalidas =  $resultsalidas ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1199 | `colon_route_params` | `");             while ($rowsalidas =  $resultsalidas ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1347 | `colon_route_params` | `");                     while ($rowalm =  $resultalm ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1392 | `colon_route_params` | `");                         while ($rowdiv =  $resultdiv ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1432 | `colon_route_params` | `");         while ($rowdelete =  $resultdelete ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1455 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1503 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1517 | `colon_route_params` | `");     while ($rowcliente =  $resultcliente ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1522 | `colon_route_params` | `");     while ($rowcliente =  $resultcliente ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1539 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1561 | `colon_route_params` | `'/ingresos/:idingreso` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1563 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1593 | `colon_route_params` | `'.$file_name;             $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1608 | `colon_route_params` | `");             while ($rowembalaje =  $resultembalaje ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1626 | `colon_route_params` | `");             while ($rowbaseproductos =  $resultbaseproductos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1690 | `colon_route_params` | `'/ingresos/:idingreso` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1692 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1868 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1890 | `colon_route_params` | `'/ingresos/pendientes/:idsalidadetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1892 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1902 | `colon_route_params` | `';          $creacion=new Carpetas();     $respuesta=$creacion->procesarCarpeta($idempresa);       $correcto = false;   ` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1933 | `colon_route_params` | `"             UPDATE t_salidadetalle             SET                 tiene_danios_pendiente = :tiene_danios,            ` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1945 | `colon_route_params` | `':tiene_danios` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1946 | `colon_route_params` | `':danios` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1947 | `colon_route_params` | `':tiene_faltante` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1948 | `colon_route_params` | `':faltante` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1949 | `colon_route_params` | `':kilometraje` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1950 | `colon_route_params` | `':idsalidadetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1956 | `colon_route_params` | `"             SELECT idsalidadetalle_accesorios_pendiente, idaccesorios_vehiculos             FROM t_salidadetalle_acces` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1961 | `colon_route_params` | `':idsalidadetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1961 | `colon_route_params` | `' => (int)$idsalidadetalle));         $accesorios_actuales = $stmtSelAcc->fetchAll(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1978 | `colon_route_params` | `"             UPDATE t_salidadetalle_accesorios_pendiente             SET cantidad = :cantidad, texto = :texto          ` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1989 | `colon_route_params` | `':cantidad` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1990 | `colon_route_params` | `':texto` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 1991 | `colon_route_params` | `':id_row` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2004 | `colon_route_params` | `"             INSERT INTO t_salidadetalle_accesorios_pendiente                 (idsalidadetalle, idaccesorios_vehiculos,` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2014 | `colon_route_params` | `':idsalidadetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2015 | `colon_route_params` | `':idacc` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2016 | `colon_route_params` | `':cantidad` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2017 | `colon_route_params` | `':texto` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2025 | `colon_route_params` | `"             UPDATE t_salidadetalle_accesorios_pendiente             SET deleted_at = CURRENT_TIMESTAMP             WHE` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2035 | `colon_route_params` | `':id_row` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2052 | `colon_route_params` | `"             INSERT INTO t_salidadetalleimagen_pendiente (idsalidadetalle, imagen)             VALUES (:idsalidadetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2066 | `colon_route_params` | `'/^data:image` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2097 | `colon_route_params` | `':idsalidadetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2098 | `colon_route_params` | `':imagen` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2166 | `colon_route_params` | `");     while ($rowdetalle =  $resultdetalle ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2209 | `colon_route_params` | `'/^data:image` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2261 | `colon_route_params` | `'/ingresos/pendientes/:idsalida` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2265 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2301 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2323 | `colon_route_params` | `'/descarga/reporte/:idcliente/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2327 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2369 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2405 | `colon_route_params` | `'/productos/:idcliente/:codigo` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2429 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2459 | `colon_route_params` | `'/inter_company/:idingreso` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2497 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2504 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2532 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2584 | `colon_route_params` | `"UPDATE t_ingreso SET envio_api=CURRENT_TIMESTAMP(), respuesta_api=:respuesta_api, payload_api=:payload_api, response_ap` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2586 | `colon_route_params` | `"UPDATE t_ingreso SET respuesta_api=:respuesta_api, payload_api=:payload_api, response_api=:response_api  WHERE idingres` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2590 | `colon_route_params` | `':respuesta_api` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2591 | `colon_route_params` | `':payload_api` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2592 | `colon_route_params` | `':response_api` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2593 | `colon_route_params` | `':idingreso` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2631 | `colon_route_params` | `'/ingresos/actaingreso/:idingreso` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2633 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2676 | `colon_route_params` | `'/ingresos/constancia/:idingreso` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2678 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2721 | `colon_route_params` | `'/salidas/:idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2724 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2730 | `colon_route_params` | `");     while ($rowcliente =  $resultcliente ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2761 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2790 | `colon_route_params` | `'/salidas/reporte/:idcliente/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2794 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2904 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2978 | `colon_route_params` | `'/salidas/detalle/:idsalida` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2991 | `colon_route_params` | `'/salidas/detalle/:idsalidadetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 2993 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3009 | `colon_route_params` | `");     while ($rowimagenes =  $resultimagenes ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3038 | `colon_route_params` | `'/salidas/pendiente/detalle/:idsalidadetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3040 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3056 | `colon_route_params` | `");     while ($rowimagenes =  $resultimagenes ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3089 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3103 | `colon_route_params` | `");     while ($rowcliente =  $resultcliente ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3109 | `colon_route_params` | `");     while ($rowcliente =  $resultcliente ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3140 | `colon_route_params` | `'.$file_name;                 $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3206 | `colon_route_params` | `");                         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3242 | `colon_route_params` | `");                         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3305 | `colon_route_params` | `");                             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3331 | `colon_route_params` | `");                 while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3359 | `colon_route_params` | `'/salidas/:idsalida` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3401 | `colon_route_params` | `");     while ($rowsalida =  $resultsalida ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3407 | `colon_route_params` | `");     while ($rowpedido =  $resultpedido ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3412 | `colon_route_params` | `");     while ($rowcliente =  $resultcliente ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3513 | `colon_route_params` | `");             while ($rowdetalle =  $resultdetalle ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3554 | `colon_route_params` | `");     while ($rowdelete =  $resultdelete ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3586 | `colon_route_params` | `'/salidas/accesorios/:idsalidadetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3588 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3619 | `colon_route_params` | `'/^data:image` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3664 | `colon_route_params` | `'/salidas/actasalida/:idsalida/:unidad_salida` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3666 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3678 | `colon_route_params` | `");     while ($rowsalida =  $resultsalida ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3714 | `colon_route_params` | `'/salidas/constancia/:idsalida` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3716 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3728 | `colon_route_params` | `");     while ($rowsalida =  $resultsalida ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3764 | `colon_route_params` | `'/salidas/:idsalida` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3862 | `colon_route_params` | `");         while ($rowsalida =  $resultsalida ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3875 | `colon_route_params` | `");                         while ($rowingreso =  $resultingreso ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3912 | `colon_route_params` | `'/salidas/:idsalida` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3919 | `colon_route_params` | `");     while ($rowsalida =  $resultsalida ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3936 | `colon_route_params` | `");         while ($rowsalida =  $resultsalida ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3947 | `colon_route_params` | `");                 while ($rowingreso =  $resultingreso ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3977 | `colon_route_params` | `'/inventario/:idcliente/:fechacorte/:corte` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 3980 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4009 | `colon_route_params` | `'/reporteinventario/:idcliente/:fechacorte` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4012 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4029 | `colon_route_params` | `'/reportemovimientos/:idcliente/:idingresodetalle/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4032 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4111 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4130 | `colon_route_params` | `");                 while ($rowdiv =  $resultdiv ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4285 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4533 | `colon_route_params` | `'/reportemovimientosdetalle/:idcliente/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4536 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4608 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4627 | `colon_route_params` | `");                 while ($rowdiv =  $resultdiv ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4762 | `colon_route_params` | `";          $result = $conexion->query($query_ing_salidas);     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4799 | `colon_route_params` | `'/reportemovimientostienda/:idcliente/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4801 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4848 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4867 | `colon_route_params` | `");                 while ($rowdiv =  $resultdiv ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 4936 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5021 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5046 | `colon_route_params` | `");             while ($rowtienda =  $resulttienda ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5094 | `colon_route_params` | `'/reporteliquidacion/:idcliente/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5096 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5235 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5264 | `colon_route_params` | `'/reporteposicionesdia/:idcliente/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5266 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5352 | `colon_route_params` | `'/reporteinventariovencimiento/:idcliente/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5354 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5422 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5507 | `colon_route_params` | `";         $resultiguales = $conexion->query($queryconsultaiguales);         while ($rowiguales =  $resultiguales ->fetc` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5525 | `colon_route_params` | `");             while ($rowubicacion0 =  $resultubicacion0 ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5543 | `colon_route_params` | `";         $resultiguales = $conexion->query($queryconsultaiguales);         while ($rowiguales =  $resultiguales ->fetc` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5648 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5671 | `colon_route_params` | `");             while ($rowalmacenitem =  $resultalmacenitem ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5675 | `colon_route_params` | `");             while ($rowalmacenitem =  $resultalmacenitem ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5683 | `colon_route_params` | `");                 while ($rowubicacion0 =  $resultubicacion0 ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5736 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5766 | `colon_route_params` | `'.$file_name;             $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5788 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5801 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5820 | `colon_route_params` | `"SELECT                                  t_ingresodetalle.idingresodetalle                             FROM             ` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5834 | `colon_route_params` | `":id` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5835 | `colon_route_params` | `":almacen` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5835 | `colon_route_params` | `" => $idalmacen                     ]);                      $existe = $stmt->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5884 | `colon_route_params` | `"                         UPDATE t_ingresodetalle                          SET                          idno_conf = :idn` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5891 | `colon_route_params` | `"                         UPDATE t_ingresodetalle                          SET                          observaciones = ` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5898 | `colon_route_params` | `"                         UPDATE t_ubicacionitem                          SET                          fechasalida = CUR` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5907 | `colon_route_params` | `"                         INSERT INTO t_ubicacionitem (idingresodetalle, idalmacendetalle, fechaingreso)                ` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5917 | `colon_route_params` | `":idno_conf` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5918 | `colon_route_params` | `":idingresodetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5924 | `colon_route_params` | `":observaciones` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5925 | `colon_route_params` | `":idingresodetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5931 | `colon_route_params` | `":idingresodetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5935 | `colon_route_params` | `":idingresodetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5936 | `colon_route_params` | `":idalmacendetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5974 | `colon_route_params` | `'/item/detalle/:idcliente/:serie` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 5977 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6000 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6026 | `colon_route_params` | `'/pedidos/:idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6030 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6038 | `colon_route_params` | `");     while ($rowcliente =  $resultcliente ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6106 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6136 | `colon_route_params` | `'/pedidos/detalle/:idpedido` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6150 | `colon_route_params` | `'/pedidos/salidas/:idpedido` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6154 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6169 | `colon_route_params` | `'/pedidos/:idpedido` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6180 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6185 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6207 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6254 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6293 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6316 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6340 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6377 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6391 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6471 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6498 | `colon_route_params` | `'/pedidos/:idpedido` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6500 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6513 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6534 | `colon_route_params` | `'.$file_name;             $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6613 | `colon_route_params` | `'/pedidos/:idpedido` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6619 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6627 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6738 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6749 | `colon_route_params` | `");                     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6834 | `colon_route_params` | `'/pedidos/:idpedido` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6840 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6850 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6868 | `colon_route_params` | `");                 while ($rowpedido_origen =  $resultpedido_origen ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6874 | `colon_route_params` | `");                 while ($rowdias_creacion_entrega =  $resultdias_creacion_entrega ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6941 | `colon_route_params` | `'/pedidos/:idpedido` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6964 | `colon_route_params` | `'/pedidos/detalle/:idpedido` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 6999 | `colon_route_params` | `'/pedidos/:idpedido` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7036 | `colon_route_params` | `'/pedidos/actapedido/:idpedido` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7038 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7082 | `colon_route_params` | `'/pedidos/reporte/:idcliente/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7086 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7212 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7253 | `colon_route_params` | `'/inventario/mailvencimiento/:idalmacen` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7294 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7369 | `colon_route_params` | `'/inventario/mailvencido/:idalmacen` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7410 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7489 | `colon_route_params` | `'/inventario/mailvencimientoprm/:idalmacen` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7525 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7611 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7656 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7742 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7820 | `colon_route_params` | `'/inventariosfisico/:idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7822 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7840 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7859 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7873 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7889 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7915 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7925 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7954 | `colon_route_params` | `'.$file_name;             $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 7985 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8082 | `colon_route_params` | `'/inventariosfisico/detalle/:idinventariosfisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8095 | `colon_route_params` | `'/inventariosfisico/monitoreo-centros/:idcliente/:idinvnetariofisico/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8097 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8206 | `colon_route_params` | `";     $result = $conexion->query($query);     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8259 | `colon_route_params` | `'/inventariosfisico/detalle/:idinventariofisicodetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8263 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8379 | `colon_route_params` | `'/inventariosfisico/download/:idinventariofisicodetallearchivo` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8381 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8393 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8431 | `colon_route_params` | `'/inventariosfisico/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8433 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8462 | `colon_route_params` | `'.$file_name;             $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8483 | `colon_route_params` | `");             while ($rowbaseproductos =  $resultbaseproductos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8528 | `colon_route_params` | `'/inventariosfisico/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8532 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8590 | `colon_route_params` | `");     while ($rowdelete =  $resultdelete ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8621 | `colon_route_params` | `'/inventariosfisico/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8623 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8656 | `colon_route_params` | `'/inventariosfisico/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8658 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8690 | `colon_route_params` | `'/inventariosfisico/tomainventariofisico/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8692 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8736 | `colon_route_params` | `'/inventariosfisico/conteo/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8738 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8766 | `colon_route_params` | `");     while ($rowdetalle =  $resultdetalle ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8805 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8867 | `colon_route_params` | `'/inventariosfisico/conteo/:idinventariofisicoconteo` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8869 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8886 | `colon_route_params` | `'/inventariosfisico/conteo/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8888 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8924 | `colon_route_params` | `'/inventariosfisico/conteo/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8926 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8939 | `colon_route_params` | `'])]);     $existente = $stmtVerificar->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8949 | `colon_route_params` | `'])]);         $detalle = $stmtDetalle->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8982 | `colon_route_params` | `'/inventariosfisico/conteo/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 8984 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9017 | `colon_route_params` | `'/inventariosfisico/conteo/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9019 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9050 | `colon_route_params` | `'/^data:image` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9095 | `colon_route_params` | `'/inventariosfisico/conteo/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9097 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9121 | `colon_route_params` | `'])]);             $existente = $stmtVerificar->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9131 | `colon_route_params` | `'])]);                 $detalle = $stmtDetalle->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9169 | `colon_route_params` | `'/^data:image` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9216 | `colon_route_params` | `'/inventariosfisico/conteo/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9234 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9274 | `colon_route_params` | `'/inventariosfisico/conteo/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9280 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9289 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9338 | `colon_route_params` | `'/inventariosfisico/conteo/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9367 | `colon_route_params` | `'/inventariosfisico/conteo/:idinventariofisico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9382 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9403 | `colon_route_params` | `'/inventariosfisico/reporte/:idcliente/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9405 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9451 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9495 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9509 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9533 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9544 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9560 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9584 | `colon_route_params` | `'/timbrado/detalle/:idtimbrado/:fecha_inicial/:fecha_final` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9595 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9619 | `colon_route_params` | `'/timbrado/detalle/:idtimbradodetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9648 | `colon_route_params` | `'/timbrado/reporte/:idcliente/:fecha_inicial/:fecha_final` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9659 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9675 | `colon_route_params` | `'/timbrado/:idtimbrado` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9677 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9706 | `colon_route_params` | `'.$file_name;             $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9721 | `colon_route_params` | `");             while ($rowcliente =  $resultcliente ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 9952 | `colon_route_params` | `");                 while ($rowrevision =  $resultrevision ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10115 | `colon_route_params` | `'/timbrado/:idtimbrado` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10117 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10146 | `colon_route_params` | `'.$file_name;             $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10161 | `colon_route_params` | `");             while ($rowcliente =  $resultcliente ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10394 | `colon_route_params` | `");                 while ($rowrevision =  $resultrevision ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10558 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10566 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10595 | `colon_route_params` | `'.$file_name;             $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10641 | `colon_route_params` | `");                         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10658 | `colon_route_params` | `"insert into t_ate_gas (idalmacen,  idcliente,  sede,  tipo,  chasis,   marca, modelo,  color,  cliente,  canal,  config` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10664 | `colon_route_params` | `':idalmacen` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10665 | `colon_route_params` | `':idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10666 | `colon_route_params` | `':sede` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10667 | `colon_route_params` | `':tipo` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10668 | `colon_route_params` | `':chasis` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10669 | `colon_route_params` | `':marca` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10670 | `colon_route_params` | `':modelo` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10671 | `colon_route_params` | `':color` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10672 | `colon_route_params` | `':cliente` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10673 | `colon_route_params` | `':canal` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10674 | `colon_route_params` | `':configuracion` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10675 | `colon_route_params` | `':tipo_tanque` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10676 | `colon_route_params` | `':tipo_ot` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10677 | `colon_route_params` | `':valor_neto` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10678 | `colon_route_params` | `':pedido_ot` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10679 | `colon_route_params` | `':fecha_ot` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10680 | `colon_route_params` | `':prog_envio` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10714 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10747 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10781 | `colon_route_params` | `'/ate-gas/recepcionar/:idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10783 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10794 | `colon_route_params` | `"UPDATE t_ate_gas SET estado_vehiculo=:estado_vehiculo, observaciones=:observaciones, fecha_recepcion=CURRENT_TIMESTAMP(` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10796 | `colon_route_params` | `':estado_vehiculo` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10797 | `colon_route_params` | `':observaciones` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10798 | `colon_route_params` | `':idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10821 | `colon_route_params` | `'/ate-gas/ubicar/:idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10823 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10830 | `colon_route_params` | `';          $params = json_decode($app->request->getBody(),true);          try {         $conexion->setAttribute(PDO::AT` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10840 | `colon_route_params` | `"UPDATE t_ate_gas_ubicacion SET fecha_salida = CURRENT_TIMESTAMP() WHERE idate_gas = :idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10843 | `colon_route_params` | `':idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10847 | `colon_route_params` | `"INSERT INTO t_ate_gas_ubicacion (idate_gas, idalmacendetalle, fecha_ingreso) VALUES (:idate_gas, :idalmacendetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10850 | `colon_route_params` | `':idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10851 | `colon_route_params` | `':idalmacendetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10879 | `colon_route_params` | `'/ate-gas/editar/:idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10881 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10888 | `colon_route_params` | `';          $params = json_decode($app->request->getBody(),true);          try {         $conexion->setAttribute(PDO::AT` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10898 | `colon_route_params` | `"UPDATE t_ate_gas SET configuracion = :configuracion, tipo_tanque=:tipo_tanque, edited_at=CURRENT_TIMESTAMP() WHERE idat` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10901 | `colon_route_params` | `':configuracion` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10902 | `colon_route_params` | `':tipo_tanque` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10903 | `colon_route_params` | `':idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10931 | `colon_route_params` | `'/ate-gas/:idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10933 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10955 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10961 | `colon_route_params` | `";     }else{         try {             $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10969 | `colon_route_params` | `"UPDATE t_ate_gas SET deleted_at = CURRENT_TIMESTAMP() WHERE idate_gas = :idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 10972 | `colon_route_params` | `':idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11005 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11127 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11162 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11180 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11202 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11210 | `colon_route_params` | `'];               try {         // Activar excepciones (si no lo tienes aún)         $conexion->setAttribute(PDO::ATTR_E` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11228 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11246 | `colon_route_params` | `");                 while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11256 | `colon_route_params` | `"INSERT INTO t_ate_gas_etapa (idate_gas, paso, idestado_etapa, created_at)                           VALUES (:idate_gas,` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11259 | `colon_route_params` | `':idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11260 | `colon_route_params` | `':paso` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11269 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11291 | `colon_route_params` | `"INSERT INTO t_ate_gas_etapa_tecnico (idate_gas_etapa, idusuario, created_at)                                  VALUES (:` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11296 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11296 | `colon_route_params` | `', $idate_gas_etapa, PDO::PARAM_INT` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11297 | `colon_route_params` | `':idusuario` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11303 | `colon_route_params` | `"UPDATE t_ate_gas_etapa_tecnico                     SET deleted_at = CURRENT_TIMESTAMP()                     WHERE idate` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11310 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11310 | `colon_route_params` | `', $idate_gas_etapa, PDO::PARAM_INT` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11311 | `colon_route_params` | `':idusuario` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11348 | `colon_route_params` | `'/ate-gas/asignacion-trabajo/tecnicos/:idate_gas_etapa_tecnico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11350 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11358 | `colon_route_params` | `"UPDATE t_ate_gas_etapa_tecnico SET deleted_at=CURRENT_TIMESTAMP() WHERE idate_gas_etapa_tecnico=:idate_gas_etapa_tecnic` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11360 | `colon_route_params` | `':idate_gas_etapa_tecnico` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11386 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11404 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11426 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11434 | `colon_route_params` | `'];               try {         // Activar excepciones (si no lo tienes aún)         $conexion->setAttribute(PDO::ATTR_E` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11452 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11470 | `colon_route_params` | `");                 while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11480 | `colon_route_params` | `"INSERT INTO t_ate_gas_etapa (idate_gas, paso, idestado_etapa, created_at)                           VALUES (:idate_gas,` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11483 | `colon_route_params` | `':idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11484 | `colon_route_params` | `':paso` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11493 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11515 | `colon_route_params` | `"INSERT INTO t_ate_gas_etapa_tecnico_qa (idate_gas_etapa,  idusuario,  created_at)                                      ` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11520 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11520 | `colon_route_params` | `', $idate_gas_etapa, PDO::PARAM_INT` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11521 | `colon_route_params` | `':idusuario` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11527 | `colon_route_params` | `"UPDATE t_ate_gas_etapa_tecnico_qa                     SET deleted_at = CURRENT_TIMESTAMP()                     WHERE id` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11534 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11534 | `colon_route_params` | `', $idate_gas_etapa, PDO::PARAM_INT` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11535 | `colon_route_params` | `':idusuario` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11572 | `colon_route_params` | `'/ate-gas/asignacion-trabajo/tecnicos_qa/:idate_gas_etapa_tecnico_qa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11574 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11582 | `colon_route_params` | `"UPDATE t_ate_gas_etapa_tecnico_qa SET deleted_at=CURRENT_TIMESTAMP() WHERE idate_gas_etapa_tecnico_qa=:idate_gas_etapa_` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11584 | `colon_route_params` | `':idate_gas_etapa_tecnico_qa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11610 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11745 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11787 | `colon_route_params` | `'/ate-gas/gestion-movimiento/vista/:idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11789 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11803 | `colon_route_params` | `'];      try {         $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11811 | `colon_route_params` | `"UPDATE t_ate_gas_ubicacion a             INNER JOIN t_ate_gas_etapa b ON a.idate_gas=b.idate_gas             SET a.fech` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11819 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11823 | `colon_route_params` | `"INSERT INTO t_ate_gas_ubicacion (idate_gas, idate_gas_etapa, idalmacendetalle, fecha_ingreso) SELECT idate_gas, idate_g` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11826 | `colon_route_params` | `':idalmacendetalle` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11827 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11831 | `colon_route_params` | `"UPDATE t_ate_gas_etapa SET              idetapa=:idetapa,              edited_at=CURRENT_TIMESTAMP()              WHERE` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11837 | `colon_route_params` | `':idetapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11838 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11866 | `colon_route_params` | `'/ate-gas/gestion-movimiento/inventario/:idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11868 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11882 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11892 | `colon_route_params` | `");         while ($rowimagenes =  $resultimagenes ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11943 | `colon_route_params` | `'/ate-gas/gestion-movimiento/:idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11945 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 11958 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12001 | `colon_route_params` | `'/ate-gas/gestion-movimiento/inventario/:idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12003 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12045 | `colon_route_params` | `"UPDATE t_ate_gas_etapa SET observaciones_inventario=:observaciones_inventario, fecha_inventario=CURRENT_TIMESTAMP() WHE` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12047 | `colon_route_params` | `':observaciones_inventario` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12048 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12054 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12060 | `colon_route_params` | `"INSERT INTO t_ate_gas_etapa_inventario (idate_gas_etapa, iddanios_vehiculos, descripcion, created_at)                  ` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12063 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12064 | `colon_route_params` | `':iddanios_vehiculos` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12065 | `colon_route_params` | `':descripcion` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12070 | `colon_route_params` | `"UPDATE t_ate_gas_etapa_inventario SET descripcion=:descripcion, edited_at=CURRENT_TIMESTAMP() WHERE idate_gas_etapa_inv` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12072 | `colon_route_params` | `':descripcion` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12073 | `colon_route_params` | `':idate_gas_etapa_inventario` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12079 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12091 | `colon_route_params` | `"UPDATE t_ate_gas_etapa_inventario_imagen                 SET deleted_at = CURRENT_TIMESTAMP()                 WHERE ida` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12098 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12099 | `colon_route_params` | `':iddanios_vehiculos` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12104 | `colon_route_params` | `"UPDATE t_ate_gas_etapa_inventario                 SET deleted_at = CURRENT_TIMESTAMP()                 WHERE idate_gas_` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12111 | `colon_route_params` | `':idate_gas_etapa_inventario` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12117 | `colon_route_params` | `"INSERT INTO t_ate_gas_etapa_imagen (idate_gas_etapa,   nombre_original,    nombre_guardado,    ubicacion_fisica,   ubic` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12185 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12186 | `colon_route_params` | `':nombre_original` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12187 | `colon_route_params` | `':nombre_guardado` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12188 | `colon_route_params` | `':ubicacion_fisica` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12189 | `colon_route_params` | `':ubicacion_thumb` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12196 | `colon_route_params` | `"INSERT INTO t_ate_gas_etapa_inventario_imagen (idate_gas_etapa,   iddanios_vehiculos,   nombre_original,    nombre_guar` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12266 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12267 | `colon_route_params` | `':iddanios_vehiculos` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12268 | `colon_route_params` | `':nombre_original` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12269 | `colon_route_params` | `':nombre_guardado` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12270 | `colon_route_params` | `':ubicacion_fisica` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12271 | `colon_route_params` | `':ubicacion_thumb` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12300 | `colon_route_params` | `'/ate-gas/gestion-movimiento/iniciar/:idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12302 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12315 | `colon_route_params` | `"INSERT INTO t_ate_gas_etapa_tiempos (idate_gas_etapa,   inicio,                 idusuario,  created_at)                ` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12318 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12319 | `colon_route_params` | `':idusuario` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12323 | `colon_route_params` | `"UPDATE t_ate_gas_etapa SET idestado_etapa=2 WHERE idate_gas_etapa=:idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12325 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12358 | `colon_route_params` | `'/ate-gas/gestion-movimiento/pausar/:idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12360 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12375 | `colon_route_params` | `"UPDATE t_ate_gas_etapa_tiempos set fin=CURRENT_TIMESTAMP(), motivo_pausa=:motivo_pausa, edited_at=CURRENT_TIMESTAMP() W` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12377 | `colon_route_params` | `':motivo_pausa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12378 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12382 | `colon_route_params` | `"UPDATE t_ate_gas_etapa SET idestado_etapa=3 WHERE idate_gas_etapa=:idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12384 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12417 | `colon_route_params` | `'/ate-gas/gestion-movimiento/finalizar/:idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12419 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12434 | `colon_route_params` | `"UPDATE t_ate_gas_etapa_tiempos set fin=CURRENT_TIMESTAMP(), edited_at=CURRENT_TIMESTAMP() WHERE idate_gas_etapa=:idate_` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12436 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12440 | `colon_route_params` | `"UPDATE t_ate_gas_etapa SET idestado_etapa=4 WHERE idate_gas_etapa=:idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12442 | `colon_route_params` | `':idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12477 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12642 | `colon_route_params` | `";      $result = $conexion->query($query);     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12690 | `colon_route_params` | `'/ate-gas/estado-pedidos/:idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12692 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12711 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12808 | `colon_route_params` | `') && azure_blob_enabled) ? new BlobStorageService() : null;     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12820 | `colon_route_params` | `");         while ($rowinventario =  $resultinventario ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12831 | `colon_route_params` | `");             while ($rowimagenes =  $resultimagenes ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12880 | `colon_route_params` | `");         while ($rowimagenes =  $resultimagenes ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12945 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12967 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12987 | `colon_route_params` | `'/ate-gas/inventario/:idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 12989 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13031 | `colon_route_params` | `'/ate-gas/etapa/inventario/:idate_gas_etapa` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13033 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13077 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13085 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13114 | `colon_route_params` | `'.$file_name;             $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13120 | `colon_route_params` | `'A2:A` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13146 | `colon_route_params` | `");                         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13163 | `colon_route_params` | `"UPDATE t_ate_gas SET fecha_programacion_salida=CURRENT_TIMESTAMP() WHERE chasis=:chasis` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13168 | `colon_route_params` | `':chasis` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13202 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13232 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13265 | `colon_route_params` | `'/ate-gas/salidas/:idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13267 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13291 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13297 | `colon_route_params` | `";     }else{             try {             $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13305 | `colon_route_params` | `"UPDATE t_ate_gas SET fecha_salida=CURRENT_TIMESTAMP(), destino_salida= :destino_salida, transportista_salida=:transport` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13308 | `colon_route_params` | `':destino_salida` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13309 | `colon_route_params` | `':transportista_salida` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13310 | `colon_route_params` | `':idate_gas` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13341 | `colon_route_params` | `'/ate-gas/reporte-tiempos-proceso/:idcliente/:tipo_filtro/:fecha_inicial/:fecha_final` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13343 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13433 | `colon_route_params` | `";              $result = $conexion->query($query);     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13590 | `colon_route_params` | `");     while ($rowdetalle =  $resultdetalle ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13607 | `colon_route_params` | `");         while ($rowsalidas =  $resultsalidas ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13637 | `colon_route_params` | `");         while ($rowaccesorios =  $resultaccesorios ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13726 | `colon_route_params` | `");     while ($rowdiv =  $resultdiv ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13754 | `colon_route_params` | `");             while ($rowdiv =  $resultdiv ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13840 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 13946 | `colon_route_params` | `");         while ($rowdetalle =  $resultdetalle ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 14084 | `colon_route_params` | `");         while ($rowdetalle =  $resultdetalle ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 14231 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 14402 | `colon_route_params` | `");         while ($rowdetalle =  $resultdetalle ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 14522 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 14546 | `colon_route_params` | `"d/m/Y H:i:s` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 14620 | `colon_route_params` | `");                 while ($rowaccesorio =  $resultaccesorio ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 14703 | `colon_route_params` | `");     while ($rowsalida =  $resultsalida ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 14746 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 15045 | `colon_route_params` | `");     while ($rowsalida =  $resultsalida ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 15069 | `colon_route_params` | `"d/m/Y H:i:s` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 15142 | `colon_route_params` | `");                 while ($rowaccesorio =  $resultaccesorio ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 15288 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 15319 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 15345 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 15661 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 15867 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 16085 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 16442 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 16455 | `colon_route_params` | `");         while ($rowdetalle =  $resultdetalle ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 16476 | `colon_route_params` | `");             while ($rowdisponibilidad =  $resultdisponibilidad ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 16526 | `colon_route_params` | `");         while ($rowtienda =  $resulttienda ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 16541 | `colon_route_params` | `");         while ($rowdetalletienda =  $resultdetalletienda ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 16561 | `colon_route_params` | `");         while ($rowsalidas =  $resultsalidas ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 16595 | `colon_route_params` | `");         while ($rowpreparacion =  $resultpreparacion ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 16754 | `colon_route_params` | `");         while ($rowsalidas =  $resultsalidas ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 16896 | `colon_route_params` | `");     while ($rowdetalle =  $resultdetalle ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 16986 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17000 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17049 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17114 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17138 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17141 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17144 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17147 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17151 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17165 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17168 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17171 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17174 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17178 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17188 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17191 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17194 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17197 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17253 | `colon_route_params` | `')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17254 | `colon_route_params` | `')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17326 | `colon_route_params` | `");          //$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17371 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17563 | `colon_route_params` | `";     }               $resultdetalle = $conexion->query($query);     while ($rowdetalle =  $resultdetalle ->fetch(PDO::` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17636 | `colon_route_params` | `");     while ($rowimagenes =  $resultimagenes ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17667 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17691 | `colon_route_params` | `"H:i` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17694 | `colon_route_params` | `"H:i` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17697 | `colon_route_params` | `"H:i` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17707 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17710 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17713 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17716 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17720 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17734 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17737 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17740 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17743 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17747 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17757 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17760 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17763 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17766 | `colon_route_params` | `' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17818 | `colon_route_params` | `')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17819 | `colon_route_params` | `')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17919 | `colon_route_params` | `') {     $dateTime = DateTime::createFromFormat` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17926 | `colon_route_params` | `', :cuerpo,    :respuesta` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17928 | `colon_route_params` | `':cuerpo` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17929 | `colon_route_params` | `':respuesta` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17956 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 17971 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 18048 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 18058 | `colon_route_params` | `"d/m/Y H:i` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 18061 | `colon_route_params` | `"d/m/Y H:i` | Convert route placeholders from :param to {param}. |
| `app/routes/almacenes.php` | 754 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 1117 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 1500 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 1501 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 1898 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 2262 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 2263 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 2325 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 2722 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 2792 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 3086 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 3087 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 3360 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 3594 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 3978 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 4010 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 4030 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 4534 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 5420 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 5646 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 5975 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 6028 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 6170 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 6290 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 6291 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 6367 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 6374 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 6375 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 6623 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 6829 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 6844 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 7084 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 7660 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 7856 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 7857 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 7920 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 7921 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 8101 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 8260 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 8529 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 8932 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 8990 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 9025 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 9103 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 9217 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 9530 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 9531 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 10564 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 10792 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 10832 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 10890 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 11208 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 11432 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 11800 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 12371 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 12430 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 12484 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 12950 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 13083 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 13276 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/almacenes.php` | 45 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 103 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 300 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 461 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 482 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 611 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 742 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 946 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 1106 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 2395 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 2449 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 2780 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 2968 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 2981 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 3027 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 3074 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 3992 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 3999 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 4019 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 4522 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 4789 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 5083 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 5253 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 5341 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 5411 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 6126 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 6140 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 6159 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 7242 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 7810 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 7845 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 8085 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 8241 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 8249 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 8857 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 8876 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 9393 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 9519 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 9609 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 9665 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 10771 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 11150 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 11190 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 11339 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 11414 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 11563 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 11777 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 11933 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 11991 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 12679 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 12932 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 12977 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 13255 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 13463 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/almacenes.php` | 1494 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 1558 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 1687 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 1887 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 2253 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 2320 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 2603 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 2626 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 2671 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 2716 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 3356 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 3581 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 3659 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 3709 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 3759 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 3907 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 3972 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 5642 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 5729 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 5952 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 5971 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 6021 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 6286 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 6370 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 6495 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 6610 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 6831 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 6938 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 6961 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 6994 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 7031 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 7077 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 7364 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 7484 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 7649 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 7910 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 8079 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 8348 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 8374 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 8426 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 8525 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 8616 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 8651 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 8685 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 8731 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 8920 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 8977 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 9012 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 9090 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 9210 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 9269 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 9332 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 9361 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 9488 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 9581 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 9643 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 10112 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 10553 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 10688 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 10695 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 10709 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 10807 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 10816 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 10874 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 10926 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 10998 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 11369 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 11378 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 11593 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 11602 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 11861 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 12297 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 12346 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 12355 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 12405 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 12414 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 12463 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 12472 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 13026 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 13070 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 13176 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 13183 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 13197 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 13336 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/almacenes.php` | 14 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 55 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 113 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 311 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 470 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 492 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 621 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 752 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 956 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 1116 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 1499 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 1561 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 1690 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 1890 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 2261 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 2323 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 2405 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 2459 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 2631 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 2676 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 2721 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 2790 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 2978 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 2991 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 3038 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 3085 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 3359 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 3586 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 3664 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 3714 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 3764 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 3912 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 3977 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 4009 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 4029 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 4533 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 4799 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 5094 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 5264 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 5352 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 5419 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 5645 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 5734 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 5974 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 6026 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 6136 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 6150 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 6169 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 6289 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 6373 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 6498 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 6613 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 6834 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 6941 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 6964 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 6999 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 7036 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 7082 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 7253 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 7369 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 7489 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 7654 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 7820 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 7855 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 7913 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8082 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8095 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8259 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8379 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8431 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8528 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8621 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8656 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8690 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8736 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8867 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8886 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8924 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 8982 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 9017 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 9095 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 9216 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 9274 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 9338 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 9367 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 9403 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 9493 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 9529 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 9584 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 9619 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 9648 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 9675 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 10115 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 10556 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 10712 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 10781 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 10821 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 10879 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 10931 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 11003 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 11160 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 11200 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 11348 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 11384 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 11424 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 11572 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 11608 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 11787 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 11866 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 11943 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 12001 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 12300 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 12358 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 12417 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 12475 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 12690 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 12943 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 12987 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 13031 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 13075 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 13200 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 13265 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/almacenes.php` | 13341 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/asgard.php` | 18 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 18 | `colon_route_params` | `", $username_asggard, $password_asgard);         $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 42 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 66 | `colon_route_params` | `'/asgard/datosCarpeta/:carpeta` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 79 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 79 | `colon_route_params` | `", $username_asggard, $password_asgard);         $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 165 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 174 | `colon_route_params` | `");         while ($row_clientes =  $result_clientes ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 185 | `colon_route_params` | `");         while ($row_clientes =  $result_clientes ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 237 | `colon_route_params` | `'/asgard/datosPartida/:partida/:idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 250 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 250 | `colon_route_params` | `", $username_asggard, $password_asgard);         $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 265 | `colon_route_params` | `");     while ($row_clientes =  $result_clientes ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 305 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 316 | `colon_route_params` | `");         while ($row_accesorios =  $result_accesorios ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 365 | `colon_route_params` | `'/asgard/inventario/buscar-chasis/:idcliente/:chasis` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 376 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 376 | `colon_route_params` | `", $username_asggard, $password_asgard);         $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 388 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 400 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 412 | `colon_route_params` | `';          $token = JWT::encode` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 548 | `colon_route_params` | `'/asgard/inventario/resumen-inventario/:idcliente/:chasis/:tipo_inventario_id` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 559 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 559 | `colon_route_params` | `", $username_asggard, $password_asgard);         $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 571 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 583 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 595 | `colon_route_params` | `';          $token = JWT::encode` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 637 | `colon_route_params` | `'/asgard/inventario/accesorios/lista/:idcliente/:chasis/:tipo_inventario_id` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 648 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 648 | `colon_route_params` | `", $username_asggard, $password_asgard);         $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 660 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 672 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 684 | `colon_route_params` | `';          $token = JWT::encode` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 726 | `colon_route_params` | `'/asgard/inventario/desperfectos/lista/:idcliente/:chasis/:tipo_inventario_id` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 737 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 737 | `colon_route_params` | `", $username_asggard, $password_asgard);         $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 749 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 761 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 773 | `colon_route_params` | `';          $token = JWT::encode` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 815 | `colon_route_params` | `'/asgard/inventario/contaminacion/lista/:idcliente/:chasis/:tipo_inventario_id` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 826 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 826 | `colon_route_params` | `", $username_asggard, $password_asgard);         $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 838 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 850 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 862 | `colon_route_params` | `';          $token = JWT::encode` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 904 | `colon_route_params` | `'/asgard/inventario/file/download/:idcliente/:chasis` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 919 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 919 | `colon_route_params` | `", $username_asggard, $password_asgard);         $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 931 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 943 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 955 | `colon_route_params` | `';          $token = JWT::encode` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1009 | `colon_route_params` | `'/asgard/inventario/nacional/resumen-inventario/:idcliente/:embarque_id/:chasis/:tipo_inventario_id` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1020 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1020 | `colon_route_params` | `", $username_asggard, $password_asgard);         $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1032 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1044 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1056 | `colon_route_params` | `';          $token = JWT::encode` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1098 | `colon_route_params` | `'/asgard/inventario/nacional/accesorios/lista/:idcliente/:embarque_id/:chasis/:tipo_inventario_id` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1109 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1109 | `colon_route_params` | `", $username_asggard, $password_asgard);         $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1121 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1133 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1145 | `colon_route_params` | `';          $token = JWT::encode` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1187 | `colon_route_params` | `'/asgard/inventario/nacional/desperfectos/lista/:idcliente/:embarque_id/:chasis/:tipo_inventario_id` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1198 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1198 | `colon_route_params` | `", $username_asggard, $password_asgard);         $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1210 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1222 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1234 | `colon_route_params` | `';          $token = JWT::encode` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1276 | `colon_route_params` | `'/asgard/inventario/nacional/contaminacion/lista/:idcliente/:embarque_id/:chasis/:tipo_inventario_id` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1287 | `colon_route_params` | `"mysql:host` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1287 | `colon_route_params` | `", $username_asggard, $password_asgard);         $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1299 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1311 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 1323 | `colon_route_params` | `';          $token = JWT::encode` | Convert route placeholders from :param to {param}. |
| `app/routes/asgard.php` | 905 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/asgard.php` | 56 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/asgard.php` | 227 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/asgard.php` | 354 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/asgard.php` | 530 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/asgard.php` | 626 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/asgard.php` | 715 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/asgard.php` | 804 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/asgard.php` | 893 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/asgard.php` | 998 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/asgard.php` | 1087 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/asgard.php` | 1176 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/asgard.php` | 1265 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/asgard.php` | 1354 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/asgard.php` | 527 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/asgard.php` | 623 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/asgard.php` | 712 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/asgard.php` | 801 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/asgard.php` | 890 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/asgard.php` | 995 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/asgard.php` | 1084 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/asgard.php` | 1173 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/asgard.php` | 1262 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/asgard.php` | 1351 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/asgard.php` | 5 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/asgard.php` | 66 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/asgard.php` | 237 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/asgard.php` | 365 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/asgard.php` | 548 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/asgard.php` | 637 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/asgard.php` | 726 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/asgard.php` | 815 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/asgard.php` | 904 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/asgard.php` | 1009 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/asgard.php` | 1098 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/asgard.php` | 1187 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/asgard.php` | 1276 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/common.php` | 7 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/common.php` | 12 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/common.php` | 45 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/common.php` | 5 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 8 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 111 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 162 | `colon_route_params` | `'/contabilidad/rangofacturas/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 164 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 225 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 270 | `colon_route_params` | `'/contabilidad/generarfactura/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 272 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 300 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 343 | `colon_route_params` | `");             while ($rownrofactura =  $resultnrofactura ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 360 | `colon_route_params` | `");         while ($rownrofactura =  $resultnrofactura ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 388 | `colon_route_params` | `");                 while ($rownrofactura =  $resultnrofactura ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 411 | `colon_route_params` | `");                         while ($rownrofactura =  $resultnrofactura ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 453 | `colon_route_params` | `'/contabilidad/reservarfactura/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 455 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 479 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 508 | `colon_route_params` | `");                 while ($rownrofactura =  $resultnrofactura ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 540 | `colon_route_params` | `'/contabilidad/facturas/download/:idfactura` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 542 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 586 | `colon_route_params` | `'/contabilidad/facturas/download/membretada/:idfactura` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 588 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 632 | `colon_route_params` | `'/contabilidad/facturas/migrarovp/:idfactura` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 634 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 676 | `colon_route_params` | `'/contabilidad/facturas/anular/:idfactura` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 678 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 743 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 783 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 877 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 919 | `colon_route_params` | `'/contabilidad/notascobranza/anular/:idnotadebito` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 921 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 983 | `colon_route_params` | `'/contabilidad/rangonotascobranza/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 985 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1039 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1075 | `colon_route_params` | `'/contabilidad/generarnotacobranza/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1077 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1112 | `colon_route_params` | `");             while ($rownronotadebito =  $resultnronotadebito ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1138 | `colon_route_params` | `'/contabilidad/notascobranza/download/:idnotadebito` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1140 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1184 | `colon_route_params` | `'/contabilidad/notascobranza/download/membretada/:idnotadebito` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1186 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1230 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1297 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1324 | `colon_route_params` | `'/contabilidad/rangoinvoices/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1326 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1394 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1421 | `colon_route_params` | `'/contabilidad/reservarinvoice/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1423 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1451 | `colon_route_params` | `");             while ($rownroinvoice =  $resultnroinvoice ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1481 | `colon_route_params` | `'/contabilidad/generarinvoice/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1483 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1525 | `colon_route_params` | `");             while ($rowinvoice =  $resultinvoice ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1551 | `colon_route_params` | `'/contabilidad/invoices/download/:idinvoice` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1553 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1597 | `colon_route_params` | `'/contabilidad/invoices/download/membretada/:idinvoice` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1599 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1645 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1668 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1691 | `colon_route_params` | `'/contabilidad/generarplanilla/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1693 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1724 | `colon_route_params` | `");             while ($rowplanilla =  $resultplanilla ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1750 | `colon_route_params` | `'/contabilidad/planillas/download/:idplanilla` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1752 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1798 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1895 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1931 | `colon_route_params` | `'/contabilidad/ordenespago/rango/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 1933 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2016 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2048 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2076 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2102 | `colon_route_params` | `'/contabilidad/generarordenpago/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2104 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2140 | `colon_route_params` | `");             while ($rowfacturapago =  $resultfacturaoago ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2165 | `colon_route_params` | `'/contabilidad/ordenespago/download/:idfacturapago` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2167 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2212 | `colon_route_params` | `'/contabilidad/ordenespago/migrarovp/:idfacturapago` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2218 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2236 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2259 | `colon_route_params` | `'/contabilidad/ordenespago/anular/:idfacturapago` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2261 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2324 | `colon_route_params` | `'/contabilidad/generarordenservicio/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2326 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2357 | `colon_route_params` | `");             while ($rowordenservicio =  $resultordenservicio ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2382 | `colon_route_params` | `'/contabilidad/ordenesservicio/:tipo/download/:idordenservicio` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2384 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2429 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2466 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2486 | `colon_route_params` | `'/contabilidad/cobros/:idtipoentidad/:id` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2538 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2566 | `colon_route_params` | `'/contabilidad/cobros/:idtipoentidad/:id/historico/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2598 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2621 | `colon_route_params` | `'/contabilidad/cobros/:idtipoentidad/:id` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2623 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2690 | `colon_route_params` | `'/contabilidad/anticipos/:idtipoentidad/:id` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2715 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2744 | `colon_route_params` | `'/contabilidad/anticipos/:idtipoentidad/:id` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2746 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2788 | `colon_route_params` | `'/contabilidad/download/anticipos/:idanticipo` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2790 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2835 | `colon_route_params` | `'/contabilidad/download/cobros/:numero` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2837 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2882 | `colon_route_params` | `'/contabilidad/pagos/:idtipoentidad/:id` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2935 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2961 | `colon_route_params` | `'/contabilidad/pagos/:idtipoentidad/:id` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 2963 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3014 | `colon_route_params` | `'/contabilidad/pagado/:idtipoentidad/:id` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3044 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3068 | `colon_route_params` | `'/contabilidad/download/pagos/:idpago` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3070 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3115 | `colon_route_params` | `'/contabilidad/saldos/:idtipoentidad/:id` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3129 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3149 | `colon_route_params` | `'/contabilidad/saldos/:idtipoentidad/:id` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3151 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3200 | `colon_route_params` | `'/contabilidad/devuelto/:idtipoentidad/:id` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3220 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3240 | `colon_route_params` | `'/contabilidad/download/devoluciones/:iddevolucion` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3242 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3332 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3357 | `colon_route_params` | `'/contabilidad/reportes/cobranzas/:idtipoentidad/:id/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3423 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3450 | `colon_route_params` | `'/contabilidad/reportes/anticipos/:idtipoentidad/:id/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3504 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3537 | `colon_route_params` | `'/contabilidad/reportes/facturas-concepto/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3560 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3586 | `colon_route_params` | `'/contabilidad/reportes/ordenes-pago-concepto/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3611 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3635 | `colon_route_params` | `'/contabilidad/reportes/conceptos/:fechainicial/:fechafinal` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3869 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 3995 | `colon_route_params` | `");     while ($row =  $result->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4051 | `colon_route_params` | `");         while ($row =  $result->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4090 | `colon_route_params` | `';         while ($row = $resultDatosAdicionales->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4139 | `colon_route_params` | `");         QRcode::png` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4235 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4354 | `colon_route_params` | `" . mysql_error());                         while ($rowdetalle =  $resultdetalle ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4375 | `colon_route_params` | `" . mysql_error());                         while ($row =  $resultdetalle ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4451 | `colon_route_params` | `");                 QRcode::png` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4566 | `colon_route_params` | `");   $data = $result->fetchAll(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4582 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4607 | `colon_route_params` | `");     while ($row = $result->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4625 | `colon_route_params` | `");     while ($rowcorreo = $resultcorreo->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4735 | `colon_route_params` | `" . mysql_error());     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4842 | `colon_route_params` | `" . mysql_error());             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 4994 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 5117 | `colon_route_params` | `" . mysql_error());     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 5192 | `colon_route_params` | `" . mysql_error());             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 5256 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 5338 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 5416 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 5537 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 5638 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 5708 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 5806 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 5832 | `colon_route_params` | `"text-align:center` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 5897 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 5922 | `colon_route_params` | `"text-align:center` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 6024 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 6084 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 6206 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 6286 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 6368 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/contabilidad.php` | 278 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 639 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 687 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 690 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 748 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 930 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 933 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 1082 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 1488 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 1698 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 2109 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 2270 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 2273 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 2331 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 2629 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 2751 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 2968 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 3156 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/contabilidad.php` | 152 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 260 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 909 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 1065 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 1314 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 1411 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 1681 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 1921 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 2036 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 2092 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 2476 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 2556 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 2611 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 2734 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 2951 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 3058 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 3139 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 3230 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 3347 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 3440 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 3527 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 3576 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 3625 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 3892 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/contabilidad.php` | 449 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 536 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 581 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 627 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 671 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 736 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 776 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 979 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 1134 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 1179 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 1225 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 1477 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 1547 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 1592 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 1638 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 1746 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 1791 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 2161 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 2207 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 2254 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 2319 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 2378 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 2423 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 2687 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 2783 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 2830 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 2877 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 3008 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 3110 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 3194 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 3282 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/contabilidad.php` | 6 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 162 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 270 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 453 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 540 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 586 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 632 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 676 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 741 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 781 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 919 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 983 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1075 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1138 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1184 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1228 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1324 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1421 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1481 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1551 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1597 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1643 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1691 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1750 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1796 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 1931 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2046 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2102 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2165 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2212 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2259 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2324 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2382 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2427 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2486 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2566 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2621 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2690 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2744 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2788 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2835 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2882 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 2961 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 3014 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 3068 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 3115 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 3149 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 3200 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 3240 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 3287 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 3357 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 3450 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 3537 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 3586 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 3635 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/contabilidad.php` | 687 | `params_call` | `->params(` | Use getQueryParams() or getParsedBody(). |
| `app/routes/contabilidad.php` | 690 | `params_call` | `->params(` | Use getQueryParams() or getParsedBody(). |
| `app/routes/contabilidad.php` | 930 | `params_call` | `->params(` | Use getQueryParams() or getParsedBody(). |
| `app/routes/contabilidad.php` | 933 | `params_call` | `->params(` | Use getQueryParams() or getParsedBody(). |
| `app/routes/contabilidad.php` | 2270 | `params_call` | `->params(` | Use getQueryParams() or getParsedBody(). |
| `app/routes/contabilidad.php` | 2273 | `params_call` | `->params(` | Use getQueryParams() or getParsedBody(). |
| `app/routes/datosmaestro.php` | 15 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 41 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 61 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 75 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 93 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 131 | `colon_route_params` | `'/contemplaciones/:idcontemplacion` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 133 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 176 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 189 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 207 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 245 | `colon_route_params` | `'/consideraciones/:idconsideraciones` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 247 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 290 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 308 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 328 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 346 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 369 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 387 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 422 | `colon_route_params` | `'/ciudades/:idciudad` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 424 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 442 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 486 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 505 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 525 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 543 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 562 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 580 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 599 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 639 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 661 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 701 | `colon_route_params` | `'/conceptos/:idconcepto` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 721 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 776 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 795 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 809 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 828 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 840 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 886 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 902 | `colon_route_params` | `'/tipo-cambio/:fecha` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 904 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 925 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 942 | `colon_route_params` | `'/tipo-cambio/:fecha` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 944 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 958 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 971 | `colon_route_params` | `");             while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 979 | `colon_route_params` | `");                 while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 990 | `colon_route_params` | `");                     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1046 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1064 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1080 | `colon_route_params` | `'/nombrefactura/:idtipodocumento/:nit` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1082 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1099 | `colon_route_params` | `'/correosfactura/:idtipodocumento/:numero` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1101 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1122 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1128 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1148 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1188 | `colon_route_params` | `'/cuentas/:idcuenta` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1190 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1238 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1257 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1275 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1293 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1312 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1330 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1350 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1368 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1386 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1405 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1423 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1441 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1496 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1589 | `colon_route_params` | `'/productos_cliente/:idbaseproductos` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1649 | `colon_route_params` | `");     while ($rowpreciotimbrado =  $resultpreciotimbrado ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1680 | `colon_route_params` | `'/productos_cliente/:idbaseproductos` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1706 | `colon_route_params` | `'/productos_cliente/:idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1708 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1738 | `colon_route_params` | `'.$file_name;             $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1753 | `colon_route_params` | `");             while ($rowembalaje =  $resultembalaje ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1767 | `colon_route_params` | `");             while ($rowbaseproductos =  $resultbaseproductos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1885 | `colon_route_params` | `'/referencia_salida/:idcliente/:contrato_no` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1908 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1942 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 1990 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2028 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2068 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2094 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2117 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2133 | `colon_route_params` | `'/centros_rubro/:idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2135 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2154 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2176 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2194 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2210 | `colon_route_params` | `'/accesorios_vehiculos/:idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2212 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2232 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2252 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2270 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2288 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2307 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2325 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2330 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2338 | `colon_route_params` | `");     $rootQuery->execute();     $roots = $rootQuery->fetchAll(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2361 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2365 | `colon_route_params` | `");         while ($row_children =  $result_children ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2390 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2406 | `colon_route_params` | `'/solicitantes/:idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2408 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2424 | `colon_route_params` | `'/movilizadores/:idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2426 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2444 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2461 | `colon_route_params` | `"SELECT idmodulo, modulo FROM t_modulo WHERE parent_id = :parent_id` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2462 | `colon_route_params` | `':parent_id` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 2462 | `colon_route_params` | `' => $parent_id]);     $nodes = $query->fetchAll(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/datosmaestro.php` | 97 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 137 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 211 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 251 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 373 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 428 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 644 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 702 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 833 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 908 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 948 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 1152 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 1194 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 1535 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 1590 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/datosmaestro.php` | 25 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 51 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 83 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 197 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 298 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 317 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 359 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 495 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 515 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 533 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 552 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 570 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 629 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 785 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 818 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 894 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 934 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1054 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1072 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1089 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1110 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1138 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1247 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1265 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1283 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1302 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1320 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1340 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1358 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1376 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1394 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1413 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1431 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1526 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 1930 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2077 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2103 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2125 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2144 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2166 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2184 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2202 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2222 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2242 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2260 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2278 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2297 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2315 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2380 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2398 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2416 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2434 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 2452 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/datosmaestro.php` | 125 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 169 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 239 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 283 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 416 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 479 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 695 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 769 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 878 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 1041 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 1182 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 1231 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 1586 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 1677 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 1701 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 1882 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 1985 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 2021 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 2054 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/datosmaestro.php` | 7 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 33 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 59 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 91 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 131 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 174 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 205 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 245 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 288 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 306 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 326 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 367 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 422 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 484 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 503 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 523 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 541 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 560 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 578 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 637 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 701 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 774 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 793 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 826 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 884 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 902 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 942 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1044 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1062 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1080 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1099 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1120 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1146 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1188 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1236 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1255 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1273 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1291 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1310 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1328 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1348 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1366 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1384 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1403 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1421 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1439 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1534 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1589 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1680 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1706 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1885 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1940 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 1988 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2026 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2059 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2085 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2111 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2133 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2152 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2174 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2192 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2210 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2230 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2250 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2268 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2286 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2305 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2323 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2388 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2406 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2424 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/datosmaestro.php` | 2442 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 7 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 27 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 54 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 78 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 98 | `colon_route_params` | `'/cotizaciones/:idcotizacion` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 146 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 163 | `colon_route_params` | `");         while ($rowcostos =  $resultcostos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 188 | `colon_route_params` | `");         while ($roweventos =  $resulteventos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 209 | `colon_route_params` | `");         while ($rowcontemplaciones =  $resultcontemplaciones ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 228 | `colon_route_params` | `");         while ($rowconsideraciones =  $resultconsideraciones ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 284 | `colon_route_params` | `'/cotizaciones/:idcotizacion` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 287 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 364 | `colon_route_params` | `");     while ($roweventos =  $resulteventos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 394 | `colon_route_params` | `");     while ($rowcostos =  $resultcostos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 409 | `colon_route_params` | `");     while ($rowcontemplaciones =  $resultcontemplaciones ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 440 | `colon_route_params` | `");     while ($rowconsideraciones =  $resultconsideraciones ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 485 | `colon_route_params` | `'/cotizaciones/:idcotizacion/documento/:iddivisa` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 487 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 533 | `colon_route_params` | `'/cotizaciones/:idcotizacion` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 536 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 576 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 599 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 698 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 740 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 751 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 764 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 785 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 788 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 854 | `colon_route_params` | `");         while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 876 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 942 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 969 | `colon_route_params` | `");         while ($roweventos =  $resulteventos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 988 | `colon_route_params` | `");         while ($rowcorreosembarque =  $resultcorreosembarque ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1136 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1138 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1193 | `colon_route_params` | `'/embarques/:idembarque/download/:archivo` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1195 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1230 | `colon_route_params` | `'/embarques/:idembarque/eliminardocumento/:archivo` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1232 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1259 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1320 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1397 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1462 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1464 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1536 | `colon_route_params` | `");     while ($rowcargos =  $resultcargos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1540 | `colon_route_params` | `";         }     }          if(strlen($query)>0){         $result = $conexion->exec($query);          if($result===false` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1583 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1585 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1647 | `colon_route_params` | `");     while ($rowcargos =  $resultcargos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1673 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1675 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1745 | `colon_route_params` | `");     while ($rowcostos =  $resultcostos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1749 | `colon_route_params` | `";         }     }          if(strlen($query)>0){         $result = $conexion->exec($query);          if($result===false` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1794 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1796 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1868 | `colon_route_params` | `");     while ($rowcargos =  $resultcargos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1932 | `colon_route_params` | `");     while ($rowcostos =  $resultcostos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1936 | `colon_route_params` | `";         }     }          if(strlen($query)>0){         $result = $conexion->exec($query);          if($result===false` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1954 | `colon_route_params` | `']                     );                 }             }              if(strlen($querynuevoscostos)>0){                ` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 1996 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2042 | `colon_route_params` | `");     while ($roweventos =  $resulteventos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2070 | `colon_route_params` | `");     while ($rowcorreos =  $resultcorreos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2074 | `colon_route_params` | `";         }     }               if(strlen($query)>0){         $result = $conexion->exec($query);          if($result===` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2091 | `colon_route_params` | `']                     );                 }             }              if(strlen($querynuevoscorreos)>0){               ` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2130 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2147 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2175 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2196 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2226 | `colon_route_params` | `'/embarques/finalizar/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2254 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2256 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2301 | `colon_route_params` | `'/embarques/:idembarque` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2303 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2348 | `colon_route_params` | `'/reporte-embarques/:idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2410 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2501 | `colon_route_params` | `");     while ($rowcargos =  $resultcargos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2588 | `colon_route_params` | `");     while ($rowcostos =  $resultcostos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2643 | `colon_route_params` | `");     while ($rowfacturas =  $resultfacturas ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2673 | `colon_route_params` | `");     while ($rownotascobranza =  $resultnotascobranza ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2702 | `colon_route_params` | `");     while ($rowinvoices =  $resultinvoices ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2731 | `colon_route_params` | `");     while ($rowplanillas =  $resultplanillas ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2762 | `colon_route_params` | `");     while ($rowfacturaspago =  $resultfacturaspago ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2791 | `colon_route_params` | `");     while ($rowordenserviciosi =  $resultordenserviciosi ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2819 | `colon_route_params` | `");     while ($rowordenserviciose =  $resultordenserviciose ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2842 | `colon_route_params` | `" . mysql_error());     while ($row = $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2853 | `colon_route_params` | `");     while ($row = $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2858 | `colon_route_params` | `");     while ($row = $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 2891 | `colon_route_params` | `");     while ($row = $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 3009 | `colon_route_params` | `");                     while ($row = $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 3053 | `colon_route_params` | `" . mysql_error());             while ($row = $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 3069 | `colon_route_params` | `");             while ($row = $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 3088 | `colon_route_params` | `" . mysql_error());         while ($row = $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 3138 | `colon_route_params` | `");     while ($row = $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 3220 | `colon_route_params` | `");             while ($row = $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 3250 | `colon_route_params` | `");         while ($row = $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 3312 | `colon_route_params` | `");     while ($row = $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/embarques.php` | 51 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 52 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 285 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 534 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 604 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 737 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 738 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 786 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 1260 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 1321 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 1398 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 1470 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 1591 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 1681 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 1801 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 1997 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 2131 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/embarques.php` | 40 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/embarques.php` | 274 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/embarques.php` | 726 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/embarques.php` | 1128 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/embarques.php` | 2437 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/embarques.php` | 95 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 480 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 528 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 594 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 782 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 873 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 1190 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 1225 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 1254 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 1315 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 1392 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 1457 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 1578 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 1668 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 1789 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 1991 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 2125 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 2221 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 2249 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 2296 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 2343 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/embarques.php` | 5 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 50 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 98 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 284 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 485 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 533 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 597 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 736 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 785 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 876 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 1136 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 1193 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 1230 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 1259 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 1320 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 1397 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 1462 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 1583 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 1673 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 1794 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 1996 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 2130 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 2226 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 2254 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 2301 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/embarques.php` | 2348 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/empresa.php` | 7 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/empresa.php` | 22 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/empresa.php` | 71 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/empresa.php` | 112 | `colon_route_params` | `'/empresa/cargardocumento/:tipo` | Convert route placeholders from :param to {param}. |
| `app/routes/empresa.php` | 114 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/empresa.php` | 69 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/empresa.php` | 58 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/empresa.php` | 107 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/empresa.php` | 168 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/empresa.php` | 5 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/empresa.php` | 68 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/empresa.php` | 112 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 7 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 65 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 134 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 186 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 237 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 305 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 333 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 381 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 407 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 567 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 580 | `colon_route_params` | `");         while ($rowdireccion =  $resultdireccion ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 601 | `colon_route_params` | `");         while ($rowdiasvencimiento =  $resultdiasvencimiento ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 666 | `colon_route_params` | `'/entidades/clientes/verificarusername/:idcliente/:username` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 674 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 687 | `colon_route_params` | `'/entidades/clientes/no-conf-no-considerar/:idcliente` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 695 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 710 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 940 | `colon_route_params` | `");     while ($rowdireccion =  $resultdireccion ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 967 | `colon_route_params` | `");     while ($rowdiasvencimiento =  $resultdiasvencimiento ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 994 | `colon_route_params` | `");     while ($rowmetodotimbrado =  $resultmetodotimbrado ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1019 | `colon_route_params` | `");     while ($rowcorreosfacturacion =  $resultcorreosfacturacion ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1051 | `colon_route_params` | `");     while ($rowserviciologistico =  $resultserviciologistico ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1089 | `colon_route_params` | `");     while ($rowgestionlogistica =  $resultgestionlogistica ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1125 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1166 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1193 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1301 | `colon_route_params` | `");     while ($rowdireccion =  $resultdireccion ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1334 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1393 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1404 | `colon_route_params` | `");         while ($rowdireccion =  $resultdireccion ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1444 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1570 | `colon_route_params` | `");     while ($rowdireccion =  $resultdireccion ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1595 | `colon_route_params` | `");     while ($rowcorreosfacturacion =  $resultcorreosfacturacion ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1628 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1688 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1699 | `colon_route_params` | `");         while ($rowdireccion =  $resultdireccion ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1739 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1863 | `colon_route_params` | `");     while ($rowdireccion =  $resultdireccion ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1888 | `colon_route_params` | `");     while ($rowcorreosfacturacion =  $resultcorreosfacturacion ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1922 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1962 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 1989 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 2098 | `colon_route_params` | `");     while ($rowdireccion =  $resultdireccion ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/entidades.php` | 715 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/entidades.php` | 828 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/entidades.php` | 1197 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/entidades.php` | 1249 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/entidades.php` | 1448 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/entidades.php` | 1510 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/entidades.php` | 1744 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/entidades.php` | 1805 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/entidades.php` | 1994 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/entidades.php` | 2046 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/entidades.php` | 323 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/entidades.php` | 396 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/entidades.php` | 658 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/entidades.php` | 679 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/entidades.php` | 700 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/entidades.php` | 1183 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/entidades.php` | 1434 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/entidades.php` | 1729 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/entidades.php` | 1979 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/entidades.php` | 822 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/entidades.php` | 1118 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/entidades.php` | 1243 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/entidades.php` | 1327 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/entidades.php` | 1504 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/entidades.php` | 1621 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/entidades.php` | 1799 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/entidades.php` | 1915 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/entidades.php` | 2040 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/entidades.php` | 2124 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/entidades.php` | 5 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 331 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 405 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 666 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 687 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 708 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 827 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 1123 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 1191 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 1248 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 1332 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 1442 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 1509 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 1626 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 1737 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 1804 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 1920 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 1987 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/entidades.php` | 2045 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 28 | `colon_route_params` | `");     if(($row = $result->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 58 | `colon_route_params` | `");     if(($row = $result->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 80 | `colon_route_params` | `");                 while ($rowpermisos =  $resultpermisos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 105 | `colon_route_params` | `'=>$permisos                 );                 $token = JWT::encode` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 159 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 202 | `colon_route_params` | `");                 while ($rowid =  $resultid ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 241 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 329 | `colon_route_params` | `");     if(($row = $result->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 352 | `colon_route_params` | `");     if(($row = $result->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 437 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 468 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 500 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 530 | `colon_route_params` | `'/usuario/:idusuario` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 532 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 564 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 578 | `colon_route_params` | `");         while ($rowmoverdividir =  $resultmoverdividir ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 598 | `colon_route_params` | `");         while ($rowpedido =  $resultpedido ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 618 | `colon_route_params` | `");         while ($rowalmacen =  $resultalmacen ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 662 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 678 | `colon_route_params` | `'/usuarios/:username` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 681 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 699 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 726 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 736 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 780 | `colon_route_params` | `");             while ($rowid =  $resultid ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 815 | `colon_route_params` | `'/usuarios/:idusuario` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 839 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 890 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 910 | `colon_route_params` | `");     while ($row =  $result ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 955 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 976 | `colon_route_params` | `");     while ($rowpass =  $resultpass ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1016 | `colon_route_params` | `'/usuarios/columnas_moverdividir/:idusuario` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1031 | `colon_route_params` | `");     while ($rowmoverdividir =  $resultmoverdividir ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1091 | `colon_route_params` | `'/usuario/almacenes/:idusuario` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1115 | `colon_route_params` | `");     while ($rowalmacen =  $resultalmacen ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1134 | `colon_route_params` | `'/usuarios/columnas_pedido/:idusuario` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1149 | `colon_route_params` | `");     while ($rowpedido =  $resultpedido ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1211 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1217 | `colon_route_params` | `'];          $key = jwt_key;     $token = JWT::encode` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1239 | `colon_route_params` | `'];     $decoded = JWT::decode` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1246 | `colon_route_params` | `'];          $key = jwt_key;     $token = JWT::encode` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1274 | `colon_route_params` | `");     while ($rowpermisos =  $resultpermisos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1312 | `colon_route_params` | `");             while ($rowpermisos =  $resultpermisos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1329 | `colon_route_params` | `");             while ($rowpermisos =  $resultpermisos ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1347 | `colon_route_params` | `");             while ($rowexiste =  $resultexiste ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 1356 | `colon_route_params` | `");             while ($rowexiste =  $resultexiste ->fetch(PDO::FETCH_ASSOC` | Convert route placeholders from :param to {param}. |
| `app/routes/usuarios.php` | 6 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 7 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 148 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 231 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 272 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 313 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 314 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 427 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 703 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 816 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 895 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 960 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 1017 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 1092 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 1135 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 1215 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 1243 | `app_request` | `$app->request` | Use ServerRequestInterface methods. |
| `app/routes/usuarios.php` | 115 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/usuarios.php` | 123 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/usuarios.php` | 130 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/usuarios.php` | 522 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/usuarios.php` | 652 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/usuarios.php` | 670 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/usuarios.php` | 689 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/usuarios.php` | 1124 | `app_response` | `$app->response` | Use ResponseInterface immutable methods and return the response. |
| `app/routes/usuarios.php` | 143 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 225 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 266 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 307 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 422 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 461 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 810 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 883 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 948 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 1011 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 1086 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 1204 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 1233 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 1262 | `echo_output` | `echo` | Write to response body instead of echoing. |
| `app/routes/usuarios.php` | 5 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 147 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 230 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 271 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 312 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 426 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 466 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 530 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 660 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 678 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 697 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 815 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 888 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 953 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 1016 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 1091 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 1134 | `use_app_closure` | `use ($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 1209 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 1237 | `use_app_closure` | `use($app` | Remove route coupling to app; inject dependencies or use container. |
| `app/routes/usuarios.php` | 115 | `status_set` | `setStatus(` | Use withStatus(). |
| `app/routes/usuarios.php` | 123 | `status_set` | `setStatus(` | Use withStatus(). |
| `app/routes/usuarios.php` | 130 | `status_set` | `setStatus(` | Use withStatus(). |
| `app/services/BlobStorageService.php` | 73 | `colon_route_params` | `'D, d M Y H:i:s` | Convert route placeholders from :param to {param}. |
| `app/services/BlobStorageService.php` | 135 | `colon_route_params` | `'D, d M Y H:i:s` | Convert route placeholders from :param to {param}. |
| `app/services/BlobStorageService.php` | 181 | `colon_route_params` | `'D, d M Y H:i:s` | Convert route placeholders from :param to {param}. |
| `app/services/BlobStorageService.php` | 208 | `colon_route_params` | `'D, d M Y H:i:s` | Convert route placeholders from :param to {param}. |

Total findings: 2191
