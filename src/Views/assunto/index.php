<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-tag"></i> Gerenciar Assuntos</h4>
                <a href="<?= $url('assunto/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Novo Assunto
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Código</th>
                                <th>Descrição</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($assuntos)): ?>
                                <tr><td colspan="3" class="text-center">Nenhum assunto cadastrado.</td></tr>
                            <?php else: ?>
                                <?php foreach ($assuntos as $assunto): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($assunto['codAs']) ?></td>
                                        <td><?= htmlspecialchars($assunto['Descricao']) ?></td>
                                        <td>
                                            <a href="<?= $url('assunto/edit/' . $assunto['codAs']) ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger" onclick="deleteItem('<?= $url('assunto/delete') ?>/<?= $assunto['codAs'] ?>', 'Tem certeza que deseja excluir este assunto?')">
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
