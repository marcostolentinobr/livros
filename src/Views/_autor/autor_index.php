<!-- View personalizada de listagem de autores com funcionalidades extras -->
<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> <strong>Total de Autores:</strong> <?= count($items) ?> cadastrados
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi <?= $icon ?>"></i> Gerenciar <?= $entityName ?>s</h4>
                <a href="<?= $url($viewName . '/create') ?>" class="btn btn-light">
                    <i class="bi bi-plus-circle"></i> Novo <?= $entityName ?>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <?php foreach ($fields as $field): 
                                    $campo = $field[0];
                                    $dbKey = str_replace('_', '', ucwords($campo, '_'));
                                    if ($primaryKey && $dbKey === $primaryKey) continue;
                                ?>
                                    <th><?= $field[1] ?></th>
                                <?php endforeach; ?>
                                <th width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $displayCount = count($fields);
                            $colspan = $displayCount + 1;
                            ?>
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="<?= $colspan ?>" class="text-center py-5">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="text-muted mt-3">Nenhum <?= strtolower($entityName) ?> cadastrado.</p>
                                        <a href="<?= $url($viewName . '/create') ?>" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Cadastrar Primeiro <?= $entityName ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <?php foreach ($fields as $field): 
                                            $campo = $field[0];
                                            $dbKey = str_replace('_', '', ucwords($campo, '_'));
                                            if ($primaryKey && $dbKey === $primaryKey) continue;
                                            $value = $item[$dbKey] ?? '';
                                        ?>
                                            <td>
                                                <strong><?= htmlspecialchars($value) ?></strong>
                                            </td>
                                        <?php endforeach; ?>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= $url($viewName . '/edit/' . $item[$primaryKey]) ?>" 
                                                   class="btn btn-outline-warning" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button class="btn btn-outline-danger" 
                                                        onclick="deleteItem('<?= $url($viewName . '/delete') ?>/<?= $item[$primaryKey] ?>', 'Tem certeza que deseja excluir este <?= strtolower($entityName) ?>?')"
                                                        title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
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

