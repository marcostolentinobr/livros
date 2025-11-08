<div class="row">
    <div class="col-12">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Erro</h4>
            <p><?= htmlspecialchars($message ?? 'Ocorreu um erro inesperado.') ?></p>
            <hr>
            <a href="<?= $url('home') ?>" class="btn btn-primary">Voltar ao Início</a>
        </div>
    </div>
</div>
