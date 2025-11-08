<!-- View personalizada de formulário de livros com funcionalidades extras -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi <?= $icon ?>"></i> <?= $item ? 'Editar' : 'Novo' ?> <?= $entityName ?></h4>
            </div>
            <div class="card-body">
                <form id="<?= $viewName ?>Form">
                    <input type="hidden" name="id" value="<?= $item[$primaryKey] ?? '' ?>">
                    
                    <div class="row">
                        <?php 
                        $autoresField = null;
                        $assuntosField = null;
                        $otherFields = [];
                        
                        // Separa campos de relacionamento dos demais
                        foreach ($fields as $field) {
                            $campo = $field[0];
                            if ($campo === 'autores') {
                                $autoresField = $field;
                            } elseif ($campo === 'assuntos') {
                                $assuntosField = $field;
                            } else {
                                $otherFields[] = $field;
                            }
                        }
                        
                        // Renderiza campos normais
                        foreach ($otherFields as $field): 
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
                            
                            if ($tipo === 'number') {
                                $value = $item[$dbKey] ?? ($valorPadrao ?? '');
                            } elseif ($tipo === 'currency') {
                                if ($item) {
                                    $value = number_format((float)($item[$dbKey] ?? 0), 2, ',', '.');
                                } else {
                                    $value = $valorPadrao ?? '0,00';
                                }
                            } else {
                                $value = $item[$dbKey] ?? ($valorPadrao ?? '');
                            }
                            
                            // Define largura da coluna: edição, ano e valor ficam na mesma linha (col-md-4)
                            $colClass = in_array($campo, ['edicao', 'ano_publicacao', 'valor']) ? 'col-md-4' : 'col-md-6';
                        ?>
                            <div class="<?= $colClass ?> mb-3">
                                <label for="<?= $campo ?>" class="form-label">
                                    <?= $nomeAmigavel ?><?= $obrigatorio ? ' <span class="text-danger">*</span>' : '' ?>
                                </label>
                                <?php if ($tipo === 'number'): 
                                    $maxValue = $maxLength ? (int)str_repeat('9', $maxLength) : null;
                                ?>
                                    <input type="number" class="form-control" id="<?= $campo ?>" name="<?= $campo ?>" 
                                           value="<?= htmlspecialchars($value) ?>" 
                                           <?= $obrigatorio ? 'required' : '' ?> 
                                           min="1" 
                                           <?= $maxValue ? 'max="' . $maxValue . '"' : '' ?>
                                           data-maxlength="<?= $maxLength ?: '' ?>">
                                <?php elseif ($tipo === 'currency'): ?>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control mask-currency" id="<?= $campo ?>" name="<?= $campo ?>" 
                                               value="<?= htmlspecialchars($value) ?>" 
                                               <?= $obrigatorio ? 'required' : '' ?>>
                                    </div>
                                <?php elseif ($tipo === 'year'): ?>
                                    <input type="text" class="form-control mask-year" id="<?= $campo ?>" name="<?= $campo ?>" 
                                           value="<?= htmlspecialchars($value) ?>" 
                                           <?= $obrigatorio ? 'required' : '' ?>
                                           <?= $maxLength ? 'maxlength="' . $maxLength . '"' : '' ?>>
                                <?php else: ?>
                                    <input type="text" class="form-control" id="<?= $campo ?>" name="<?= $campo ?>" 
                                           value="<?= htmlspecialchars($value) ?>" 
                                           <?= $obrigatorio ? 'required' : '' ?>
                                           <?= $maxLength ? 'maxlength="' . $maxLength . '"' : '' ?>>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Campos de relacionamento lado a lado com checkboxes -->
                        <div class="row">
                            <?php if ($autoresField): 
                                $campo = $autoresField[0];
                                $nomeAmigavel = $autoresField[1];
                                $obrigatorio = $autoresField[2] ?? false;
                                $valorPadrao = $autoresField[4] ?? null;
                                
                                $firstItem = reset($valorPadrao);
                                $itemKey = null;
                                $itemLabel = null;
                                foreach (['CodAu', 'codAs', 'Codl', 'id'] as $pk) {
                                    if (isset($firstItem[$pk])) {
                                        $itemKey = $pk;
                                        break;
                                    }
                                }
                                foreach (['Nome', 'Descricao', 'Titulo', 'name', 'title'] as $label) {
                                    if (isset($firstItem[$label])) {
                                        $itemLabel = $label;
                                        break;
                                    }
                                }
                                $selectedValues = [];
                                if ($item) {
                                    $relacaoKey = 'item' . ucfirst($campo);
                                    $selectedValues = $$relacaoKey ?? [];
                                }
                            ?>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <?= $nomeAmigavel ?><?= $obrigatorio ? ' <span class="text-danger">*</span>' : '' ?>
                                    </label>
                                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                        <?php foreach ($valorPadrao as $option): 
                                            $optionValue = $option[$itemKey];
                                            $isSelected = in_array($optionValue, $selectedValues);
                                        ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="<?= $campo ?>[]" 
                                                       value="<?= $optionValue ?>" 
                                                       id="<?= $campo ?>_<?= $optionValue ?>"
                                                       <?= $isSelected ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="<?= $campo ?>_<?= $optionValue ?>">
                                                    <?= htmlspecialchars($option[$itemLabel] ?? '') ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($assuntosField): 
                                $campo = $assuntosField[0];
                                $nomeAmigavel = $assuntosField[1];
                                $obrigatorio = $assuntosField[2] ?? false;
                                $valorPadrao = $assuntosField[4] ?? null;
                                
                                $firstItem = reset($valorPadrao);
                                $itemKey = null;
                                $itemLabel = null;
                                foreach (['CodAu', 'codAs', 'Codl', 'id'] as $pk) {
                                    if (isset($firstItem[$pk])) {
                                        $itemKey = $pk;
                                        break;
                                    }
                                }
                                foreach (['Nome', 'Descricao', 'Titulo', 'name', 'title'] as $label) {
                                    if (isset($firstItem[$label])) {
                                        $itemLabel = $label;
                                        break;
                                    }
                                }
                                $selectedValues = [];
                                if ($item) {
                                    $relacaoKey = 'item' . ucfirst($campo);
                                    $selectedValues = $$relacaoKey ?? [];
                                }
                            ?>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <?= $nomeAmigavel ?><?= $obrigatorio ? ' <span class="text-danger">*</span>' : '' ?>
                                    </label>
                                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                        <?php foreach ($valorPadrao as $option): 
                                            $optionValue = $option[$itemKey];
                                            $isSelected = in_array($optionValue, $selectedValues);
                                        ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="<?= $campo ?>[]" 
                                                       value="<?= $optionValue ?>" 
                                                       id="<?= $campo ?>_<?= $optionValue ?>"
                                                       <?= $isSelected ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="<?= $campo ?>_<?= $optionValue ?>">
                                                    <?= htmlspecialchars($option[$itemLabel] ?? '') ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="<?= $url($viewName) ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Salvar <?= $entityName ?>
                        </button>
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
    
    // Validação de checkboxes obrigatórios antes de submeter
    $('#<?= $viewName ?>Form').on('submit', function(e) {
        e.preventDefault();
        
        // Valida checkboxes obrigatórios
        const autoresChecked = $('input[name="autores[]"]:checked').length;
        const assuntosChecked = $('input[name="assuntos[]"]:checked').length;
        
        if (autoresChecked === 0) {
            alert('Selecione pelo menos um autor.');
            return false;
        }
        
        if (assuntosChecked === 0) {
            alert('Selecione pelo menos um assunto.');
            return false;
        }
        
        // Se passou na validação, submete o formulário
        submitForm('#<?= $viewName ?>Form', url, '<?= $url($viewName) ?>');
    });
});
</script>

