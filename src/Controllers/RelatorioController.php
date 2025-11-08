<?php

namespace App\Controllers;

use App\Services\RelatorioService;
use App\Services\RelatorioPDF;

/** Controller para gerenciar relatórios */
class RelatorioController extends BaseController
{
    /** Exibe página de relatórios agrupados por autor */
    public function index(): void
    {
        $dados = (new RelatorioService())->getLivrosPorAutor();
        $this->render('relatorio/relatorio_index', ['dados' => $dados]);
    }

    /** Gera e exibe relatório em PDF */
    public function exportar(): void
    {
        $relatorioPDF = new RelatorioPDF();
        $relatorioPDF->gerarRelatorio();
    }
}
