<!-- Formulário de criação/edição de assunto -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- Cabeçalho dinâmico: mostra "Editar" ou "Novo" dependendo do contexto -->
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-tag"></i> <?= $assunto ? 'Editar' : 'Novo' ?> Assunto</h4>
            </div>
            <div class="card-body">
                <form id="assuntoForm">
                    <!-- Campo hidden com ID do assunto (usado apenas na edição) -->
                    <input type="hidden" name="id" value="<?= $assunto['codAs'] ?? '' ?>">
                    
                    <!-- Campo de descrição do assunto -->
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição *</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" 
                               value="<?= htmlspecialchars($assunto['Descricao'] ?? '') ?>" required maxlength="20">
                    </div>
                    
                    <!-- Botões de ação -->
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Salvar</button>
                        <a href="<?= $url('assunto') ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script para envio do formulário via AJAX -->
<script>
$(document).ready(function() {
    // Determina a URL baseada na ação (create ou update) e se há ID
    const assuntoId = <?= isset($assunto) && $assunto ? (int)$assunto['codAs'] : 'null' ?>;
    const url = '<?= $url('assunto/' . $action) ?>' + (assuntoId !== null ? '/' + assuntoId : '');
    
    // Submete o formulário usando a função helper submitForm
    submitForm('#assuntoForm', url, '<?= $url('assunto') ?>');
});
</script>
