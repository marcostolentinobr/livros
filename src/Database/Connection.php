<?php

namespace App\Database;

use PDO;

/**
 * Gerencia a conexão com o banco de dados usando padrão Singleton
 * Garante uma única instância de conexão durante toda a execução
 */
class Connection
{
    private static ?PDO $instance = null;

    /**
     * Obtém a instância única da conexão PDO
     * 
     * Se a conexão ainda não foi criada, cria uma nova usando as configurações
     * do arquivo .env. Se já existe, retorna a instância existente.
     * 
     * @return PDO Conexão com o banco de dados
     */
    public static function getInstance(): PDO
    {
        // Se a conexão ainda não foi criada, cria uma nova
        if (self::$instance === null) {
            // Obter configurações do banco de dados do arquivo .env
            $host = \App\Config\Env::get('DB_HOST');
            $database = \App\Config\Env::get('DB_DATABASE');
            $username = \App\Config\Env::get('DB_USERNAME');
            $password = \App\Config\Env::get('DB_PASSWORD');
            
            // Montar DSN (Data Source Name) para conexão MySQL
            $dsn = sprintf("mysql:host=%s;dbname=%s;charset=utf8mb4", $host, $database);
            
            // Criar e armazenar a conexão
            self::$instance = new PDO($dsn, $username, $password);
        }
        
        return self::$instance;
    }
}
