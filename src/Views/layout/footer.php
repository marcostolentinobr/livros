    </div>
    
    <!-- Overlay de carregamento (mostrado durante requisições AJAX) -->
    <div id="loadingOverlay">
        <div class="text-center">
            <div class="loading-spinner"></div>
            <div class="text-white mt-3">Processando...</div>
        </div>
    </div>
    
    <!-- Rodapé da página -->
    <footer class="mt-5 py-4 bg-light">
        <div class="container text-center">
            <p class="text-muted mb-0">Sistema de Cadastro de Livros &copy; <?= date('Y') ?></p>
        </div>
    </footer>
    
    <!-- Scripts externos: Bootstrap e jQuery Mask -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    
    <!-- Scripts JavaScript do sistema -->
    <script>
        // Aplica máscaras de formatação nos campos de entrada
        $(document).ready(function() {
            // Máscara para valores monetários (R$ 1.234,56)
            $('.mask-currency').mask('000.000.000.000,00', {reverse: true});
            // Máscara para ano (4 dígitos)
            $('.mask-year').mask('0000');
        });

        /**
         * Exibe uma mensagem flash na tela (sucesso ou erro)
         * @param {string} message - Mensagem a ser exibida
         * @param {string} type - Tipo da mensagem ('success' ou 'danger')
         */
        function showMessage(message, type = 'success') {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            $('#flashMessages').html(`
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
        }

        /**
         * Mostra o overlay de carregamento e bloqueia o scroll da página
         */
        function showLoading() {
            $('#loadingOverlay').addClass('show');
            $('body').css('overflow', 'hidden');
        }

        /**
         * Esconde o overlay de carregamento e restaura o scroll da página
         */
        function hideLoading() {
            $('#loadingOverlay').removeClass('show');
            $('body').css('overflow', 'auto');
        }

        /**
         * Exclui um item via AJAX com confirmação
         * @param {string} url - URL para exclusão
         * @param {string} message - Mensagem de confirmação
         */
        function deleteItem(url, message) {
            if (!confirm(message)) return;
            showLoading();
            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Redireciona para a mesma página com mensagem de sucesso
                        window.location.href = window.location.pathname + '?success=' + encodeURIComponent(response.message);
                    } else {
                        showMessage(response.message, 'danger');
                        hideLoading();
                    }
                },
                error: function() {
                    showMessage('Erro ao processar solicitação.', 'danger');
                    hideLoading();
                }
            });
        }

        /**
         * Submete um formulário via AJAX
         * @param {string} formId - Seletor do formulário (#id)
         * @param {string} url - URL para envio
         * @param {string} redirectUrl - URL para redirecionamento em caso de sucesso
         */
        function submitForm(formId, url, redirectUrl) {
            $(formId).on('submit', function(e) {
                e.preventDefault();
                showLoading();
                const formData = $(this).serialize();
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Redireciona para a página de listagem com mensagem de sucesso
                            window.location.href = redirectUrl + '?success=' + encodeURIComponent(response.message);
                        } else {
                            showMessage(response.message, 'danger');
                            hideLoading();
                        }
                    },
                    error: function(xhr) {
                        // Trata erros de validação ou servidor
                        let errorMsg = 'Erro ao salvar.';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) errorMsg = response.message;
                        } catch (e) {}
                        showMessage(errorMsg, 'danger');
                        hideLoading();
                    }
                });
            });
        }

        // Configura handlers globais para requisições AJAX
        // Mostra loading no início e esconde em caso de erro ou resposta com success=false
        $(document).ajaxStart(showLoading).ajaxSuccess(function(event, xhr) {
            try {
                const response = typeof xhr.responseText === 'string' ? JSON.parse(xhr.responseText) : xhr.responseText;
                if (response && response.success === false) hideLoading();
            } catch (e) {}
        }).ajaxError(hideLoading);

        // Verifica se há mensagens de sucesso ou erro na URL e as exibe
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success')) {
            showMessage(decodeURIComponent(urlParams.get('success')), 'success');
            // Remove o parâmetro da URL sem recarregar a página
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        if (urlParams.get('error')) {
            showMessage(decodeURIComponent(urlParams.get('error')), 'danger');
            // Remove o parâmetro da URL sem recarregar a página
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</body>
</html>
