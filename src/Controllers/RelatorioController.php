<?php

namespace App\Controllers;

use App\Services\RelatorioService;
use App\Services\RelatorioPDF;

/**
 * Controller para gerenciar relatórios
 * Exibe dados agrupados por autor e gera relatórios em PDF
 */
class RelatorioController extends BaseController
{
    private RelatorioService $relatorioService;

    public function __construct()
    {
        parent::__construct();
        $this->relatorioService = new RelatorioService();
    }

    /**
     * Exibe a página de relatórios com dados agrupados por autor
     * Busca dados da view vw_livros_por_autor e renderiza na tela
     */
    public function index(): void
    {
        $dados = $this->relatorioService->getLivrosPorAutor();
        $this->render('relatorio/index', ['dados' => $dados]);
    }

    /**
     * Gera e exibe o relatório em PDF usando TCPDF
     * 
     * Limpa qualquer output anterior para evitar erros ao gerar o PDF
     * Suprime warnings de constantes já definidas (comum com TCPDF)
     * A consulta é proveniente da view vw_livros_por_autor do banco de dados
     */
    public function exportar(): void
    {
        // Limpa todos os buffers de output para evitar erros ao enviar o PDF
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        // Suprime warnings de constantes já definidas (TCPDF define suas próprias constantes)
        $oldErrorReporting = error_reporting(E_ALL & ~E_WARNING);
        
        try {
            $relatorioPDF = new RelatorioPDF();
            $relatorioPDF->gerarRelatorio();
            // O método gerarRelatorio() já faz exit através do Output() do TCPDF
        } finally {
            // Restaura o nível de reporte de erros original
            error_reporting($oldErrorReporting);
        }
    }
}
