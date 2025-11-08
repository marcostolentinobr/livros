<?php

namespace App\Controllers;

use App\Services\RelatorioService;

/**
 * Controller para gerenciar relatórios
 * Utiliza RelatorioService para buscar e processar dados
 */
class RelatorioController extends BaseController
{
    /**
     * Serviço responsável por buscar dados dos relatórios
     */
    private RelatorioService $relatorioService;

    /**
     * Inicializa o serviço de relatórios
     */
    public function __construct()
    {
        $this->relatorioService = new RelatorioService();
    }

    /**
     * Exibe a página de relatórios com dados agrupados por autor
     */
    public function index(): void
    {
        $dados = $this->relatorioService->getLivrosPorAutor();
        
        $this->render('relatorio/index', [
            'dados' => $dados
        ]);
    }

    /**
     * Exporta o relatório em formato HTML para impressão
     */
    public function exportar(): void
    {
        header('Content-Type: text/html; charset=utf-8');
        
        $dados = $this->relatorioService->getLivrosPorAutor();
        
        $this->render('relatorio/exportar', [
            'dados' => $dados
        ]);
    }
}
