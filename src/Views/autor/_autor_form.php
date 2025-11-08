<!-- View personalizada de formulário de autores com funcionalidades extras -->
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0"><i class="bi <?= $icon ?>"></i> <?= $item ? 'Editar' : 'Cadastrar' ?> <?= $entityName ?></h4>
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
                        <div class="mb-4">
                            <label for="<?= $campo ?>" class="form-label fw-bold">
                                <?= $nomeAmigavel ?><?= $obrigatorio ? ' <span class="text-danger">*</span>' : '' ?>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="<?= $campo ?>" name="<?= $campo ?>" 
                                   value="<?= htmlspecialchars($value) ?>" 
                                   <?= $obrigatorio ? 'required' : '' ?>
                                   <?= $maxLength ? 'maxlength="' . $maxLength . '"' : '' ?>
                                   placeholder="Digite o <?= strtolower($nomeAmigavel) ?>">
                            <?php if ($maxLength): ?>
                                <div class="form-text">
                                    <small>Máximo de <?= $maxLength ?> caracteres</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Salvar
                        </button>
                        <a href="<?= $url($viewName) ?>" class="btn btn-outline-secondary">
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

