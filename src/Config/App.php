<?php

namespace App\Config;

/** Configuração principal da aplicação */
class App
{
    public static string $defaultModule = 'livro';
    public static string $baseUrl = '';

    /** Inicializa aplicação carregando variáveis de ambiente */
    public static function init(): void
    {
        Env::load();
        self::$defaultModule = Env::getOptional('DEFAULT_MODULE', 'livro');
        self::$baseUrl = Env::get('BASE_URL');
    }
}
