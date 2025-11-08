<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-person"></i> <?= $autor ? 'Editar' : 'Novo' ?> Autor</h4>
            </div>
            <div class="card-body">
                <form id="autorForm">
                    <input type="hidden" name="id" value="<?= $autor['CodAu'] ?? '' ?>">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome *</label>
                        <input type="text" class="form-control" id="nome" name="nome" 
                               value="<?= htmlspecialchars($autor['Nome'] ?? '') ?>" required maxlength="40">
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Salvar</button>
                        <a href="<?= $url('autor') ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    const autorId = <?= isset($autor) && $autor ? (int)$autor['CodAu'] : 'null' ?>;
    const url = '<?= $url('autor/' . $action) ?>' + (autorId !== null ? '/' + autorId : '');
    submitForm('#autorForm', url, '<?= $url('autor') ?>');
});
</script>
