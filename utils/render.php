<?php

function render(string $path, array $variables = []): void
{
    extract($variables);

    ob_start();
    include $path;
    $content = ob_get_clean();
    $variables['content'] = $content;

    include dirname(__DIR__) . '/templates/base.php';
}
