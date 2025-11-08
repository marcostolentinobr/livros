<?php

require_once __DIR__ . '/../vendor/autoload.php';

\App\Config\Env::load();

$dbHost = \App\Config\Env::get('DB_HOST');
$dbUser = \App\Config\Env::get('DB_USERNAME');
$dbPass = \App\Config\Env::get('DB_PASSWORD');
$testDatabase = \App\Config\Env::getOptional('DB_TEST_DATABASE', 'livros_test');
$schemaFile = __DIR__ . '/../database/schema.sql';

try {
    $pdo = new PDO("mysql:host={$dbHost};charset=utf8mb4", 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $stmt = $pdo->query("SHOW DATABASES LIKE '{$testDatabase}'");
    
    if ($stmt->rowCount() === 0) {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS {$testDatabase} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("GRANT ALL PRIVILEGES ON {$testDatabase}.* TO '{$dbUser}'@'%'");
        $pdo->exec("FLUSH PRIVILEGES");

        $schemaContent = file_get_contents($schemaFile);
        $schemaContent = preg_replace('/USE\s+livros_db\s*;/i', "USE {$testDatabase};", $schemaContent);

        $pdoTest = new PDO("mysql:host={$dbHost};dbname={$testDatabase};charset=utf8mb4", 'root', 'root', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $lines = explode("\n", $schemaContent);
        $currentStatement = '';
        
        foreach ($lines as $line) {
            $line = trim(preg_replace('/--.*$/', '', $line));
            if (empty($line)) continue;
            
            $currentStatement .= $line . "\n";
            
            if (substr(rtrim($line), -1) === ';') {
                $statement = trim($currentStatement);
                $currentStatement = '';
                
                if (empty($statement) || preg_match('/^USE\s+/i', $statement)) {
                    continue;
                }
                
                try {
                    $pdoTest->exec($statement);
                } catch (PDOException $e) {
                    if (strpos($e->getMessage(), 'already exists') === false && 
                        strpos($e->getMessage(), 'Duplicate') === false) {
                        error_log("SQL Warning: " . $e->getMessage());
                    }
                }
            }
        }
    }
} catch (PDOException $e) {
    // Ignora erro se não conseguir criar banco de teste
}
