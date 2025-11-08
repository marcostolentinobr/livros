<?php

namespace App\Config;

/** Configuração principal da aplicação */
class App
{
    /** Inicializa aplicação carregando variáveis de ambiente */
    public static function init(): void
    {
        Env::load();
    }

    /** Gera URL completa para uma rota */
    public static function url(string $route = ''): string
    {
        return Env::get('BASE_URL') . '/' . trim($route, '/');
    }
}
