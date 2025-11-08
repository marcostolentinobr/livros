<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-tag"></i> <?= $assunto ? 'Editar' : 'Novo' ?> Assunto</h4>
            </div>
            <div class="card-body">
                <form id="assuntoForm">
                    <input type="hidden" name="id" value="<?= $assunto['codAs'] ?? '' ?>">
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição *</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" 
                               value="<?= htmlspecialchars($assunto['Descricao'] ?? '') ?>" required maxlength="20">
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Salvar</button>
                        <a href="<?= $url('assunto') ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    const assuntoId = <?= isset($assunto) && $assunto ? (int)$assunto['codAs'] : 'null' ?>;
    const url = '<?= $url('assunto/' . $action) ?>' + (assuntoId !== null ? '/' + assuntoId : '');
    submitForm('#assuntoForm', url, '<?= $url('assunto') ?>');
});
</script>
