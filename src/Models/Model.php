<?php

namespace App\Models;

use App\Database\Connection;
use PDO;
use PDOException;

/**
 * Classe base abstrata para todos os Models
 * Fornece operações CRUD básicas (Create, Read, Update, Delete)
 * 
 * As classes filhas devem definir:
 * - $table: Nome da tabela no banco de dados
 * - $primaryKey: Nome da chave primária da tabela
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
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY {$this->primaryKey}")->fetchAll();
    }

    /**
     * Cria um novo registro no banco de dados
     * 
     * @param array $data Dados do registro (chave => valor)
     * @return int ID do registro criado
     * @throws \RuntimeException Em caso de erro ou registro duplicado
     */
    public function create(array $data): int
    {
        // Monta a query INSERT dinamicamente baseado nas chaves do array
        $fields = array_keys($data);
        $fieldsList = implode(', ', $fields);
        $valuesList = ':' . implode(', :', $fields);
        $sql = "INSERT INTO {$this->table} ({$fieldsList}) VALUES ({$valuesList})";
        
        // Retorna o ID do registro criado
        return $this->executeQuery(
            $sql, 
            $data, 
            "Registro duplicado. Verifique os dados informados.", 
            "criar registro",
            fn() => (int) $this->db->lastInsertId()
        );
    }

    /**
     * Atualiza um registro existente no banco de dados
     * 
     * @param int $id ID do registro a ser atualizado
     * @param array $data Dados a serem atualizados (chave => valor)
     * @return bool true se atualizado com sucesso
     * @throws \RuntimeException Em caso de erro ou registro duplicado
     */
    public function update(int $id, array $data): bool
    {
        // Monta a cláusula SET dinamicamente
        $setClause = implode(', ', array_map(fn($field) => "{$field} = :{$field}", array_keys($data)));
        
        // Adiciona o ID aos dados para usar na cláusula WHERE
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

    /**
     * Exclui um registro do banco de dados
     * 
     * @param int $id ID do registro a ser excluído
     * @return bool true se excluído com sucesso, false se não encontrado
     * @throws \RuntimeException Em caso de erro (ex: registro em uso por outras tabelas)
     */
    public function delete(int $id): bool
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

    /**
     * Executa uma query SQL com tratamento de erros e processamento customizado do resultado
     * 
     * @param string $sql Query SQL a ser executada
     * @param array $params Parâmetros para a query
     * @param string $duplicateMessage Mensagem para erros de duplicação (código 23000)
     * @param string $action Nome da ação sendo executada (para mensagens de erro)
     * @param callable $resultProcessor Função que processa o resultado da query (recebe $stmt e retorna o valor final)
     * @return mixed Resultado processado pela função $resultProcessor
     * @throws \RuntimeException Em caso de erro
     */
    private function executeQuery(string $sql, array $params, string $duplicateMessage, string $action, callable $resultProcessor)
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
