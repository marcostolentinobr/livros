<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi <?= $icon ?>"></i> Gerenciar <?= $entityName ?>s</h4>
                <a href="<?= $url($viewName . '/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Novo <?= $entityName ?>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <?php if ($showPrimaryKey): ?>
                                    <th>Código</th>
                                <?php endif; ?>
                                <?php foreach ($fields as $field): 
                                    $campo = $field[0];
                                    $dbKey = str_replace('_', '', ucwords($campo, '_'));
                                    // Ignora campos que são chave primária
                                    if ($primaryKey && $dbKey === $primaryKey) continue;
                                ?>
                                    <th><?= $field[1] ?></th>
                                <?php endforeach; ?>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Calcula colspan para mensagem vazia
                            $displayCount = 0;
                            foreach ($fields as $field) {
                                $campo = $field[0];
                                $dbKey = str_replace('_', '', ucwords($campo, '_'));
                                if (!$primaryKey || $dbKey !== $primaryKey) {
                                    $displayCount++;
                                }
                            }
                            $colspan = $displayCount + 1 + ($showPrimaryKey ? 1 : 0);
                            ?>
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="<?= $colspan ?>" class="text-center">
                                        Nenhum <?= strtolower($entityName) ?> cadastrado.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <?php if ($showPrimaryKey): ?>
                                            <td><?= htmlspecialchars($item[$primaryKey] ?? '') ?></td>
                                        <?php endif; ?>
                                        <?php foreach ($fields as $field): 
                                            $campo = $field[0];
                                            $dbKey = str_replace('_', '', ucwords($campo, '_'));
                                            // Ignora campos que são chave primária
                                            if ($primaryKey && $dbKey === $primaryKey) continue;
                                            $value = $item[$dbKey] ?? '';
                                        ?>
                                            <td><?= htmlspecialchars($value) ?></td>
                                        <?php endforeach; ?>
                                        <td>
                                            <a href="<?= $url($viewName . '/edit/' . $item[$primaryKey]) ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="deleteItem('<?= $url($viewName . '/delete') ?>/<?= $item[$primaryKey] ?>', 'Tem certeza que deseja excluir este <?= strtolower($entityName) ?>?')">
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

