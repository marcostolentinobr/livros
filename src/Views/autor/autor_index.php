<!-- Página de listagem de autores -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- Cabeçalho com título e botão de novo autor -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-person"></i> Gerenciar Autores</h4>
                <a href="<?= $url('autor/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Novo Autor
                </a>
            </div>
            <div class="card-body">
                <!-- Tabela responsiva com lista de autores -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($autores)): ?>
                                <!-- Mensagem quando não há autores cadastrados -->
                                <tr><td colspan="3" class="text-center">Nenhum autor cadastrado.</td></tr>
                            <?php else: ?>
                                <!-- Loop para exibir cada autor -->
                                <?php foreach ($autores as $autor): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($autor['CodAu']) ?></td>
                                        <td><?= htmlspecialchars($autor['Nome']) ?></td>
                                        <td>
                                            <!-- Botão de editar -->
                                            <a href="<?= $url('autor/edit/' . $autor['CodAu']) ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <!-- Botão de excluir com confirmação -->
                                            <button class="btn btn-sm btn-danger" onclick="deleteItem('<?= $url('autor/delete') ?>/<?= $autor['CodAu'] ?>', 'Tem certeza que deseja excluir este autor?')">
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
