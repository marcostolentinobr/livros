<!-- View personalizada de listagem de livros com funcionalidades extras -->
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-book"></i> Total de Livros</h5>
                <h2 class="mb-0"><?= count($items) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-check-circle"></i> Com Autores</h5>
                <h2 class="mb-0"><?= count(array_filter($items, fn($item) => !empty($item['Autores']))) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-tag"></i> Com Assuntos</h5>
                <h2 class="mb-0"><?= count(array_filter($items, fn($item) => !empty($item['Assuntos']))) ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi <?= $icon ?>"></i> Gerenciar <?= $entityName ?>s</h4>
                <div>
                    <a href="<?= $url($viewName . '/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Novo <?= $entityName ?>
                    </a>
                    <button class="btn btn-secondary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div>
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
                                    if ($primaryKey && $dbKey === $primaryKey) continue;
                                ?>
                                    <th><?= $field[1] ?></th>
                                <?php endforeach; ?>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
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
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle"></i> Nenhum <?= strtolower($entityName) ?> cadastrado.
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <?php if ($showPrimaryKey): ?>
                                            <td>
                                                <span class="badge bg-secondary"><?= htmlspecialchars($item[$primaryKey] ?? '') ?></span>
                                            </td>
                                        <?php endif; ?>
                                        <?php foreach ($fields as $field): 
                                            $campo = $field[0];
                                            $dbKey = str_replace('_', '', ucwords($campo, '_'));
                                            if ($primaryKey && $dbKey === $primaryKey) continue;
                                            $tipo = $field[5] ?? null;
                                            
                                            if ($tipo === 'select-multiple') {
                                                $relKey = ucfirst($campo);
                                                $value = $item[$relKey] ?? ($item[$dbKey] ?? '');
                                            } else {
                                                $value = $item[$dbKey] ?? '';
                                            }
                                            
                                            if ($tipo === 'currency') {
                                                $value = number_format((float)$value, 2, ',', '.');
                                            } elseif ($tipo === 'select-multiple') {
                                                if (empty($value)) {
                                                    $value = '<span class="badge bg-secondary">Nenhum</span>';
                                                } else {
                                                    if (mb_strlen($value) > $maxLengthMultiple) {
                                                        $value = mb_substr($value, 0, $maxLengthMultiple) . '...';
                                                    }
                                                    $value = '<span class="badge bg-info">' . htmlspecialchars($value) . '</span>';
                                                }
                                            }
                                        ?>
                                            <td><?= $tipo === 'select-multiple' ? $value : htmlspecialchars($value) ?></td>
                                        <?php endforeach; ?>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= $url($viewName . '/edit/' . $item[$primaryKey]) ?>" class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger" 
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

