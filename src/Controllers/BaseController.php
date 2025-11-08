<?php

namespace App\Controllers;

use App\Config\App;

/** Controller base com operações CRUD padrão */
abstract class BaseController
{
    protected string $entityName;
    protected string $pluralName;
    protected string $viewName;
    protected ?object $model = null;
    protected string $icon = 'bi-file-earmark';
    protected bool $showPrimaryKey = false;

    public function __construct()
    {
        // Extrai nome da entidade removendo "Controller" do nome da classe
        $this->entityName = str_replace('Controller', '', basename(str_replace('\\', '/', get_class($this))));
        $this->viewName = strtolower($this->entityName);
        // Usa pluralName definido ou gera automaticamente
        $this->pluralName = $this->pluralName ?? $this->viewName . 's';
        
        // Carrega model automaticamente se existir
        $modelClass = "App\\Models\\{$this->entityName}";
        $this->model = class_exists($modelClass) ? new $modelClass() : null;
    }

    /** Lista todos os registros */
    public function index(): void
    {
        $viewPath = "{$this->viewName}/{$this->viewName}_index";
        $specificView = __DIR__ . "/../Views/{$viewPath}.php";
        
        // Usa view genérica se não existir view específica
        if (!file_exists($specificView)) {
            $viewPath = 'layout/index';
        }
        
        $data = [];
        $items = [];
        
        // Se houver model, adiciona dados ao array
        if ($this->model !== null) {
            $items = $this->model->findAll();
            $data[$this->pluralName] = $items;
        }
        
        $primaryKey = $this->model ? $this->model->getPrimaryKey() : 'id';
        
        $data['items'] = $items;
        $data['fields'] = $this->getFields();
        $data['primaryKey'] = $primaryKey;
        $data['showPrimaryKey'] = $this->showPrimaryKey;
        $data['entityName'] = $this->entityName;
        $data['viewName'] = $this->viewName;
        $data['pluralName'] = $this->pluralName;
        $data['icon'] = $this->icon;
        
        $this->render($viewPath, $data);
    }

    /** Exibe formulário de criação/edição */
    public function form(?int $id = null): void
    {
        $item = $id !== null ? $this->model->find($id) : null;
        // Valida se registro existe quando é edição
        if ($id !== null && !$item) {
            throw new \RuntimeException("{$this->entityName} não encontrado.", 404);
        }
        $this->renderForm($item, $id !== null ? 'update' : 'store');
    }

    /** Renderiza formulário */
    protected function renderForm(?array $item, string $action): void
    {
        $viewPath = "{$this->viewName}/{$this->viewName}_form";
        $specificView = __DIR__ . "/../Views/{$viewPath}.php";
        
        // Usa view genérica se não existir view específica
        if (!file_exists($specificView)) {
            $viewPath = 'layout/form';
        }
        
        $primaryKey = $this->model ? $this->model->getPrimaryKey() : 'id';
        
        $data = [
            'item' => $item,
            'action' => $action,
            'fields' => $this->getFields(),
            'primaryKey' => $primaryKey,
            'entityName' => $this->entityName,
            'viewName' => $this->viewName,
            'icon' => $this->icon
        ];
        
        // Carrega valores selecionados para campos de relacionamento se for edição
        if ($item !== null && $this->model !== null) {
            $fields = $this->getFields();
            foreach ($fields as $field) {
                $campo = $field[0];
                $valorPadrao = $field[4] ?? null;
                $multiple = $field[5] ?? true;
                // Se o valor padrão é um array (relacionamento), carrega IDs selecionados
                if (is_array($valorPadrao) && !empty($valorPadrao)) {
                    $itemId = $item[$primaryKey];
                    // Tenta métodos específicos primeiro (getAutores, getAssuntos)
                    $methodName = 'get' . ucfirst($campo);
                    if (method_exists($this->model, $methodName)) {
                        $selected = $this->model->$methodName($itemId);
                        // Se não for múltiplo, pega apenas o primeiro valor
                        if (!$multiple && !empty($selected)) {
                            $selected = [reset($selected)];
                        }
                        $data['item' . ucfirst($campo)] = $selected;
                    }
                }
            }
        }
        
        // Mantém compatibilidade com views específicas
        $data[$this->viewName] = $item;
        
        $this->render($viewPath, $data);
    }

