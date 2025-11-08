<!-- Formulário de criação/edição de livro -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- Cabeçalho dinâmico: mostra "Editar" ou "Novo" dependendo do contexto -->
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-book"></i> <?= $livro ? 'Editar' : 'Novo' ?> Livro</h4>
            </div>
            <div class="card-body">
                <form id="livroForm">
                    <!-- Campo hidden com ID do livro (usado apenas na edição) -->
                    <input type="hidden" name="id" value="<?= $livro['Codl'] ?? '' ?>">
                    
                    <!-- Campos básicos do livro -->
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="titulo" class="form-label">Título *</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" 
                                   value="<?= htmlspecialchars($livro['Titulo'] ?? '') ?>" required maxlength="40">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editora" class="form-label">Editora *</label>
                            <input type="text" class="form-control" id="editora" name="editora" 
                                   value="<?= htmlspecialchars($livro['Editora'] ?? '') ?>" required maxlength="40">
                        </div>
                    </div>
                    
                    <!-- Campos numéricos: edição, ano e valor -->
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="edicao" class="form-label">Edição *</label>
                            <input type="number" class="form-control" id="edicao" name="edicao" 
                                   value="<?= htmlspecialchars($livro['Edicao'] ?? '1') ?>" required min="1">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="ano_publicacao" class="form-label">Ano de Publicação *</label>
                            <input type="text" class="form-control mask-year" id="ano_publicacao" name="ano_publicacao" 
                                   value="<?= htmlspecialchars($livro['AnoPublicacao'] ?? '') ?>" required maxlength="4">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="valor" class="form-label">Valor (R$) *</label>
                            <input type="text" class="form-control mask-currency" id="valor" name="valor" 
                                   value="<?= $livro ? number_format($livro['Valor'], 2, ',', '.') : '' ?>" required>
                        </div>
                    </div>
                    
                    <!-- Seleção de relacionamentos: Autores e Assuntos -->
                    <div class="row">
                        <!-- Checkboxes para seleção de autores -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Autores</label>
                            <div class="border p-2" style="max-height: 200px; overflow-y: auto;">
                                <?php foreach ($autores as $autor): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="autores[]" 
                                               value="<?= $autor['CodAu'] ?>" id="autor_<?= $autor['CodAu'] ?>"
                                               <?= isset($livroAutores) && in_array($autor['CodAu'], $livroAutores) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="autor_<?= $autor['CodAu'] ?>">
                                            <?= htmlspecialchars($autor['Nome']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Checkboxes para seleção de assuntos -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Assuntos</label>
                            <div class="border p-2" style="max-height: 200px; overflow-y: auto;">
                                <?php foreach ($assuntos as $assunto): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="assuntos[]" 
                                               value="<?= $assunto['codAs'] ?>" id="assunto_<?= $assunto['codAs'] ?>"
                                               <?= isset($livroAssuntos) && in_array($assunto['codAs'], $livroAssuntos) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="assunto_<?= $assunto['codAs'] ?>">
                                            <?= htmlspecialchars($assunto['Descricao']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botões de ação -->
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Salvar</button>
                        <a href="<?= $url('livro') ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
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
    const livroId = <?= isset($livro) && $livro ? (int)$livro['Codl'] : 'null' ?>;
    const url = '<?= $url('livro/' . $action) ?>' + (livroId !== null ? '/' + livroId : '');
    
    // Submete o formulário via AJAX
    $('#livroForm').on('submit', function(e) {
        e.preventDefault();
        showLoading();
        
        // Coleta todos os dados do formulário, incluindo autores e assuntos selecionados
        const data = {
            titulo: $('#titulo').val(),
            editora: $('#editora').val(),
            edicao: $('#edicao').val(),
            ano_publicacao: $('#ano_publicacao').val(),
            valor: $('#valor').val(),
            // Converte os IDs de autores e assuntos selecionados para inteiros
            autores: $('input[name="autores[]"]:checked').map(function() { return parseInt($(this).val()); }).get(),
            assuntos: $('input[name="assuntos[]"]:checked').map(function() { return parseInt($(this).val()); }).get()
        };
        
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Redireciona para a listagem com mensagem de sucesso
                    window.location.href = '<?= $url('livro') ?>?success=' + encodeURIComponent(response.message);
                } else {
                    showMessage(response.message, 'danger');
                    hideLoading();
                }
            },
            error: function(xhr) {
                // Trata erros de validação ou servidor
                let errorMsg = 'Erro ao salvar livro.';
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) errorMsg = response.message;
                } catch (e) {}
                showMessage(errorMsg, 'danger');
                hideLoading();
            }
        });
    });
});
</script>
