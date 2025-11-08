<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistema de Cadastro de Livros' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #2c3e50 !important; }
        .navbar-brand, .nav-link { color: #ecf0f1 !important; }
        .nav-link:hover { color: #3498db !important; }
        .card { box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .btn-primary { background-color: #3498db; border-color: #3498db; }
        .btn-primary:hover { background-color: #2980b9; border-color: #2980b9; }
        #loadingOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        #loadingOverlay.show { display: flex; }
        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= $url(\App\Config\App::$defaultModule) ?>">
                <i class="bi bi-book"></i> Sistema de Livros
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php
                    $controllers = array_reduce(
                        glob(__DIR__ . '/../../Controllers/*Controller.php'),
                        function($carry, $file) {
                            $className = basename($file, '.php');
                            if ($className !== 'BaseController') {
                                $route = strtolower(str_replace('Controller', '', $className));
                                $carry[$route] = "App\\Controllers\\{$className}";
                            }
                            return $carry;
                        },
                        []
                    );
                    
                    $defaultModule = \App\Config\App::$defaultModule;
                    uksort($controllers, fn($a, $b) => $a === $defaultModule ? -1 : ($b === $defaultModule ? 1 : strcmp($a, $b)));
                    
                    foreach ($controllers as $route => $controllerClass):
                        try {
                            $controller = new $controllerClass();
                            $reflection = new \ReflectionClass($controller);
                            
                            $iconProperty = $reflection->getProperty('icon');
                            $iconProperty->setAccessible(true);
                            $icon = $iconProperty->getValue($controller);
                            
                            $pluralProperty = $reflection->getProperty('pluralName');
                            $pluralProperty->setAccessible(true);
                            $pluralName = $pluralProperty->getValue($controller);
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $url($route) ?>">
                                <i class="bi <?= $icon ?>"></i> <?= ucfirst($pluralName) ?>
                            </a>
                        </li>
                    <?php
                        } catch (\Exception $e) {}
                    endforeach;
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <div id="flashMessages" class="container mt-3"></div>
    <div class="container mt-4">
