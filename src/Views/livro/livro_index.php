<!-- Página de listagem de livros -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- Cabeçalho com título e botão de novo livro -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-book"></i> Gerenciar Livros</h4>
                <a href="<?= $url('livro/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Novo Livro
                </a>
            </div>
            <div class="card-body">
                <!-- Tabela responsiva com lista de livros e seus relacionamentos -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Código</th>
                                <th>Título</th>
                                <th>Editora</th>
                                <th>Edição</th>
                                <th>Ano</th>
                                <th>Valor</th>
                                <th>Autores</th>
                                <th>Assuntos</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($livros)): ?>
                                <!-- Mensagem quando não há livros cadastrados -->
                                <tr><td colspan="9" class="text-center">Nenhum livro cadastrado.</td></tr>
                            <?php else: ?>
                                <!-- Loop para exibir cada livro -->
                                <?php foreach ($livros as $livro): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($livro['Codl']) ?></td>
                                        <td><?= htmlspecialchars($livro['Titulo']) ?></td>
                                        <td><?= htmlspecialchars($livro['Editora']) ?></td>
                                        <td><?= htmlspecialchars($livro['Edicao']) ?>ª</td>
                                        <td><?= htmlspecialchars($livro['AnoPublicacao']) ?></td>
                                        <!-- Formatação do valor em reais -->
                                        <td>R$ <?= number_format($livro['Valor'], 2, ',', '.') ?></td>
                                        <!-- Autores e assuntos como strings concatenadas (vindos do findAllWithRelations) -->
                                        <td><?= htmlspecialchars($livro['Autores'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($livro['Assuntos'] ?? '-') ?></td>
                                        <td>
                                            <!-- Botão de editar -->
                                            <a href="<?= $url('livro/edit/' . $livro['Codl']) ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <!-- Botão de excluir com confirmação -->
                                            <button class="btn btn-sm btn-danger" onclick="deleteItem('<?= $url('livro/delete') ?>/<?= $livro['Codl'] ?>', 'Tem certeza que deseja excluir este livro?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
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
