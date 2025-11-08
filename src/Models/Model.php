<?php

namespace App\Models;

use App\Database\Connection;
use PDO;
use PDOException;

/**
 * Classe base abstrata para todos os Models
 * Fornece operações CRUD básicas para interação com o banco de dados
 * 
 * As classes filhas devem definir:
 * - $table: Nome da tabela no banco de dados
 * - $primaryKey: Nome da chave primária
 */
abstract class Model
{
    protected string $table;
    protected string $primaryKey;
    protected PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Busca um registro pelo ID
     * 
     * @param int $id ID do registro
     * @return array|null Dados do registro ou null se não encontrado
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Busca todos os registros da tabela
     * 
     * @return array Lista de todos os registros ordenados pela chave primária
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$this->primaryKey}");
        return $stmt->fetchAll();
    }

    /**
     * Cria um novo registro no banco de dados
     * 
     * @param array $data Dados do registro (chave => valor)
     * @return int ID do registro criado
     * @throws \RuntimeException Em caso de erro
     */
    public function create(array $data): int
    {
        return $this->executeWithErrorHandling(function() use ($data) {
            $keys = array_keys($data);
            $fields = implode(', ', $keys);
            $values = ':' . implode(', :', $keys);
            $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$fields}) VALUES ({$values})");
            $stmt->execute($data);
            return (int) $this->db->lastInsertId();
        }, "Registro duplicado. Verifique os dados informados.", "criar registro");
    }

    /**
     * Atualiza um registro existente no banco de dados
     * 
     * @param int $id ID do registro a ser atualizado
     * @param array $data Dados a serem atualizados (chave => valor)
     * @return bool true se atualizado com sucesso
     * @throws \RuntimeException Em caso de erro
     */
    public function update(int $id, array $data): bool
    {
        return $this->executeWithErrorHandling(function() use ($id, $data) {
            $set = implode(', ', array_map(fn($field) => "{$field} = :{$field}", array_keys($data)));
            $data[$this->primaryKey] = $id;
            $stmt = $this->db->prepare("UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = :{$this->primaryKey}");
            $stmt->execute($data);
            return true;
        }, "Registro duplicado. Verifique os dados informados.", "atualizar registro");
    }


    /**
     * Exclui um registro do banco de dados
     * 
     * @param int $id ID do registro a ser excluído
     * @return bool true se excluído com sucesso, false se não encontrado
     * @throws \RuntimeException Em caso de erro
     */
    public function delete(int $id): bool
    {
        return $this->executeWithErrorHandling(function() use ($id) {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->rowCount() > 0;
        }, "Não é possível excluir este registro pois está sendo utilizado em outras tabelas.", "excluir registro");
    }

    /**
     * Executa uma operação do banco de dados com tratamento genérico de erros
     * 
     * @param callable $operation Operação a ser executada
     * @param string $duplicateMessage Mensagem para erros de duplicação
     * @param string $action Nome da ação sendo executada
     * @return mixed Resultado da operação
     * @throws \RuntimeException Em caso de erro
     */
    private function executeWithErrorHandling(callable $operation, string $duplicateMessage, string $action)
    {
        try {
            return $operation();
        } catch (PDOException $e) {
            $isDuplicate = $e->getCode() == 23000;
            $message = $isDuplicate ? $duplicateMessage : "Erro ao {$action}: " . $e->getMessage();
            $code = $isDuplicate ? 400 : 500;
            
            throw new \RuntimeException($message, $code);
        }
    }
}
