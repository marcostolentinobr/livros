<!-- Formulário de criação/edição de autor -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- Cabeçalho dinâmico: mostra "Editar" ou "Novo" dependendo do contexto -->
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-person"></i> <?= $autor ? 'Editar' : 'Novo' ?> Autor</h4>
            </div>
            <div class="card-body">
                <form id="autorForm">
                    <!-- Campo hidden com ID do autor (usado apenas na edição) -->
                    <input type="hidden" name="id" value="<?= $autor['CodAu'] ?? '' ?>">
                    
                    <!-- Campo de nome do autor -->
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome *</label>
                        <input type="text" class="form-control" id="nome" name="nome" 
                               value="<?= htmlspecialchars($autor['Nome'] ?? '') ?>" required maxlength="40">
                    </div>
                    
                    <!-- Botões de ação -->
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Salvar</button>
                        <a href="<?= $url('autor') ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
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
    const autorId = <?= isset($autor) && $autor ? (int)$autor['CodAu'] : 'null' ?>;
    const url = '<?= $url('autor/' . $action) ?>' + (autorId !== null ? '/' + autorId : '');
    
    // Submete o formulário usando a função helper submitForm
    submitForm('#autorForm', url, '<?= $url('autor') ?>');
});
</script>
