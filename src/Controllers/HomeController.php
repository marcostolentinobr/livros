<?php

namespace App\Controllers;

/** Controller para página inicial */
class HomeController extends BaseController
{
    /** Exibe página inicial */
    public function index(): void
    {
        $this->render('home/home_index');
    }
}
