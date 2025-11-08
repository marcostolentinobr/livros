<!-- Página de relatório de livros agrupados por autor -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- Cabeçalho com título e botão de exportação -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> Relatório de Livros por Autor</h4>
                <a href="<?= $url('relatorio/exportar') ?>" target="_blank" class="btn btn-success">
                    <i class="bi bi-download"></i> Exportar
                </a>
            </div>
            <div class="card-body">
                <!-- Tabela responsiva com dados agrupados por autor -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Autor</th>
                                <th>Título</th>
                                <th>Editora</th>
                                <th>Edição</th>
                                <th>Ano</th>
                                <th>Valor</th>
                                <th>Assuntos</th>
                                <th>Outros Autores</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dados)): ?>
                                <!-- Mensagem quando não há dados -->
                                <tr><td colspan="8" class="text-center">Nenhum dado encontrado.</td></tr>
                            <?php else: ?>
                                <?php 
                                // Agrupa os livros por autor, mostrando o nome do autor como cabeçalho
                                $autorAtual = '';
                                foreach ($dados as $row): 
                                    // Quando muda o autor, exibe uma linha de cabeçalho
                                    if ($row['NomeAutor'] !== $autorAtual):
                                        $autorAtual = $row['NomeAutor'];
                                ?>
                                    <!-- Linha de cabeçalho do autor (destaque em azul claro) -->
                                    <tr class="table-info">
                                        <td colspan="8" class="fw-bold">
                                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($autorAtual) ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                    <!-- Linha com dados do livro -->
                                    <tr>
                                        <td></td>
                                        <td><?= htmlspecialchars($row['Titulo']) ?></td>
                                        <td><?= htmlspecialchars($row['Editora']) ?></td>
                                        <td><?= htmlspecialchars($row['Edicao']) ?>ª</td>
                                        <td><?= htmlspecialchars($row['AnoPublicacao']) ?></td>
                                        <!-- Formatação do valor em reais -->
                                        <td>R$ <?= number_format($row['Valor'], 2, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($row['Assuntos'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['OutrosAutores'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
