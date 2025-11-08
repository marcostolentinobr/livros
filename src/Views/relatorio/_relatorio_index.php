<!-- View personalizada de relatório com funcionalidades extras -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-person"></i> Total de Autores</h5>
                <h3 class="mb-0"><?= count(array_unique(array_column($dados, 'CodAutor'))) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-book"></i> Total de Livros</h5>
                <h3 class="mb-0"><?= count(array_unique(array_column($dados, 'CodLivro'))) ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> Relatório de Livros por Autor</h4>
                <div>
                    <a href="<?= $url('relatorio/exportar') ?>" class="btn btn-light" target="_blank">
                        <i class="bi bi-file-pdf"></i> Gerar PDF
                    </a>
                    <button class="btn btn-light" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div>
            </div>
            <div class="card-body">
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
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle"></i> Nenhum dado encontrado.
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $autorAtual = '';
                                foreach ($dados as $row): 
                                    if ($row['NomeAutor'] !== $autorAtual):
                                        $autorAtual = $row['NomeAutor'];
                                ?>
                                    <tr class="table-primary">
                                        <td colspan="8" class="fw-bold fs-5">
                                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($autorAtual) ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                    <tr>
                                        <td></td>
                                        <td><strong><?= htmlspecialchars($row['Titulo']) ?></strong></td>
                                        <td><?= htmlspecialchars($row['Editora']) ?></td>
                                        <td>
                                            <span class="badge bg-secondary"><?= htmlspecialchars($row['Edicao']) ?>ª</span>
                                        </td>
                                        <td><?= htmlspecialchars($row['AnoPublicacao']) ?></td>
                                        <td>
                                            <span class="badge bg-success">R$ <?= number_format($row['Valor'], 2, ',', '.') ?></span>
                                        </td>
                                        <td>
                                            <?php if (!empty($row['Assuntos'])): ?>
                                                <span class="badge bg-info"><?= htmlspecialchars($row['Assuntos']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($row['OutrosAutores'])): ?>
                                                <span class="badge bg-warning text-dark"><?= htmlspecialchars($row['OutrosAutores']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
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

