<?php
/**
 * Bootstrap para PHPUnit
 * Cria automaticamente o banco de teste se não existir
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Carregar arquivo .env
\App\Config\Env::load();

// Configurações do banco de dados
$dbHost = \App\Config\Env::get('DB_HOST');
$dbUser = \App\Config\Env::get('DB_USERNAME');
$dbPass = \App\Config\Env::get('DB_PASSWORD');
$dbRootUser = \App\Config\Env::getOptional('MYSQL_ROOT_USER', 'root');
$dbRootPass = \App\Config\Env::getOptional('MYSQL_ROOT_PASSWORD', 'root');
$testDatabase = \App\Config\Env::getOptional('DB_TEST_DATABASE', 'livros_test');
$schemaFile = __DIR__ . '/../database/schema.sql';

echo "=== Verificando banco de teste...\n";

try {
    // Conectar como root para criar banco
    $pdo = new PDO(
        "mysql:host={$dbHost};charset=utf8mb4",
        $dbRootUser,
        $dbRootPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    // Verificar se banco existe
    $stmt = $pdo->query("SHOW DATABASES LIKE '{$testDatabase}'");
    $databaseExists = $stmt->rowCount() > 0;

    if (!$databaseExists) {
        echo "=== Banco '{$testDatabase}' não existe. Criando...\n";

        // Criar banco
        $pdo->exec("CREATE DATABASE IF NOT EXISTS {$testDatabase} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Banco '{$testDatabase}' criado.\n";

        // Conceder permissões
        $pdo->exec("GRANT ALL PRIVILEGES ON {$testDatabase}.* TO '{$dbUser}'@'%'");
        $pdo->exec("FLUSH PRIVILEGES");
        echo "✅ Permissões concedidas.\n";

        // Ler schema.sql e substituir livros_db por livros_test
        if (!file_exists($schemaFile)) {
            throw new RuntimeException("Arquivo schema.sql não encontrado: {$schemaFile}");
        }

        $schemaContent = file_get_contents($schemaFile);
        
        // Substituir USE livros_db por USE livros_test
        $schemaContent = preg_replace('/USE\s+livros_db\s*;/i', "USE {$testDatabase};", $schemaContent);
        
        // Conectar ao banco de teste
        $pdoTest = new PDO(
            "mysql:host={$dbHost};dbname={$testDatabase};charset=utf8mb4",
            $dbRootUser,
            $dbRootPass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        // Executar SQL linha por linha, removendo comentários
        $lines = explode("\n", $schemaContent);
        $currentStatement = '';
        
        foreach ($lines as $line) {
            // Remover comentários de linha
            $line = preg_replace('/--.*$/', '', $line);
            $line = trim($line);
            
            // Pular linhas vazias
            if (empty($line)) {
                continue;
            }
            
            // Adicionar linha ao statement atual
            $currentStatement .= $line . "\n";
            
            // Se a linha termina com ;, executar o statement
            if (substr(rtrim($line), -1) === ';') {
                $statement = trim($currentStatement);
                $currentStatement = '';
                
                // Pular statements vazios ou apenas USE
                if (empty($statement) || preg_match('/^USE\s+/i', $statement)) {
                    continue;
                }
                
                try {
                    $pdoTest->exec($statement);
                } catch (PDOException $e) {
                    // Ignorar erros de "table already exists" ou "database already exists"
                    if (strpos($e->getMessage(), 'already exists') === false && 
                        strpos($e->getMessage(), 'Duplicate') === false) {
                        // Log do erro mas não interrompe (pode ser statement inválido)
                        error_log("SQL Warning: " . $e->getMessage());
                    }
                }
            }
        }

        echo "✅ Tabelas criadas a partir de schema.sql.\n";
        echo "=== Banco de teste pronto!\n\n";
    } else {
        echo "✅ Banco '{$testDatabase}' já existe.\n\n";
    }

} catch (PDOException $e) {
    echo "⚠️  Aviso: Não foi possível criar banco de teste automaticamente.\n";
    echo "   Erro: " . $e->getMessage() . "\n";
    echo "   Você pode criar manualmente executando:\n";
    echo "   docker-compose exec db mysql -uroot -proot -e \"CREATE DATABASE IF NOT EXISTS {$testDatabase}...\"\n\n";
}

