<?php

namespace App\Services;

use TCPDF;
use App\Database\Connection;
use PDO;

/**
 * Service para geração de relatórios em PDF
 * Utiliza TCPDF como componente de relatórios
 * A consulta é proveniente da view vw_livros_por_autor do banco de dados
 * Agrupa os dados por autor, mostrando informações das 3 tabelas principais:
 * - Autor (Nome)
 * - Livro (Título, Editora, Edição, Ano, Valor)
 * - Assunto (Descrição)
 */
class RelatorioPDF
{
    private PDO $db;
    private TCPDF $pdf;

    public function __construct()
    {
        $this->db = Connection::getInstance();
        // Cria instância do TCPDF: P=Portrait, mm=unidade, A4=formato, UTF-8=encoding
        $this->pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $this->configurarPDF();
    }

    /**
     * Configura as propriedades básicas do PDF
     * Define metadados, margens, quebra de página e fonte padrão
     */
    private function configurarPDF(): void
    {
        // Metadados do documento
        $this->pdf->SetCreator('Sistema de Cadastro de Livros');
        $this->pdf->SetAuthor('Sistema de Livros');
        $this->pdf->SetTitle('Relatório de Livros por Autor');
        $this->pdf->SetSubject('Relatório de Livros Agrupados por Autor');
        
        // Configurações de margem (esquerda, topo, direita)
        $this->pdf->SetMargins(15, 20, 15);
        $this->pdf->SetHeaderMargin(5);
        $this->pdf->SetFooterMargin(10);
        
        // Quebra automática de página quando chegar a 15mm do final
        $this->pdf->SetAutoPageBreak(TRUE, 15);
        
        // Fonte padrão do documento
        $this->pdf->SetFont('helvetica', '', 10);
        
        // Adiciona a primeira página
        $this->pdf->AddPage();
    }

    /**
     * Gera o relatório completo em PDF
     * Busca dados da view, agrupa por autor e formata no PDF
     */
    public function gerarRelatorio(): void
    {
        // Busca dados da view do banco de dados
        $sql = "SELECT * FROM vw_livros_por_autor ORDER BY NomeAutor, Titulo";
        $stmt = $this->db->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Adiciona cabeçalho do relatório (título, data, cabeçalho da tabela)
        $this->adicionarCabecalho();

        // Variáveis para controlar o agrupamento por autor
        $autorAtual = '';
        $primeiroAutor = true;

        // Itera sobre os dados, agrupando por autor
        foreach ($dados as $row) {
            // Quando muda o autor, adiciona cabeçalho do grupo
            if ($row['NomeAutor'] !== $autorAtual) {
                $autorAtual = $row['NomeAutor'];
                
                // Adiciona espaço antes de cada novo autor (exceto o primeiro)
                if (!$primeiroAutor) {
                    $this->pdf->Ln(5);
                }
                $primeiroAutor = false;

                // Verifica se precisa de nova página antes de adicionar o cabeçalho do autor
                if ($this->pdf->GetY() > 250) {
                    $this->pdf->AddPage();
                }

                // Cabeçalho do grupo de autor (fundo cinza claro, texto em negrito)
                $this->pdf->SetFont('helvetica', 'B', 12);
                $this->pdf->SetFillColor(230, 230, 230);
                $this->pdf->Cell(0, 8, 'Autor: ' . $row['NomeAutor'], 0, 1, 'L', true);
                $this->pdf->SetFont('helvetica', '', 10);
                $this->pdf->Ln(2);
            }

            // Adiciona os dados do livro na tabela
            $this->adicionarDadosLivro($row);
        }

        // Adiciona rodapé com numeração de páginas
        $this->adicionarRodape();
        
        // Gera e envia o PDF para o navegador (I = Inline, exibe no navegador)
        $this->pdf->Output('relatorio_livros_por_autor.pdf', 'I');
    }

    /**
     * Adiciona o cabeçalho do relatório
     * Inclui título, informações do sistema, data de geração e cabeçalho da tabela
     */
    private function adicionarCabecalho(): void
    {
        // Título principal do relatório
        $this->pdf->SetFont('helvetica', 'B', 16);
        $this->pdf->Cell(0, 10, 'RELATÓRIO DE LIVROS POR AUTOR', 0, 1, 'C');
        
        // Informações do sistema e data
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(0, 5, 'Sistema de Cadastro de Livros', 0, 1, 'C');
        $this->pdf->Cell(0, 5, 'Gerado em: ' . date('d/m/Y H:i:s'), 0, 1, 'C');
        $this->pdf->Ln(10);

        // Cabeçalho da tabela (fundo azul, texto branco)
        $this->pdf->SetFont('helvetica', 'B', 9);
        $this->pdf->SetFillColor(70, 130, 180); // Azul
        $this->pdf->SetTextColor(255, 255, 255); // Branco
        
        // Colunas da tabela
        $this->pdf->Cell(80, 7, 'Título', 1, 0, 'L', true);
        $this->pdf->Cell(40, 7, 'Editora', 1, 0, 'L', true);
        $this->pdf->Cell(20, 7, 'Edição', 1, 0, 'C', true);
        $this->pdf->Cell(20, 7, 'Ano', 1, 0, 'C', true);
        $this->pdf->Cell(30, 7, 'Valor (R$)', 1, 0, 'R', true);
        $this->pdf->Ln();
        
        // Restaura cor e fonte padrão para os dados
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('helvetica', '', 9);
    }

