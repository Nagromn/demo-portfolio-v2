<?php

function loadEnvVariables(string $filePath): array
{
    $file = fopen($filePath, 'r');
    $envVariables = [];

    if ($file) {
        while (($line = fgets($file)) !== false) {
            $line = trim($line);
            if ($line !== '' && str_contains($line, '=')) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                if (!empty($key) && !empty($value)) {
                    $envVariables[$key] = $value;
                }
            }
        }
        fclose($file);
    }
    return $envVariables;
}

$envFilePath = __DIR__ . '/../.env';
$envVariables = loadEnvVariables($envFilePath);

return $envVariables;
