<?php

namespace App\Config;

/**
 * ============================================
 * CLASSE DE GERENCIAMENTO DE VARIÁVEIS DE AMBIENTE
 * ============================================
 * Carrega e acessa variáveis do arquivo .env
 * Permite configurar a aplicação sem hardcode de valores
 */
class Env
{
    /**
     * Carrega as variáveis do arquivo .env para o ambiente PHP
     * 
     * Processa cada linha do arquivo .env no formato CHAVE=valor
     * e disponibiliza em $_ENV, $_SERVER e putenv()
     * 
     * Exemplo de arquivo .env:
     * BASE_URL=http://localhost:8888
     * DB_HOST=db
     * DB_DATABASE=livros_db
     */
    public static function load(): void
    {
        // 1. Lê todas as linhas do arquivo .env (ignora linhas vazias)
        $lines = @file(__DIR__ . '/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        
        // 2. Processa cada linha do arquivo
        foreach ($lines as $line) {
            // Remove BOM (Byte Order Mark) e espaços em branco
            $line = trim(preg_replace('/^\xEF\xBB\xBF/', '', $line));
            
            // 3. Processa apenas linhas que contêm '=' (formato: CHAVE=valor)
            if (strpos($line, '=') !== false) {
                // Divide a linha em chave e valor
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                
                // Remove aspas simples ou duplas do valor (se houver)
                $value = trim(trim($value, '"\''));
                
                // 4. Define a variável apenas se chave e valor não estiverem vazios
                // e se a variável ainda não foi definida (evita sobrescrever)
                if (!empty($key) && !empty($value) && !isset($_ENV[$key])) {
                    $_ENV[$key] = $value;           // Disponível via $_ENV['CHAVE']
                    $_SERVER[$key] = $value;        // Disponível via $_SERVER['CHAVE']
                    putenv("{$key}={$value}");       // Disponível via getenv('CHAVE')
                }
            }
        }
    }

    /**
     * Obtém uma variável obrigatória do arquivo .env
     * 
     * @param string $key Nome da variável (ex: 'DB_HOST')
     * @return string Valor da variável
     * @throws \RuntimeException Se a variável não existir ou estiver vazia
     * 
     * Exemplo de uso:
     * $host = Env::get('DB_HOST'); // Retorna 'db' ou lança exceção se não existir
     */
    public static function get(string $key): string
    {
        // Tenta obter de $_ENV primeiro, depois de getenv()
        $value = $_ENV[$key] ?? getenv($key);
        
        // Se a variável não existir ou estiver vazia, lança exceção
        if ($value === false || $value === '') {
            throw new \RuntimeException("Variável de ambiente '{$key}' não está definida no arquivo .env.");
        }
        
        return (string) $value;
    }

    /**
     * Obtém uma variável opcional do arquivo .env
     * 
     * Retorna o valor padrão se a variável não existir ou estiver vazia
     * 
     * @param string $key Nome da variável
     * @param string|null $default Valor padrão se a variável não existir
     * @return string|null Valor da variável ou valor padrão
     * 
     * Exemplo de uso:
     * $testDb = Env::getOptional('DB_TEST_DATABASE', 'livros_test');
     * // Retorna 'livros_test' se DB_TEST_DATABASE não existir
     */
    public static function getOptional(string $key, ?string $default = null): ?string
    {
        $value = $_ENV[$key] ?? getenv($key);
        // Retorna o valor se existir e não estiver vazio, senão retorna o padrão
        return ($value !== false && $value !== '') ? (string) $value : $default;
    }
}
