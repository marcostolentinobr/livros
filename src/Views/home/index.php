<!-- Página inicial do sistema - Dashboard com acesso rápido às funcionalidades -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- Cabeçalho da página inicial -->
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-house-door"></i> Bem-vindo ao Sistema de Cadastro de Livros</h4>
            </div>
            <div class="card-body">
                <p class="lead">Sistema desenvolvido para gerenciamento de livros, autores e assuntos.</p>
                
                <!-- Cards de acesso rápido às principais funcionalidades -->
                <div class="row mt-4">
                    <!-- Card: Gerenciamento de Livros -->
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="bi bi-book" style="font-size: 3rem; color: #3498db;"></i>
                                <h5 class="card-title mt-3">Livros</h5>
                                <p class="card-text">Cadastre e gerencie livros com suas informações completas.</p>
                                <a href="<?= $url('livro') ?>" class="btn btn-primary">Gerenciar Livros</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card: Gerenciamento de Autores -->
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="bi bi-person" style="font-size: 3rem; color: #3498db;"></i>
                                <h5 class="card-title mt-3">Autores</h5>
                                <p class="card-text">Cadastre e gerencie autores do sistema.</p>
                                <a href="<?= $url('autor') ?>" class="btn btn-primary">Gerenciar Autores</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card: Gerenciamento de Assuntos -->
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="bi bi-tag" style="font-size: 3rem; color: #3498db;"></i>
                                <h5 class="card-title mt-3">Assuntos</h5>
                                <p class="card-text">Cadastre e gerencie assuntos/categorias dos livros.</p>
                                <a href="<?= $url('assunto') ?>" class="btn btn-primary">Gerenciar Assuntos</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Seção de Relatórios -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Relatórios</h5>
                            </div>
                            <div class="card-body">
                                <p>Visualize relatórios de livros agrupados por autor.</p>
                                <a href="<?= $url('relatorio') ?>" class="btn btn-info">Ver Relatório</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
