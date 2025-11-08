<?php

namespace App\Config;

/** Gerenciamento de variáveis de ambiente */
class Env
{
    /** Carrega variáveis do arquivo .env */
    public static function load(): void
    {
        // Lê arquivo .env ignorando linhas vazias e preservando quebras de linha
        $lines = @file(__DIR__ . '/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        
        // Processa cada linha do arquivo .env
        foreach ($lines as $line) {
            // Remove BOM (Byte Order Mark) e espaços
            $line = trim(preg_replace('/^\xEF\xBB\xBF/', '', $line));
            
            // Processa apenas linhas com formato CHAVE=valor
            if (strpos($line, '=') !== false) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                // Remove aspas do valor
                $value = trim(trim($value, '"\''));
                
                // Define variável apenas se válida e não existir
                if (!empty($key) && !empty($value) && !isset($_ENV[$key])) {
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                    // Disponibiliza também via getenv()
                    putenv("{$key}={$value}");
                }
            }
        }
    }

    /** Obtém variável obrigatória do .env */
    public static function get(string $key): string
    {
        $value = $_ENV[$key] ?? getenv($key);
        
        // Valida se variável existe e não está vazia
        if ($value === false || $value === '') {
            throw new \RuntimeException("Variável de ambiente '{$key}' não está definida no arquivo .env.");
        }
        
        return (string) $value;
    }

    /** Obtém variável opcional do .env */
    public static function getOptional(string $key, ?string $default = null): ?string
    {
        $value = $_ENV[$key] ?? getenv($key);
        return ($value !== false && $value !== '') ? (string) $value : $default;
    }
}
