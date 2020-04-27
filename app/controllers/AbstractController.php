<?php

namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractController
{
    protected $renderer;

    public function __construct()
    {
        $loader = new FilesystemLoader('app/views');

        $this->renderer = new Environment($loader, [
            //'cache' => 'cache'
        ]);
    }

    function render(string $template, $data): string
    {
        return $this->renderer->render($template, $data);
    }
}