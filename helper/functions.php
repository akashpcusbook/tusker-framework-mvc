<?php

use Twig\Environment;

/**
 * return view of application
 *
 * @param string $file
 * @param array<mixed, mixed> $data
 * @return void
 */
function view(string $file, array $data = []): void
{
    echo render($file, $data);
}

/**
 * returns rendered view
 *
 * @param string $file
 * @param array $data
 * @return string
 */
function render(string $file, array $data = []): string
{
    /**
     * @var Environment $template
     */
    $template = getObjectManager()->get('template');
    return $template->render($file, $data);
}