    /**
     * Adiciona os dados de um livro no relatório
     * Inclui informações principais e, se houver, assuntos e coautores
     * 
     * @param array $row Dados do livro vindos da view
     */
    private function adicionarDadosLivro(array $row): void
    {
        // Verifica se precisa de nova página (quando está muito próximo do final)
        if ($this->pdf->GetY() > 270) {
            $this->pdf->AddPage();
            $this->adicionarCabecalhoTabela();
        }

        // Linha principal com dados do livro
        $this->pdf->SetFont('helvetica', '', 9);
        $this->pdf->Cell(80, 6, $this->truncarTexto($row['Titulo'], 40), 1, 0, 'L');
        $this->pdf->Cell(40, 6, $this->truncarTexto($row['Editora'], 20), 1, 0, 'L');
        $this->pdf->Cell(20, 6, $row['Edicao'] . 'ª', 1, 0, 'C');
        $this->pdf->Cell(20, 6, $row['AnoPublicacao'], 1, 0, 'C');
        $this->pdf->Cell(30, 6, number_format($row['Valor'], 2, ',', '.'), 1, 0, 'R');
        $this->pdf->Ln();

        // Verifica se há assuntos ou coautores para exibir
        $temAssuntos = !empty($row['Assuntos']);
        $temOutrosAutores = !empty($row['OutrosAutores']);

        // Adiciona linha adicional com assuntos e coautores (se existirem)
        if ($temAssuntos || $temOutrosAutores) {
            $this->pdf->SetFont('helvetica', 'I', 8); // Itálico, menor
            $this->pdf->SetTextColor(100, 100, 100); // Cinza
            
            // Monta a linha de informações adicionais
            $linhaInfo = '';
            if ($temAssuntos) {
                $linhaInfo .= 'Assuntos: ' . $row['Assuntos'];
            }
            if ($temOutrosAutores) {
                if ($temAssuntos) {
                    $linhaInfo .= ' | '; // Separador se houver assuntos
                }
                $linhaInfo .= 'Coautores: ' . $row['OutrosAutores'];
            }
            
            $this->pdf->Cell(190, 5, $this->truncarTexto($linhaInfo, 90), 0, 0, 'L');
            $this->pdf->Ln(3);
            
            // Restaura cor padrão
            $this->pdf->SetTextColor(0, 0, 0);
        }

        // Espaço entre livros
        $this->pdf->Ln(2);
    }

    /**
     * Adiciona cabeçalho da tabela em nova página
     * Usado quando o relatório precisa quebrar para uma nova página
     */
    private function adicionarCabecalhoTabela(): void
    {
        $this->pdf->SetFont('helvetica', 'B', 9);
        $this->pdf->SetFillColor(70, 130, 180); // Azul
        $this->pdf->SetTextColor(255, 255, 255); // Branco
        
        // Colunas da tabela (mesmas do cabeçalho principal)
        $this->pdf->Cell(80, 7, 'Título', 1, 0, 'L', true);
        $this->pdf->Cell(40, 7, 'Editora', 1, 0, 'L', true);
        $this->pdf->Cell(20, 7, 'Edição', 1, 0, 'C', true);
        $this->pdf->Cell(20, 7, 'Ano', 1, 0, 'C', true);
        $this->pdf->Cell(30, 7, 'Valor (R$)', 1, 0, 'R', true);
        $this->pdf->Ln();
        
        // Restaura cor e fonte padrão
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('helvetica', '', 9);
    }

    /**
     * Adiciona o rodapé do relatório com numeração de páginas
     * Posiciona a 20mm do final da página
     */
    private function adicionarRodape(): void
    {
        $this->pdf->SetY(-20); // 20mm do final da página
        $this->pdf->SetFont('helvetica', 'I', 8); // Itálico, pequeno
        $this->pdf->Cell(0, 10, 'Página ' . $this->pdf->getAliasNumPage() . ' de ' . $this->pdf->getAliasNbPages(), 0, 0, 'C');
    }

    /**
     * Trunca texto para caber na célula do PDF
     * Se o texto for maior que o tamanho permitido, corta e adiciona "..."
     * 
     * @param string $texto Texto a ser truncado
     * @param int $tamanho Tamanho máximo permitido
     * @return string Texto truncado ou original
     */
    private function truncarTexto(string $texto, int $tamanho): string
    {
        if (mb_strlen($texto) > $tamanho) {
            return mb_substr($texto, 0, $tamanho - 3) . '...';
        }
        return $texto;
    }
}

