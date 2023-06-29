<?php

namespace App\Controller;

class ImageController
{
    public function displayImage($folder, $subfolder, $filename)
    {
        $path = 'uploads/' . $folder . '/' . $subfolder . '/' . $filename;
        $mimeType = mime_content_type($path);

        header('Content-Type: ' . $mimeType);
        readfile($path);
    }
}
