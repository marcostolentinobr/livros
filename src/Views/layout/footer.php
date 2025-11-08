    </div>
    <div id="loadingOverlay">
        <div class="text-center">
            <div class="loading-spinner"></div>
            <div class="text-white mt-3">Processando...</div>
        </div>
    </div>
    <footer class="mt-5 py-4 bg-light">
        <div class="container text-center">
            <p class="text-muted mb-0">Sistema de Cadastro de Livros &copy; <?= date('Y') ?></p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.mask-currency').mask('000.000.000.000,00', {reverse: true});
            $('.mask-year').mask('0000');
        });

        function showMessage(message, type = 'success') {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            $('#flashMessages').html(`
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
        }

        function showLoading() {
            $('#loadingOverlay').addClass('show');
            $('body').css('overflow', 'hidden');
        }

        function hideLoading() {
            $('#loadingOverlay').removeClass('show');
            $('body').css('overflow', 'auto');
        }

        function deleteItem(url, message) {
            if (!confirm(message)) return;
            showLoading();
            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
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
                            window.location.href = redirectUrl + '?success=' + encodeURIComponent(response.message);
                        } else {
                            showMessage(response.message, 'danger');
                            hideLoading();
                        }
                    },
                    error: function(xhr) {
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

        $(document).ajaxStart(showLoading).ajaxSuccess(function(event, xhr) {
            try {
                const response = typeof xhr.responseText === 'string' ? JSON.parse(xhr.responseText) : xhr.responseText;
                if (response && response.success === false) hideLoading();
            } catch (e) {}
        }).ajaxError(hideLoading);

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success')) {
            showMessage(decodeURIComponent(urlParams.get('success')), 'success');
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        if (urlParams.get('error')) {
            showMessage(decodeURIComponent(urlParams.get('error')), 'danger');
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</body>
</html>
