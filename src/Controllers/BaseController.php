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
     * Nome da entidade (ex: "Autor", "Livro")
     * Definido automaticamente no construtor
     * 
     * @var string
     */
    protected string $entityName;

    /**
     * Nome plural da entidade
     * Define o nome plural usado nas views de listagem
     * Se não definido pelo controller filho, usa o padrão: nome da entidade + "s"
     * Sempre terá um valor após o construtor
     * 
     * Exemplo de uso em controllers filhos:
     * protected string $pluralName = 'autores';
     * 
     * @var string
     */
    protected string $pluralName;

    /**
     * Nome da view em minúsculas
     * Definido automaticamente no construtor
     * 
     * @var string
     */
    protected string $viewName;

    /**
     * Instância do model associado ao controller
     * Carregado automaticamente no construtor se o model existir
     * 
     * @var object|null
     */
    protected ?object $model = null;

    /**
     * Construtor
     * Define automaticamente o nome da entidade, view, plural e model baseado no nome do controller
     */
    public function __construct()
    {
        $this->entityName = $this->calculateEntityName();
        $this->viewName = strtolower($this->entityName);
        
        // Define pluralName se não foi definido pelo controller filho
        if (!isset($this->pluralName)) {
            $this->pluralName = $this->viewName . 's';
        }
        
        // Carrega o model automaticamente se existir
        $modelClass = "App\\Models\\{$this->entityName}";
        $this->model = class_exists($modelClass) ? new $modelClass() : null;
    }

    /**
     * Calcula o nome da entidade baseado no nome do controller
     * 
     * @return string Nome da entidade (ex: "Autor", "Livro")
     */
    private function calculateEntityName(): string
    {
        return str_replace('Controller', '', basename(str_replace('\\', '/', get_class($this))));
    }


    /**
     * Lista todos os registros da entidade
     * Renderiza a view de listagem com os dados
     */
    public function index(): void
    {
        $this->render("{$this->viewName}/index", [
            $this->pluralName => $this->model->findAll()
        ]);
    }

    /**
     * Exibe o formulário para criar ou editar um registro
     * 
     * @param int|null $id ID do registro a ser editado (null para criar novo)
     * @throws \RuntimeException Se o registro não for encontrado
     */
    public function form(?int $id = null): void
    {
        $item = $id !== null ? $this->model->find($id) : null;
        
        if ($id !== null && !$item) {
            throw new \RuntimeException("{$this->entityName} não encontrado.", 404);
        }
        
        $this->renderForm($item, $id !== null ? 'update' : 'store');
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
        $this->render("{$this->viewName}/form", [
            $this->viewName => $item,
            'action' => $action
        ]);
    }

    /**
     * Cria um novo registro no banco de dados
     * 
     * @throws \RuntimeException Se houver erro na validação ou criação
     */
    public function store(): void
    {
        // Prepara os dados recebidos do formulário
        $data = $this->prepareData();
        
        // Cria o novo registro no banco de dados
        $id = $this->model->create($data);
        
        // Responde com sucesso
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
        if (!$this->model->find($id)) {
            throw new \RuntimeException("{$this->entityName} não encontrado.", 404);
        }
        
        // Prepara e atualiza os dados
        $data = $this->prepareData();
        $this->model->update($id, $data);
        
        // Responde com sucesso
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
            'message' => "{$this->entityName} {$acao} com sucesso!",
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
        if (!$this->model->find($id)) {
            throw new \RuntimeException("{$this->entityName} não encontrado.", 404);
        }
        
        // Tenta excluir o registro
        if (!$this->model->delete($id)) {
            throw new \RuntimeException("Erro ao excluir {$this->entityName}.", 500);
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
