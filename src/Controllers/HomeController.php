<?php

namespace App\Controllers;

/**
 * Controller para a página inicial do sistema
 */
class HomeController extends BaseController
{
    /**
     * Exibe a página inicial com o menu de navegação
     */
    public function index(): void
    {
        $this->render('home/index');
    }
}
