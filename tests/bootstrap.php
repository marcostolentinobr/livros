<?php

require_once __DIR__ . '/../vendor/autoload.php';

\App\Config\Env::load();

$dbHost = \App\Config\Env::get('DB_HOST');
$dbUser = \App\Config\Env::get('DB_USERNAME');
$testDatabase = \App\Config\Env::getOptional('DB_TEST_DATABASE', 'livros_test');
$schemaFile = __DIR__ . '/../database/schema.sql';

try {
    // Conecta sem banco específico para criar banco de teste
    $pdo = new PDO("mysql:host={$dbHost};charset=utf8mb4", 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Lança exceções em erros
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Retorna arrays associativos
    ]);

    // Cria banco de teste se não existir
    if ($pdo->query("SHOW DATABASES LIKE '{$testDatabase}'")->rowCount() === 0) {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS {$testDatabase} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("GRANT ALL PRIVILEGES ON {$testDatabase}.* TO '{$dbUser}'@'%'");
        $pdo->exec("FLUSH PRIVILEGES");

        // Substitui nome do banco no schema para banco de teste
        $schemaContent = preg_replace('/USE\s+livros_db\s*;/i', "USE {$testDatabase};", file_get_contents($schemaFile));
        
        // Conecta ao banco de teste para executar schema
        $pdoTest = new PDO("mysql:host={$dbHost};dbname={$testDatabase};charset=utf8mb4", 'root', 'root', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        // Executa comandos SQL do schema linha por linha
        $currentStatement = '';
        // Processa cada linha do schema SQL
        foreach (explode("\n", $schemaContent) as $line) {
            // Remove comentários SQL
            $line = trim(preg_replace('/--.*$/', '', $line));
            // Pula linhas vazias
            if (empty($line)) continue;
            
            $currentStatement .= $line . "\n";
            // Detecta fim do comando (ponto e vírgula)
            if (substr(rtrim($line), -1) === ';') {
                $statement = trim($currentStatement);
                $currentStatement = '';
                
                // Executa apenas comandos válidos (ignora USE)
                if (!empty($statement) && !preg_match('/^USE\s+/i', $statement)) {
                    try {
                        $pdoTest->exec($statement);
                    } catch (PDOException $e) {
                        // Ignora erros de "já existe" (testes paralelos)
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
}
