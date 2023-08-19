<?php

namespace Tusker\App\Controller;

class Welcome extends Controller
{
    public function __construct() {}

    public function index(): void
    {
        view('welcome.html.twig');
    }
}
