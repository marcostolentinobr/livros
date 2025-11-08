<!-- View personalizada de listagem de assuntos com funcionalidades extras -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi <?= $icon ?>"></i> Gerenciar <?= $entityName ?>s</h4>
                <a href="<?= $url($viewName . '/create') ?>" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle"></i> Novo <?= $entityName ?>
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($items)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-tag" style="font-size: 4rem; color: #28a745;"></i>
                        <h5 class="mt-3 text-muted">Nenhum <?= strtolower($entityName) ?> cadastrado</h5>
                        <p class="text-muted">Comece cadastrando seu primeiro <?= strtolower($entityName) ?>.</p>
                        <a href="<?= $url($viewName . '/create') ?>" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Cadastrar
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($items as $item): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="bi bi-tag-fill text-success"></i> 
                                            <?= htmlspecialchars($item[$primaryKey] ?? '') ?>
                                        </h5>
                                        <p class="card-text">
                                            <?php foreach ($fields as $field): 
                                                $campo = $field[0];
                                                $dbKey = str_replace('_', '', ucwords($campo, '_'));
                                                if ($primaryKey && $dbKey === $primaryKey) continue;
                                                $value = $item[$dbKey] ?? '';
                                            ?>
                                                <?= htmlspecialchars($value) ?>
                                            <?php endforeach; ?>
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top">
                                        <div class="btn-group w-100">
                                            <a href="<?= $url($viewName . '/edit/' . $item[$primaryKey]) ?>" 
                                               class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteItem('<?= $url($viewName . '/delete') ?>/<?= $item[$primaryKey] ?>', 'Tem certeza que deseja excluir este <?= strtolower($entityName) ?>?')">
                                                <i class="bi bi-trash"></i> Excluir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

