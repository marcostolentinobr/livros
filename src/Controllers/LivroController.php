<?php

namespace App\Controllers;

/**
 * Controller para gerenciar Livros
 * Possui lógica específica para relacionamentos muitos-para-muitos com Autores e Assuntos
 */
class LivroController extends BaseController
{
    /**
     * Lista todos os livros com suas relações (autores e assuntos)
     * Sobrescreve o método padrão para incluir dados relacionados
     */
    public function index(): void
    {
        $livros = $this->getModel()->findAllWithRelations();
        
        $this->render('livro/index', [
            'livros' => $livros
        ]);
    }

    /**
     * Renderiza o formulário de livro com dados adicionais
     * Inclui listas de autores e assuntos para seleção
     * 
     * @param array|null $livro Dados do livro a ser editado (null para criar novo)
     * @param string $action Ação do formulário ('store' ou 'update')
     */
    protected function renderForm(?array $livro, string $action): void
    {
        // Busca todos os autores e assuntos disponíveis
        $autores = (new \App\Models\Autor())->findAll();
        $assuntos = (new \App\Models\Assunto())->findAll();
        
        $data = [
            'livro' => $livro,
            'autores' => $autores,
            'assuntos' => $assuntos,
            'action' => $action
        ];

        // Se estiver editando, busca os autores e assuntos já associados ao livro
        if ($livro !== null) {
            $livroId = $livro['Codl'];
            
            // Extrai apenas os IDs dos autores associados
            $autoresAssociados = $this->getModel()->getAutores($livroId);
            $data['livroAutores'] = array_column($autoresAssociados, 'CodAu');
            
            // Extrai apenas os IDs dos assuntos associados
            $assuntosAssociados = $this->getModel()->getAssuntos($livroId);
            $data['livroAssuntos'] = array_column($assuntosAssociados, 'codAs');
        }

        $this->render('livro/form', $data);
    }

    /**
     * Prepara e valida os dados recebidos do formulário de livro
     * 
     * @return array Dados validados e formatados para o banco de dados
     * @throws \RuntimeException Se algum campo obrigatório estiver vazio
     */
    protected function prepareData(): array
    {
        // Valida e extrai o título
        $titulo = trim($_POST['titulo'] ?? '');
        if (empty($titulo)) {
            throw new \RuntimeException("O título é obrigatório.", 400);
        }

        // Valida e extrai a editora
        $editora = trim($_POST['editora'] ?? '');
        if (empty($editora)) {
            throw new \RuntimeException("A editora é obrigatória.", 400);
        }

        // Valida e extrai o ano de publicação
        $anoPublicacao = trim($_POST['ano_publicacao'] ?? '');
        if (empty($anoPublicacao)) {
            throw new \RuntimeException("O ano de publicação é obrigatório.", 400);
        }

        // Prepara os dados para o banco de dados
        return [
            'Titulo' => $titulo,
            'Editora' => $editora,
            'Edicao' => (int)($_POST['edicao'] ?? 1),
            'AnoPublicacao' => $anoPublicacao,
            'Valor' => $this->formatCurrencyToDb($_POST['valor'] ?? '0')
        ];
    }

    /**
     * Hook executado após salvar um livro
     * Associa os autores e assuntos selecionados ao livro
     * 
     * @param int $id ID do livro salvo
     */
    protected function afterSave(int $id): void
    {
        // Associa os autores selecionados ao livro
        $autores = $_POST['autores'] ?? [];
        $this->getModel()->setAutores($id, $autores);
        
        // Associa os assuntos selecionados ao livro
        $assuntos = $_POST['assuntos'] ?? [];
        $this->getModel()->setAssuntos($id, $assuntos);
    }

    /**
     * Converte valor monetário formatado (R$ 1.234,56) para float
     * 
     * @param string $value Valor formatado em reais
     * @return float Valor numérico para armazenar no banco
     */
    private function formatCurrencyToDb(string $value): float
    {
        // Remove símbolos e espaços: "R$ 1.234,56" -> "1234,56"
        $value = str_replace(['R$', ' ', '.'], '', trim($value));
        
        // Substitui vírgula por ponto: "1234,56" -> "1234.56"
        $value = str_replace(',', '.', $value);
        
        return (float) $value;
    }
}
