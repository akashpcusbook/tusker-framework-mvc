<?php

namespace Tusker\App\Controller;

class Welcome
{
    public function __construct() {}

    public function index(): void
    {
        view('welcome.html.twig');
    }
}
