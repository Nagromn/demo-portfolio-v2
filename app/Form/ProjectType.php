<?php

namespace App\Form;

use App\Model\Upload;
use Exception;

class ProjectType
{
    public function handleRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $upload = new Upload();
                $files = $_FILES['files'] ?? [];

                $upload->insert($files ?? []);

            } catch (Exception $e) {
                // Gérez l'erreur appropriée
                echo 'Une erreur s\'est produite : ' . $e->getMessage();
            }
        }
    }
}