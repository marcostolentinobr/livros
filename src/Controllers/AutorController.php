<?php

namespace App\Controllers;

/** Controller para gerenciar autores */
class AutorController extends BaseController
{
    protected string $pluralName = 'autores';

    /** Prepara e valida dados do formulário */
    protected function prepareData(): array
    {
        $nome = trim($_POST['nome'] ?? '');
        // Valida nome obrigatório
        if (empty($nome)) {
            throw new \RuntimeException("O nome é obrigatório.", 400);
        }
        return ['Nome' => $nome];
    }
}
