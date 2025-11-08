<?php

require_once __DIR__ . '/../vendor/autoload.php';

\App\Config\App::init();

// Extrai partes da rota (ex: /livro/form/1 -> ['livro', 'form', '1'])
$parts = explode('/', $_GET['route'] ?? 'home');

// Mapeia automaticamente controllers para rotas
$controllers = array_reduce(
    glob(__DIR__ . '/../src/Controllers/*Controller.php'),
    function($carry, $file) {
        $className = basename($file, '.php');
        // Ignora BaseController (classe abstrata)
        if ($className !== 'BaseController') {
            // Converte LivroController -> livro
            $route = strtolower(str_replace('Controller', '', $className));
            $carry[$route] = "App\\Controllers\\{$className}";
        }
        return $carry;
    },
    []
);

try {
    // Define rota, ação e ID a partir da URL
    $route = $parts[0] ?: 'home';  // Primeira parte ou 'home' por padrão
    $action = $parts[1] ?? 'index';  // Segunda parte ou 'index' por padrão
    $id = isset($parts[2]) ? (int)$parts[2] : null;  // Terceira parte como ID
    
    // Valida se rota existe
    if (!isset($controllers[$route])) {
        throw new \RuntimeException("Rota '{$route}' não encontrada.", 404);
    }
    
    $controller = new $controllers[$route]();
    
    // Normaliza ações create/edit para form
    if ($action === 'create' || $action === 'edit') {
        $action = 'form';
    }
    
    // Chama método com ou sem parâmetro ID
    if ($id !== null) {
        $controller->$action($id);
    } else {
        $controller->$action();
    }
    
} catch (\RuntimeException $e) {
    http_response_code($e->getCode() ?: 500);
    
    // Detecta se é requisição AJAX (JSON) ou navegador (HTML)
    $isJson = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
    
    // Responde em formato adequado ao tipo de requisição
    if ($isJson) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } else {
        header('Content-Type: text/html');
        echo "Erro: " . htmlspecialchars($e->getMessage());
    }
} catch (\Exception $e) {
    error_log("Erro não tratado: " . $e->getMessage());
    http_response_code(500);
    echo "Erro interno do servidor.";
}
