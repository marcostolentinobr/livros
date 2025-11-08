<?php

namespace App\Config;

/**
 * ============================================
 * CLASSE DE CONFIGURAÇÃO PRINCIPAL DA APLICAÇÃO
 * ============================================
 * Centraliza inicialização e utilitários gerais
 * Ponto de entrada para configurações do sistema
 */
class App
{
    /**
     * Inicializa a aplicação
     * 
     * Carrega as variáveis de ambiente do arquivo .env
     * Deve ser chamado no início da aplicação (em public/index.php)
     * 
     * Exemplo de uso:
     * App::init(); // Carrega todas as variáveis do .env
     */
    public static function init(): void
    {
        Env::load();
    }

    /**
     * Gera URL completa para uma rota
     * 
     * Concatena a BASE_URL com a rota fornecida
     * Remove barras duplicadas e formata corretamente
     * 
     * @param string $route Rota relativa (ex: 'livro', 'livro/edit/3')
     * @return string URL completa (ex: 'http://localhost:8888/livro')
     * 
     * Exemplos de uso:
     * App::url('livro')              -> 'http://localhost:8888/livro'
     * App::url('livro/edit/3')       -> 'http://localhost:8888/livro/edit/3'
     * App::url('/livro/')            -> 'http://localhost:8888/livro' (remove barras extras)
     */
    public static function url(string $route = ''): string
    {
        // Pega BASE_URL do .env e concatena com a rota (remove barras duplicadas)
        return Env::get('BASE_URL') . '/' . trim($route, '/');
    }
}