    /** Cria novo registro */
    public function store(): void
    {
        $id = $this->model->create($this->prepareData());
        $this->responderSucesso($id, 'cadastrado');
    }

    /** Atualiza registro existente */
    public function update(int $id): void
    {
        // Verifica se registro existe antes de atualizar
        if (!$this->model->find($id)) {
            throw new \RuntimeException("{$this->entityName} não encontrado.", 404);
        }
        $this->model->update($id, $this->prepareData());
        $this->responderSucesso($id, 'atualizado');
    }

    /** Responde com sucesso em JSON */
    protected function responderSucesso(int $id, string $acao): void
    {
        $this->afterSave($id);
        $this->json([
            'success' => true,
            'message' => "{$this->entityName} {$acao} com sucesso!",
            'id' => $id
        ]);
    }

    /** Hook executado após salvar */
    protected function afterSave(int $id): void
    {
    }

    /** Exclui registro */
    public function delete(int $id): void
    {
        // Verifica se exclusão foi bem-sucedida
        if (!$this->model->delete($id)) {
            throw new \RuntimeException("{$this->entityName} não encontrado.", 404);
        }
        $this->responderSucesso($id, 'excluído');
    }

    /** Renderiza view */
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $url = fn(string $route = '') => App::$baseUrl . '/' . trim($route, '/');
        ob_start();
        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . "/../Views/{$view}.php";
        include __DIR__ . '/../Views/layout/footer.php';
        echo ob_get_clean();
    }

    /** Retorna resposta JSON */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /** Redireciona para URL */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /** Valida campos do formulário e retorna array para banco
     * @param array $fields Array de [campo, nome_amigavel, obrigatorio, maxlength, valor_padrao]
     * @return array Dados validados prontos para inserção
     */
    protected function validateFields(array $fields): array
    {
        $data = [];
        $errors = [];
        $primaryKey = $this->model ? $this->model->getPrimaryKey() : null;
        
        foreach ($fields as $field) {
            $campo = $field[0];
            $nomeAmigavel = $field[1];
            $obrigatorio = $field[2] ?? false;
            $maxLength = $field[3] ?? null;
            $valorPadrao = $field[4] ?? null;
            
            // Ignora campos de relacionamento (arrays)
            if (is_array($valorPadrao)) continue;
            
            // Converte campo para formato do banco (PascalCase)
            $dbKey = str_replace('_', '', ucwords($campo, '_'));
            
            // Ignora campos que são chave primária
            if ($primaryKey && $dbKey === $primaryKey) continue;
            
            $value = trim($_POST[$campo] ?? '');
            
            // Valida campo obrigatório
            if ($obrigatorio && empty($value)) {
                $errors[] = $nomeAmigavel;
            }
            
            // Valida e trunca tamanho máximo
            if ($maxLength !== null && mb_strlen($value) > $maxLength) {
                $errors[] = "{$nomeAmigavel} excede o tamanho máximo de {$maxLength} caracteres.";
                $value = mb_substr($value, 0, $maxLength);
            }
            
            $data[$dbKey] = $value;
        }
        
        // Lança exceção com todos os campos obrigatórios faltantes
        if (!empty($errors)) {
            $message = count($errors) === 1 
                ? "O campo '{$errors[0]}' é obrigatório." 
                : "Os seguintes campos são obrigatórios: " . implode(', ', $errors) . ".";
            throw new \RuntimeException($message, 400);
        }
        
        return $data;
    }

    /** Retorna definição dos campos do formulário
     * Formato: [campo_post, nome_amigavel, obrigatorio, maxlength, valor_padrao, multiple]
     */
    protected function getFields(): array
    {
        return [];
    }


    /** Prepara e valida dados do formulário */
    protected function prepareData(): array
    {
        $fields = $this->getFields();
        return empty($fields) ? [] : $this->validateFields($fields);
    }
}
