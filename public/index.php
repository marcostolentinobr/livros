<?php

// Carregar autoloader do Composer e inicializar aplicação
require_once __DIR__ . '/../vendor/autoload.php';
\App\Config\App::init();

// Extrair partes da rota da URL: /livro/edit/3 -> ['livro', 'edit', '3']
$parts = explode('/', $_GET['route'] ?? 'home');

// Mapear controllers automaticamente: AutorController.php -> rota 'autor'
$controllers = array_reduce(
    glob(__DIR__ . '/../src/Controllers/*Controller.php'),
    function($carry, $file) {
        $className = basename($file, '.php');
        // Excluir controllers base do mapeamento automático
        if ($className !== 'BaseController') {
            $route = strtolower(str_replace('Controller', '', $className));
            $carry[$route] = "App\\Controllers\\{$className}";
        }
        return $carry;
    },
    []
);

try {
    // Extrair rota, ação e ID da URL
    $route = $parts[0] ?: 'home';           // Primeira parte: nome da rota
    $action = $parts[1] ?? 'index';          // Segunda parte: ação (método do controller)
    $id = isset($parts[2]) ? (int)$parts[2] : null;  // Terceira parte: ID (opcional)
    
    // Resolver controller
    if (!isset($controllers[$route])) {
        throw new \RuntimeException("Rota '{$route}' não encontrada.", 404);
    }
    
    $controller = new $controllers[$route]();
    
    // Chamar método do controller
    // Mapear 'create' e 'edit' para 'form'
    if ($action === 'create' || $action === 'edit') {
        $action = 'form';
    }
    
    if ($id !== null) {
        $controller->$action($id);  // Método com parâmetro ID
    } else {
        $controller->$action();     // Método sem parâmetros
    }
} catch (\RuntimeException $e) {
    // Erros esperados: validações, registros não encontrados, rotas inválidas
    http_response_code($e->getCode() ?: 500);
    
    // Detectar se é requisição JSON (API) ou HTML (navegador)
    $isJson = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
    
    if ($isJson) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } else {
        header('Content-Type: text/html');
        echo "Erro: " . htmlspecialchars($e->getMessage());
    }
} catch (\Exception $e) {
    // Erros inesperados: erros de sistema, bugs
    error_log("Erro não tratado: " . $e->getMessage());
    http_response_code(500);
    echo "Erro interno do servidor.";
}
