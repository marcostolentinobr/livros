<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
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
                        // Ignora campos que são chave primária
                        if ($primaryKey && $dbKey === $primaryKey) continue;
                        
                        // Se valorPadrao é array e tipo não especificado, assume select-multiple
                        if (is_array($valorPadrao) && $tipo === null) {
                            $tipo = 'select-multiple';
                        }
                        // Se tipo não especificado, assume text
                        if ($tipo === null) {
                            $tipo = 'text';
                        }
                        
                        // Determina se é múltiplo baseado no tipo
                        $multiple = ($tipo === 'select-multiple');
                        
                        // Formata valor conforme tipo
                        if ($tipo === 'select' || $tipo === 'select-multiple') {
                            $value = '';
                        } elseif ($tipo === 'number') {
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
                    ?>
                        <div class="mb-3">
                            <label for="<?= $campo ?>" class="form-label"><?= $nomeAmigavel ?><?= $obrigatorio ? ' *' : '' ?></label>
                            <?php if ($tipo === 'select' || $tipo === 'select-multiple'): 
                                // Detecta chave primária e campo de label do primeiro item
                                $firstItem = reset($valorPadrao);
                                $itemKey = null;
                                $itemLabel = null;
                                // Tenta identificar chave primária comum (CodAu, codAs, Codl)
                                foreach (['CodAu', 'codAs', 'Codl', 'id'] as $pk) {
                                    if (isset($firstItem[$pk])) {
                                        $itemKey = $pk;
                                        break;
                                    }
                                }
                                // Tenta identificar campo de label comum (Nome, Descricao, Titulo)
                                foreach (['Nome', 'Descricao', 'Titulo', 'name', 'title'] as $label) {
                                    if (isset($firstItem[$label])) {
                                        $itemLabel = $label;
                                        break;
                                    }
                                }
                                // Carrega valores selecionados se for edição
                                $selectedValues = [];
                                if ($item) {
                                    $relacaoKey = 'item' . ucfirst($campo); // itemAutores, itemAssuntos
                                    $selectedValues = $$relacaoKey ?? [];
                                    // Se for select único, pega apenas o primeiro valor como string
                                    if (!$multiple && !empty($selectedValues)) {
                                        $selectedValues = reset($selectedValues);
                                    }
                                }
                            ?>
                                <select class="form-select" id="<?= $campo ?>" name="<?= $campo ?><?= $multiple ? '[]' : '' ?>" 
                                        <?= $obrigatorio ? 'required' : '' ?><?= $multiple ? ' multiple' : '' ?>>
                                    <?php if (!$multiple): ?>
                                        <option value="">Selecione...</option>
                                    <?php endif; ?>
                                    <?php foreach ($valorPadrao as $option): 
                                        $optionValue = $option[$itemKey];
                                        $isSelected = $multiple 
                                            ? in_array($optionValue, $selectedValues) 
                                            : ($selectedValues == $optionValue);
                                    ?>
                                        <option value="<?= $optionValue ?>" 
                                                <?= $isSelected ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($option[$itemLabel] ?? '') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php elseif ($tipo === 'number'): 
                                // Calcula valor máximo baseado no maxlength (ex: maxlength=4 -> max=9999)
                                $maxValue = $maxLength ? (int)str_repeat('9', $maxLength) : null;
                            ?>
                                <input type="number" class="form-control" id="<?= $campo ?>" name="<?= $campo ?>" 
                                       value="<?= htmlspecialchars($value) ?>" 
                                       <?= $obrigatorio ? 'required' : '' ?> 
                                       min="1" 
                                       <?= $maxValue ? 'max="' . $maxValue . '"' : '' ?>
                                       data-maxlength="<?= $maxLength ?: '' ?>">
                            <?php elseif ($tipo === 'currency'): ?>
                                <input type="text" class="form-control mask-currency" id="<?= $campo ?>" name="<?= $campo ?>" 
                                       value="<?= htmlspecialchars($value) ?>" 
                                       <?= $obrigatorio ? 'required' : '' ?>>
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
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Salvar</button>
                        <a href="<?= $url($viewName) ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
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

