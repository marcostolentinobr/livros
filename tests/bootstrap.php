<?php

/**
 * ============================================
 * ARQUIVO DE INICIALIZAÇÃO DOS TESTES
 * ============================================
 * Executado automaticamente pelo PHPUnit antes de cada teste
 * Prepara o ambiente de teste criando um banco de dados separado
 * 
 * Este arquivo é referenciado no phpunit.xml
 */

// 1. Carregar autoloader do Composer (para usar classes da aplicação)
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Carregar variáveis de ambiente do .env
\App\Config\Env::load();

// 3. Obter configurações do banco de dados
$dbHost = \App\Config\Env::get('DB_HOST');
$dbUser = \App\Config\Env::get('DB_USERNAME');
$testDatabase = \App\Config\Env::getOptional('DB_TEST_DATABASE', 'livros_test');
$schemaFile = __DIR__ . '/../database/schema.sql';

try {
    // 4. Conectar ao MySQL como root (para criar banco de teste)
    $pdo = new PDO("mysql:host={$dbHost};charset=utf8mb4", 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,           // Lança exceções em erros
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      // Retorna arrays associativos
    ]);

    // 5. Verificar se o banco de teste já existe
    if ($pdo->query("SHOW DATABASES LIKE '{$testDatabase}'")->rowCount() === 0) {
        // 6. Criar banco de teste se não existir
        $pdo->exec("CREATE DATABASE IF NOT EXISTS {$testDatabase} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // 7. Dar permissões ao usuário da aplicação no banco de teste
        $pdo->exec("GRANT ALL PRIVILEGES ON {$testDatabase}.* TO '{$dbUser}'@'%'");
        $pdo->exec("FLUSH PRIVILEGES");

        // 8. Ler schema.sql e adaptar para o banco de teste
        $schemaContent = preg_replace('/USE\s+livros_db\s*;/i', "USE {$testDatabase};", file_get_contents($schemaFile));
        
        // 9. Conectar ao banco de teste
        $pdoTest = new PDO("mysql:host={$dbHost};dbname={$testDatabase};charset=utf8mb4", 'root', 'root', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        // 10. Executar cada comando SQL do schema
        $currentStatement = '';
        foreach (explode("\n", $schemaContent) as $line) {
            // Remove comentários SQL (-- comentário)
            $line = trim(preg_replace('/--.*$/', '', $line));
            if (empty($line)) continue;
            
            // Acumula linhas até encontrar ponto e vírgula (fim do comando)
            $currentStatement .= $line . "\n";
            if (substr(rtrim($line), -1) === ';') {
                $statement = trim($currentStatement);
                $currentStatement = '';
                
                // Ignora comandos USE e statements vazios
                if (!empty($statement) && !preg_match('/^USE\s+/i', $statement)) {
                    try {
                        // Executa o comando SQL (CREATE TABLE, etc)
                        $pdoTest->exec($statement);
                    } catch (PDOException $e) {
                        // Ignora erros de "já existe" (pode acontecer em testes paralelos)
                        if (strpos($e->getMessage(), 'already exists') === false && 
                            strpos($e->getMessage(), 'Duplicate') === false) {
                            error_log("SQL Warning: " . $e->getMessage());
                        }
                    }
                }
            }
        }
    }
} catch (PDOException $e) {
    // Ignora erro se não conseguir criar banco de teste
    // (pode acontecer se o banco já existe ou se não houver permissões)
}
