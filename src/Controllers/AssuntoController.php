<?php

namespace App\Controllers;

/**
 * Controller para gerenciar Assuntos
 * Herda funcionalidades CRUD básicas do BaseController
 */
class AssuntoController extends BaseController
{

    /**
     * Prepara e valida os dados recebidos do formulário
     * 
     * @return array Dados validados e formatados para o banco de dados
     * @throws \RuntimeException Se a descrição estiver vazia
     */
    protected function prepareData(): array
    {
        $descricao = trim($_POST['descricao'] ?? '');
        
        if (empty($descricao)) {
            throw new \RuntimeException("A descrição é obrigatória.", 400);
        }
        
        return [
            'Descricao' => $descricao
        ];
    }
}
