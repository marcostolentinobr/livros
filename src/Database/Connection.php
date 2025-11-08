<?php

namespace App\Database;

use PDO;

/** Gerenciador de conexão com banco de dados (Singleton) */
class Connection
{
    private static ?PDO $instance = null;

    /** Retorna instância única da conexão */
    public static function getInstance(): PDO
    {
        // Cria conexão apenas se ainda não existir
        if (self::$instance === null) {
            // Monta DSN (Data Source Name) para conexão MySQL
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=utf8mb4",
                \App\Config\Env::get('DB_HOST'),
                \App\Config\Env::get('DB_DATABASE')
            );
            
            // Cria instância PDO com credenciais do .env
            self::$instance = new PDO(
                $dsn,
                \App\Config\Env::get('DB_USERNAME'),
                \App\Config\Env::get('DB_PASSWORD')
            );
            
            // Retorna arrays associativos (nome da coluna como chave)
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        
        return self::$instance;
    }
}
