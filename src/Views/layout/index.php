<!-- Página de listagem genérica -->
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
                                <?php 
                                // Filtra campos que não são chave primária para exibir na tabela
                                $displayFields = array_filter($fields, fn($f) => ($f[4] ?? false) !== true);
                                foreach ($displayFields as $field): 
                                ?>
                                    <th><?= $field[1] ?></th>
                                <?php endforeach; ?>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="<?= count($displayFields) + 1 ?>" class="text-center">
                                        Nenhum <?= strtolower($entityName) ?> cadastrado.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <?php foreach ($displayFields as $field): 
                                            $campo = $field[0];
                                            // Se o campo já está no formato do banco, usa diretamente; senão converte
                                            $dbKey = (strpos($campo, '_') === false && ctype_upper(substr($campo, 0, 1))) 
                                                ? $campo 
                                                : str_replace('_', '', ucwords($campo, '_'));
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

