<?php

namespace Tusker\App\Services;

use Twig\Environment;
use Twig\TwigFunction;

class TwigServices
{
    /**
     * Twig constructor instance
     *
     * @param Environment $template
     */
    public function __construct(private $template) {}

    public function addFunctions(): void
    {
        $this->template->addFunction(new TwigFunction('app_version', function() {
            return env('APP_VERSION');
        }));

        $this->template->addFunction(new TwigFunction('csrf', function() {
            return csrf();
        }));
    }
}
