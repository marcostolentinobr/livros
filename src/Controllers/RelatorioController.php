<?php

namespace App\Controllers;

use App\Services\RelatorioService;
use App\Services\RelatorioPDF;

/** Controller para gerenciar relatórios */
class RelatorioController extends BaseController
{
    private RelatorioService $relatorioService;

    public function __construct()
    {
        parent::__construct();
        $this->relatorioService = new RelatorioService();
    }

    /** Exibe página de relatórios agrupados por autor */
    public function index(): void
    {
        $dados = $this->relatorioService->getLivrosPorAutor();
        $this->render('relatorio/relatorio_index', ['dados' => $dados]);
    }

    /** Gera e exibe relatório em PDF */
    public function exportar(): void
    {
        // Limpa buffers de output (necessário para PDF)
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        // Suprime warnings de constantes já definidas (TCPDF)
        $oldErrorReporting = error_reporting(E_ALL & ~E_WARNING);
        
        try {
            $relatorioPDF = new RelatorioPDF();
            $relatorioPDF->gerarRelatorio();
        } finally {
            // Restaura nível de erro original
            error_reporting($oldErrorReporting);
        }
    }
}
