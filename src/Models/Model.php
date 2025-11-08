<?php

namespace App\Models;

use App\Database\Connection;
use PDO;
use PDOException;

/** Classe base abstrata para todos os Models */
abstract class Model
{
    protected $table;
    protected $primaryKey;
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /** Retorna a chave primária do model */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /** Busca um registro pelo ID */
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /** Busca todos os registros da tabela */
    public function findAll()
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY {$this->primaryKey}")->fetchAll();
    }

    /** Busca todos os registros para exibição na listagem (pode ser sobrescrito) */
    public function findAllForIndex()
    {
        return $this->findAll();
    }

    /** Busca IDs de uma relação muitos-para-muitos */
    public function getRelacao($id, $tabela, $campoId)
    {
        $campoEntidade = $this->table . '_' . $this->primaryKey;
        $sql = "SELECT {$campoId} FROM {$tabela} WHERE {$campoEntidade} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return array_column($stmt->fetchAll(), $campoId);
    }

    /** Define relação muitos-para-muitos entre entidade e outra entidade */
    public function setRelacao($id, $ids, $tabela, $campoId)
    {
        // Remove inválidos e duplicatas
        $ids = array_filter(array_unique(array_map('intval', $ids)), fn($v) => $v > 0);
        
        $campoEntidade = $this->table . '_' . $this->primaryKey;
        
        // Transação garante atomicidade (ou tudo ou nada)
        $this->db->beginTransaction();
        $this->db->prepare("DELETE FROM {$tabela} WHERE {$campoEntidade} = :id")->execute(['id' => $id]);
        
        // Insere novas associações
        if (!empty($ids)) {
            $stmt = $this->db->prepare("INSERT INTO {$tabela} ({$campoEntidade}, {$campoId}) VALUES (:id, :relId)");
            foreach ($ids as $relId) {
                $stmt->execute(['id' => $id, 'relId' => $relId]);
            }
        }
        
        $this->db->commit();
    }

    /** Cria um novo registro no banco de dados */
    public function create($data)
    {
        // Monta query INSERT dinamicamente
        $fields = array_keys($data);
        $fieldsList = implode(', ', $fields);
        $valuesList = ':' . implode(', :', $fields);
        $sql = "INSERT INTO {$this->table} ({$fieldsList}) VALUES ({$valuesList})";
        
        return $this->executeQuery(
            $sql, 
            $data, 
            "Registro duplicado. Verifique os dados informados.", 
            "criar registro",
            fn() => (int) $this->db->lastInsertId()
        );
    }

    /** Atualiza um registro existente no banco de dados */
    public function update($id, $data)
    {
        // Monta cláusula SET dinamicamente
        $setClause = implode(', ', array_map(fn($field) => "{$field} = :{$field}", array_keys($data)));
        $data[$this->primaryKey] = $id;
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :{$this->primaryKey}";
        
        return $this->executeQuery(
            $sql, 
            $data, 
            "Registro duplicado. Verifique os dados informados.", 
            "atualizar registro",
            fn() => true
        );
    }

    /** Exclui um registro do banco de dados */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        
        return $this->executeQuery(
            $sql, 
            ['id' => $id], 
            "Não é possível excluir este registro pois está sendo utilizado em outras tabelas.", 
            "excluir registro",
            fn($stmt) => $stmt->rowCount() > 0
        );
    }

    /** Executa query SQL com tratamento de erros */
    private function executeQuery($sql, $params, $duplicateMessage, $action, $resultProcessor)
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $resultProcessor($stmt);
        } catch (PDOException $e) {
            // Código 23000 = violação de constraint (ex: chave duplicada)
            $isDuplicate = $e->getCode() == 23000;
            $message = $isDuplicate ? $duplicateMessage : "Erro ao {$action}: " . $e->getMessage();
            $code = $isDuplicate ? 400 : 500;
            
            throw new \RuntimeException($message, $code);
        }
    }
}
