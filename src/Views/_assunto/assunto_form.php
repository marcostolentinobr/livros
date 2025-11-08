<!-- View personalizada de formulário de assuntos com funcionalidades extras -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi <?= $icon ?>"></i> <?= $item ? 'Editar' : 'Novo' ?> <?= $entityName ?></h4>
            </div>
            <div class="card-body">
                <form id="<?= $viewName ?>Form">
                    <input type="hidden" name="id" value="<?= $item[$primaryKey] ?? '' ?>">
                    
                    <?php foreach ($fields as $field): 
                        $campo = $field[0];
                        $nomeAmigavel = $field[1];
                        $obrigatorio = $field[2] ?? false;
                        $maxLength = $field[3] ?? null;
                        $valorPadrao = $field[4] ?? null;
                        $tipo = $field[5] ?? null;
                        $dbKey = str_replace('_', '', ucwords($campo, '_'));
                        if ($primaryKey && $dbKey === $primaryKey) continue;
                        
                        if ($tipo === null) {
                            $tipo = 'text';
                        }
                        $value = $item[$dbKey] ?? ($valorPadrao ?? '');
                    ?>
                        <div class="mb-3">
                            <label for="<?= $campo ?>" class="form-label">
                                <i class="bi bi-tag"></i> <?= $nomeAmigavel ?><?= $obrigatorio ? ' <span class="text-danger">*</span>' : '' ?>
                            </label>
                            <input type="text" class="form-control" id="<?= $campo ?>" name="<?= $campo ?>" 
                                   value="<?= htmlspecialchars($value) ?>" 
                                   <?= $obrigatorio ? 'required' : '' ?>
                                   <?= $maxLength ? 'maxlength="' . $maxLength . '"' : '' ?>>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Salvar
                        </button>
                        <a href="<?= $url($viewName) ?>" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const itemId = <?= isset($item) && $item ? (int)$item[$primaryKey] : 'null' ?>;
    const url = '<?= $url($viewName . '/' . $action) ?>' + (itemId !== null ? '/' + itemId : '');
    submitForm('#<?= $viewName ?>Form', url, '<?= $url($viewName) ?>');
});
</script>

