<?php
class Common{
    public function crearThumbGD($srcPath, $thumbPath, $maxW = 320, $maxH = 320) {
        $info = @getimagesize($srcPath);
        if ($info === false) return false;

        $w = $info[0];
        $h = $info[1];
        $type = $info[2];

        // Crear imagen desde origen
        switch ($type) {
            case IMAGETYPE_JPEG:
                $src = @imagecreatefromjpeg($srcPath);
                break;
            case IMAGETYPE_PNG:
                $src = @imagecreatefrompng($srcPath);
                break;
            case IMAGETYPE_WEBP:
                if (!function_exists('imagecreatefromwebp')) return false;
                $src = @imagecreatefromwebp($srcPath);
                break;
            default:
                return false;
        }

        if (!$src) return false;

        // Mantener proporción
        $ratioW = $maxW / $w;
        $ratioH = $maxH / $h;
        $ratio = min($ratioW, $ratioH, 1);

        $nw = (int) floor($w * $ratio);
        $nh = (int) floor($h * $ratio);

        $dst = imagecreatetruecolor($nw, $nh);

        // Fondo blanco (por si el origen tiene transparencia y guardamos JPG)
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);

        // Reescalar
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        // Asegurar carpeta destino
        $dir = dirname($thumbPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        // Guardar thumb como JPG (liviano)
        $ok = imagejpeg($dst, $thumbPath, 82);

        imagedestroy($src);
        imagedestroy($dst);

        return $ok;
    }

    public function crearBase64($srcPath){
        if (file_exists($srcPath)) {
            $imageData = file_get_contents($srcPath);
            $mimeType = mime_content_type($srcPath); // e.g., image/png, image/jpeg
            $base64 = base64_encode($imageData);
            $fileBase64 = "data:$mimeType;base64,$base64";
        }else{
            $fileBase64=null;
        }
        return $fileBase64;
    }
}