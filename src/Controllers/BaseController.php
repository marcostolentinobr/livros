<?php

namespace App\Controllers;

use App\Config\App;

/**
 * Classe base abstrata para todos os controllers
 * Fornece métodos comuns para operações CRUD e renderização de views
 */
abstract class BaseController
{
    /**
     * Instância do model associado ao controller
     * Carregado automaticamente quando necessário
     */
    protected ?object $model = null;

    /**
     * Obtém a instância do model associado ao controller
     * Cria automaticamente baseado no nome do controller
     * 
     * @return object Instância do model
     * @throws \RuntimeException Se o model não for encontrado
     */
    protected function getModel(): object
    {
        if ($this->model === null) {
            // Extrai o nome do model a partir do nome do controller
            // Ex: AutorController -> Autor
            $modelName = str_replace('Controller', '', basename(str_replace('\\', '/', get_class($this))));
            $modelClass = "App\\Models\\{$modelName}";
            
            if (!class_exists($modelClass)) {
                throw new \RuntimeException("Model '{$modelClass}' não encontrado.", 500);
            }
            
            $this->model = new $modelClass();
        }
        
        return $this->model;
    }

    /**
     * Lista todos os registros da entidade
     * Renderiza a view de listagem com os dados
     */
    public function index(): void
    {
        $viewName = strtolower($this->getEntityName());
        $items = $this->getModel()->findAll();
        
        $this->render("{$viewName}/index", [
            $this->getPluralName() => $items
        ]);
    }

    /**
     * Retorna o nome plural da entidade
     * 
     * @return string Nome plural (ex: "autores", "assuntos", "livros")
     */
    protected function getPluralName(): string
    {
        $entityName = strtolower($this->getEntityName());
        
        // Mapeamento de plurais irregulares
        $plurals = [
            'autor' => 'autores',
            'assunto' => 'assuntos',
            'livro' => 'livros'
        ];
        
        return $plurals[$entityName] ?? $entityName . 's';
    }

    /**
     * Exibe o formulário para criar ou editar um registro
     * 
     * @param int|null $id ID do registro a ser editado (null para criar novo)
     * @throws \RuntimeException Se o registro não for encontrado
     */
    public function form(?int $id = null): void
    {
        $item = null;
        
        // Se foi informado um ID, busca o registro
        if ($id !== null) {
            $item = $this->getModel()->find($id);
            
            if (!$item) {
                throw new \RuntimeException("{$this->getEntityName()} não encontrado.", 404);
            }
        }
        
        // Define a ação: 'store' para criar, 'update' para editar
        $action = $id !== null ? 'update' : 'store';
        
        $this->renderForm($item, $action);
    }

    /**
     * Renderiza o formulário da entidade
     * Pode ser sobrescrito em controllers específicos para adicionar dados extras
     * 
     * @param array|null $item Dados do item a ser editado (null para criar novo)
     * @param string $action Ação do formulário ('store' ou 'update')
     */
    protected function renderForm(?array $item, string $action): void
    {
        $viewName = strtolower($this->getEntityName());
        
        $this->render("{$viewName}/form", [
            $viewName => $item,
            'action' => $action
        ]);
    }

    /**
     * Obtém o nome da entidade baseado no nome do controller
     * 
     * @return string Nome da entidade (ex: "Autor", "Livro")
     */
    protected function getEntityName(): string
    {
        return str_replace('Controller', '', basename(str_replace('\\', '/', get_class($this))));
    }

    /**
     * Cria um novo registro no banco de dados
     * 
     * @throws \RuntimeException Se houver erro na validação ou criação
     */
    public function store(): void
    {
        $data = $this->prepareData();
        $id = $this->getModel()->create($data);
        
        $this->responderSucesso($id, 'cadastrado');
    }

    /**
     * Atualiza um registro existente no banco de dados
     * 
     * @param int $id ID do registro a ser atualizado
     * @throws \RuntimeException Se o registro não for encontrado ou houver erro na atualização
     */
    public function update(int $id): void
    {
        // Verifica se o registro existe
        if (!$this->getModel()->find($id)) {
            throw new \RuntimeException("{$this->getEntityName()} não encontrado.", 404);
        }
        
        $data = $this->prepareData();
        $this->getModel()->update($id, $data);
        
        $this->responderSucesso($id, 'atualizado');
    }

    /**
     * Responde com sucesso em formato JSON
     * 
     * @param int $id ID do registro processado
     * @param string $acao Ação realizada ('cadastrado', 'atualizado', 'excluído')
     */
    protected function responderSucesso(int $id, string $acao): void
    {
        // Chama hook para processamento adicional após salvar
        $this->afterSave($id);
        
        // Retorna resposta JSON de sucesso
        $this->json([
            'success' => true,
            'message' => "{$this->getEntityName()} {$acao} com sucesso!",
            'id' => $id
        ]);
    }

    /**
     * Hook executado após salvar um registro
     * Pode ser sobrescrito em controllers específicos para processamento adicional
     * 
     * @param int $id ID do registro salvo
     */
    protected function afterSave(int $id): void
    {
        // Implementação vazia - pode ser sobrescrita em controllers filhos
    }

    /**
     * Exclui um registro do banco de dados
     * 
     * @param int $id ID do registro a ser excluído
     * @throws \RuntimeException Se o registro não for encontrado ou houver erro na exclusão
     */
    public function delete(int $id): void
    {
        // Verifica se o registro existe
        if (!$this->getModel()->find($id)) {
            throw new \RuntimeException("{$this->getEntityName()} não encontrado.", 404);
        }
        
        // Tenta excluir o registro
        if (!$this->getModel()->delete($id)) {
            throw new \RuntimeException("Erro ao excluir {$this->getEntityName()}.", 500);
        }
        
        $this->responderSucesso($id, 'excluído');
    }

    /**
     * Renderiza uma view com os dados fornecidos
     * 
     * @param string $view Nome da view (sem extensão .php)
     * @param array $data Dados a serem passados para a view
     */
    protected function render(string $view, array $data = []): void
    {
        // Extrai as variáveis do array para o escopo local
        extract($data);
        
        // Função auxiliar para gerar URLs
        $url = fn(string $route = '') => App::url($route);
        
        // Captura o output das views
        ob_start();
        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . "/../Views/{$view}.php";
        include __DIR__ . '/../Views/layout/footer.php';
        echo ob_get_clean();
    }

    /**
     * Retorna uma resposta JSON
     * 
     * @param array $data Dados a serem retornados
     * @param int $statusCode Código HTTP de status (padrão: 200)
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Redireciona para uma URL
     * 
     * @param string $url URL de destino
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}
