<?php

namespace App\Config;

/**
 * Classe para carregar e acessar variáveis de ambiente do arquivo .env
 */
class Env
{
    /**
     * Carrega as variáveis do arquivo .env para o ambiente PHP
     * 
     * Processa cada linha do arquivo no formato CHAVE=valor
     * e define as variáveis em $_ENV, $_SERVER e putenv()
     */
    public static function load(): void
    {
        $envFile = __DIR__ . '/../../.env';
        
        // Lê todas as linhas do arquivo .env
        $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        
        // Processa cada linha do arquivo
        foreach ($lines as $line) {
            // Remove BOM (Byte Order Mark) e espaços em branco
            $line = trim(preg_replace('/^\xEF\xBB\xBF/', '', $line));
            
            // Processa apenas linhas que contêm '=' (formato: CHAVE=valor)
            if (strpos($line, '=') !== false) {
                // Divide a linha em chave e valor
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                
                // Remove aspas simples ou duplas do valor
                $value = trim(trim($value, '"\''));
                
                // Define a variável apenas se a chave e valor não estiverem vazios
                // e se a variável ainda não foi definida
                if (!empty($key) && !empty($value) && !isset($_ENV[$key])) {
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                    putenv("{$key}={$value}");
                }
            }
        }
    }

    /**
     * Obtém uma variável obrigatória do arquivo .env
     * 
     * @param string $key Nome da variável
     * @return string Valor da variável
     * @throws \RuntimeException Se a variável não existir ou estiver vazia
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
     */
    public static function getOptional(string $key, ?string $default = null): ?string
    {
        // Tenta obter de $_ENV primeiro, depois de getenv()
        $value = $_ENV[$key] ?? getenv($key);
        
        // Retorna o valor se existir e não estiver vazio, senão retorna o padrão
        return ($value !== false && $value !== '') ? (string) $value : $default;
    }
}
