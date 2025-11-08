<?php
/**
 * ============================================
 * PONTO DE ENTRADA DA APLICAÇÃO
 * ============================================
 * Este arquivo é chamado por todas as requisições HTTP
 * Funciona como um roteador simples que direciona para os controllers
 */

// 1. Carregar dependências do Composer (autoloader)
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Inicializar aplicação (carrega variáveis de ambiente do .env)
\App\Config\App::init();

// 3. Extrair a rota da URL
// Exemplo: /livro/edit/3 -> ['livro', 'edit', '3']
$parts = explode('/', $_GET['route'] ?? 'home');

// 4. Mapear automaticamente os controllers
// Busca todos os arquivos *Controller.php e cria um array de rotas
// Exemplo: AutorController.php -> rota 'autor'
$controllers = array_reduce(
    glob(__DIR__ . '/../src/Controllers/*Controller.php'),
    function($carry, $file) {
        $className = basename($file, '.php');
        // Ignora BaseController (classe abstrata, não é uma rota)
        if ($className !== 'BaseController') {
            $route = strtolower(str_replace('Controller', '', $className));
            $carry[$route] = "App\\Controllers\\{$className}";
        }
        return $carry;
    },
    []
);

try {
    // 5. Extrair rota, ação e ID da URL
    $route = $parts[0] ?: 'home';           // Primeira parte: nome da rota (ex: 'livro')
    $action = $parts[1] ?? 'index';          // Segunda parte: ação/método (ex: 'edit')
    $id = isset($parts[2]) ? (int)$parts[2] : null;  // Terceira parte: ID (ex: 3)
    
    // 6. Verificar se a rota existe
    if (!isset($controllers[$route])) {
        throw new \RuntimeException("Rota '{$route}' não encontrada.", 404);
    }
    
    // 7. Instanciar o controller correspondente
    $controller = new $controllers[$route]();
    
    // 8. Mapear ações 'create' e 'edit' para o método 'form'
    if ($action === 'create' || $action === 'edit') {
        $action = 'form';
    }
    
    // 9. Chamar o método do controller
    if ($id !== null) {
        $controller->$action($id);  // Método com parâmetro ID (ex: form(3))
    } else {
        $controller->$action();     // Método sem parâmetros (ex: index())
    }
    
} catch (\RuntimeException $e) {
    // Erros esperados: validações, registros não encontrados, rotas inválidas
    http_response_code($e->getCode() ?: 500);
    
    // Detectar se é requisição AJAX/API (JSON) ou navegador (HTML)
    $isJson = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
    
    if ($isJson) {
        // Resposta JSON para requisições AJAX
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } else {
        // Resposta HTML para navegador
        header('Content-Type: text/html');
        echo "Erro: " . htmlspecialchars($e->getMessage());
    }
} catch (\Exception $e) {
    // Erros inesperados: erros de sistema, bugs
    error_log("Erro não tratado: " . $e->getMessage());
    http_response_code(500);
    echo "Erro interno do servidor.";
}
