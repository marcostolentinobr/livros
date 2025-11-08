<?php

namespace App\Config;

/**
 * Configuração principal da aplicação
 * Centraliza inicialização e utilitários gerais
 */
class App
{
    /**
     * Inicializa a aplicação
     * Carrega as variáveis de ambiente do arquivo .env
     */
    public static function init(): void
    {
        Env::load();
    }

    /**
     * Gera URL completa para uma rota
     * 
     * Exemplos:
     * - url('livro') -> 'http://localhost:8080/livro'
     * - url('livro/edit/3') -> 'http://localhost:8080/livro/edit/3'
     * - url('/livro/') -> 'http://localhost:8080/livro'
     * 
     * @param string $route Rota relativa
     * @return string URL completa
     */
    public static function url(string $route = ''): string
    {
        $baseUrl = Env::get('BASE_URL');
        $trimmedRoute = trim($route, '/');
        return $baseUrl . '/' . $trimmedRoute;
    }
}
