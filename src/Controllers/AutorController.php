<?php

namespace App\Controllers;

/**
 * Controller para gerenciar Autores
 * Herda funcionalidades CRUD básicas do BaseController
 */
class AutorController extends BaseController
{
    /**
     * Nome plural da entidade
     * 
     * @var string
     */
    protected string $pluralName = 'autores';

    /**
     * Prepara e valida os dados recebidos do formulário
     * 
     * @return array Dados validados e formatados para o banco de dados
     * @throws \RuntimeException Se o nome estiver vazio
     */
    protected function prepareData(): array
    {
        $nome = trim($_POST['nome'] ?? '');
        
        if (empty($nome)) {
            throw new \RuntimeException("O nome é obrigatório.", 400);
        }
        
        return [
            'Nome' => $nome
        ];
    }
}
