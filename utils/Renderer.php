<?php

namespace Utils;

class Renderer
{
    public static function render(string $path, array $variables = []): void
    {
        extract($variables);
        ob_start();
        require $path;
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/View/templates/base.php';
    }
}