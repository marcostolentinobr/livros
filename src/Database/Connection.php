<?php

namespace App\Database;

use PDO;

/**
 * Classe Connection - Gerenciador de Conexão com Banco de Dados
 * 
 * Esta classe implementa o padrão Singleton, garantindo que exista apenas
 * UMA instância de conexão com o banco de dados durante toda a execução
 * da aplicação. Isso evita criar múltiplas conexões desnecessárias e
 * melhora o desempenho da aplicação.
 * 
 * @package App\Database
 */
class Connection
{
    /**
     * Propriedade estática que armazena a única instância da conexão PDO
     * 
     * - 'private static' = só pode ser acessada dentro desta classe e é compartilhada
     *   entre todas as instâncias (não precisa criar objeto para usar)
     * - '?PDO' = pode ser um objeto PDO ou null (ainda não foi criado)
     * - '$instance' = nome da variável que guarda nossa conexão
     */
    private static ?PDO $instance = null;

    /**
     * Método estático que retorna a conexão com o banco de dados
     * 
     * Este é o método principal da classe. Ele verifica se já existe uma
     * conexão criada. Se não existir, cria uma nova. Se já existir, retorna
     * a mesma conexão que já foi criada anteriormente.
     * 
     * @return PDO Retorna um objeto PDO (PHP Data Object) que representa
     *             a conexão com o banco de dados MySQL
     */
    public static function getInstance(): PDO
    {
        // Verifica se a conexão ainda não foi criada (é null)
        // Se for null, significa que é a primeira vez que estamos chamando este método
        if (self::$instance === null) {
            
            // DSN = Data Source Name (Nome da Fonte de Dados)
            // É uma string que contém todas as informações necessárias
            // para o PDO saber como se conectar ao banco de dados
            // 
            // Formato: "mysql:host=ENDERECO;dbname=NOME_DO_BANCO;charset=CODIFICACAO"
            // - mysql: = tipo de banco de dados (MySQL)
            // - host = endereço do servidor (geralmente 'localhost')
            // - dbname = nome do banco de dados
            // - charset=utf8mb4 = codificação de caracteres (suporta emojis e caracteres especiais)
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=utf8mb4",
                \App\Config\Env::get('DB_HOST'),      // Endereço do servidor MySQL
                \App\Config\Env::get('DB_DATABASE')   // Nome do banco de dados
            );
            
            // Cria uma nova conexão PDO usando:
            // 1. DSN (string de conexão criada acima)
            // 2. Usuário do banco de dados
            // 3. Senha do banco de dados
            // 
            // O PDO é uma classe nativa do PHP que fornece uma interface
            // padronizada para acessar diferentes tipos de bancos de dados
            self::$instance = new PDO(
                $dsn,                                    // String de conexão
                \App\Config\Env::get('DB_USERNAME'),    // Usuário do banco
                \App\Config\Env::get('DB_PASSWORD')     // Senha do banco
            );
        }
        
        // Retorna a conexão (seja ela recém-criada ou já existente)
        // Todas as vezes que chamarmos getInstance(), vamos receber
        // a MESMA conexão, não uma nova conexão
        return self::$instance;
    }
}
