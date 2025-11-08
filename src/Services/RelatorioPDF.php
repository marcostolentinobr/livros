<?php

namespace App\Services;

use TCPDF;
use App\Database\Connection;
use PDO;

/** Service para geração de relatórios em PDF */
class RelatorioPDF
{
    private TCPDF $pdf;

    public function __construct()
    {
        // P=Portrait, mm=unidade, A4=formato, UTF-8=encoding
        $this->pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $this->pdf->SetMargins(15, 20, 15);
        $this->pdf->SetAutoPageBreak(TRUE, 15);
        $this->pdf->AddPage();
    }

    /** Gera o relatório completo em PDF */
    public function gerarRelatorio(): void
    {
        $db = Connection::getInstance();
        $dados = $db->query("SELECT * FROM vw_livros_por_autor ORDER BY NomeAutor, Titulo")->fetchAll(PDO::FETCH_ASSOC);

        // Título do sistema
        $this->pdf->SetFont('helvetica', 'B', 16);
        $this->pdf->Cell(0, 10, 'Sistema de Cadastro de Livros', 0, 1, 'C');
        $this->pdf->Ln(5);

        $this->adicionarCabecalho();

        $autorAtual = '';
        // Processa cada registro agrupando por autor
        foreach ($dados as $row) {
            // Quando muda o autor, adiciona cabeçalho do grupo
            if ($row['NomeAutor'] !== $autorAtual) {
                // Adiciona espaço antes de cada novo autor (exceto o primeiro)
                if ($autorAtual !== '') {
                    $this->pdf->Ln(5);
                }
                $autorAtual = $row['NomeAutor'];
                $this->pdf->SetFont('helvetica', 'B', 12);
                $this->pdf->Cell(0, 8, 'Autor: ' . $row['NomeAutor'], 0, 1, 'L');
                $this->pdf->SetFont('helvetica', '', 9);
                $this->pdf->Ln(2);
            }

            $this->adicionarDadosLivro($row);
        }

        $this->adicionarRodape();
        // I = Inline (exibe no navegador), D = Download, F = Salva arquivo
        $this->pdf->Output('relatorio_livros_por_autor.pdf', 'I');
    }

    /** Adiciona o cabeçalho da tabela */
    private function adicionarCabecalho(): void
    {
        $this->pdf->SetFont('helvetica', 'B', 9);
        $this->pdf->Cell(80, 7, 'Título', 1, 0, 'L');
        $this->pdf->Cell(40, 7, 'Editora', 1, 0, 'L');
        $this->pdf->Cell(20, 7, 'Edição', 1, 0, 'C');
        $this->pdf->Cell(20, 7, 'Ano', 1, 0, 'C');
        $this->pdf->Cell(30, 7, 'Valor (R$)', 1, 0, 'R');
        $this->pdf->Ln();
        $this->pdf->SetFont('helvetica', '', 9);
    }

    /** Adiciona os dados de um livro no relatório */
    private function adicionarDadosLivro(array $row): void
    {
        $this->pdf->Cell(80, 6, $row['Titulo'], 1, 0, 'L');
        $this->pdf->Cell(40, 6, $row['Editora'], 1, 0, 'L');
        $this->pdf->Cell(20, 6, $row['Edicao'] . 'ª', 1, 0, 'C');
        $this->pdf->Cell(20, 6, $row['AnoPublicacao'], 1, 0, 'C');
        $this->pdf->Cell(30, 6, number_format($row['Valor'], 2, ',', '.'), 1, 0, 'R');
        $this->pdf->Ln();
    }

    /** Adiciona o rodapé do relatório com numeração de páginas */
    private function adicionarRodape(): void
    {
        $this->pdf->SetY(-20);  // 20mm do final da página
        $this->pdf->SetFont('helvetica', 'I', 8);
        $dataGeracao = 'Gerado em: ' . date('d/m/Y H:i:s');
        $this->pdf->Cell(0, 5, $dataGeracao, 0, 0, 'L');
        $this->pdf->Cell(0, 5, 'Página ' . $this->pdf->getAliasNumPage() . ' de ' . $this->pdf->getAliasNbPages(), 0, 0, 'R');
    }
}

