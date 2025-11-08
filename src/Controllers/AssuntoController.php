<?php

namespace App\Controllers;

/** Controller para gerenciar assuntos */
class AssuntoController extends BaseController
{
    /** Prepara e valida dados do formulário */
    protected function prepareData(): array
    {
        $descricao = trim($_POST['descricao'] ?? '');
        // Valida descrição obrigatória
        if (empty($descricao)) {
            throw new \RuntimeException("A descrição é obrigatória.", 400);
        }
        return ['Descricao' => $descricao];
    }
}
