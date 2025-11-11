@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Todos os Métodos de Enviar Senha para os eleitores')

@section('content')
<div class="container">
    <div class="container-fluid">

        <!-- Cabeçalho -->
        <div class="mb-5">
            <h1 class="h2 fw-bolder text-dark">Todos os Métodos de Enviar Senha para os eleitores</h1>
            <p class="text-muted mt-1">
                Clique na forma desejada para gerar e enviar a senha de acesso para todos os eleitores por e-mail, sms ou não votantes.
            </p>
        </div>

        <!-- Card -->
        <div class="card shadow-lg border-0 rounded-3 stat-card">
            <div class="card-body p-4 p-md-5">

                <div class="row g-4 text-center">

                    <!-- 1️⃣ Enviar senha para todos por e-mail e sms -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('admin.adminEleitores.enviarSenha') }}" class="btn bot-enviar w-100 py-4 h-100 shadow-sm d-flex flex-column align-items-center justify-content-center" title="Enviar senha para todos por e-mail e sms">
                            <div class="d-flex">
                                <i class="fa-solid fa-envelope fa-2x mb-2 me-2 text-white"></i>
                                <i class="fa-solid fa-comment-sms fa-2x mb-2 text-white"></i>
                            </div>
                            <span class="fw-semibold text-white small">Enviar senha para todos por e-mail e sms</span>
                        </a>
                    </div>

                    <!-- 2️⃣ Enviar por e-mail -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('admin.adminEleitores.enviarSenhaParaTodosPorEmail') }}" class="btn bot-enviar w-100 py-4 h-100 shadow-sm d-flex flex-column align-items-center justify-content-center" titla="Enviar por e-mail">
                            <i class="fa-solid fa-envelope fa-2x mb-2 me-2 text-white"></i>
                            <span class="fw-semibold text-white small">Enviar por e-mail</span>
                        </a>
                    </div>

                    <!-- 3️⃣ Criar Novo -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('admin.adminEleitores.enviarSenhaParaTodosPorSms') }}" class="btn bot-enviar w-100 py-4 h-100 shadow-sm d-flex flex-column align-items-center justify-content-center" title="Enviar por sms">
                            <i class="fa-solid fa-comment-sms fa-2x mb-2 text-white"></i>
                            <span class="fw-semibold text-white small">Enviar por sms</span>
                        </a>
                    </div>

                    <!-- 4️⃣ Enviar para os não votantes senha por e-mail e sms -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('admin.adminEleitores.enviarSenhaNaoVotantes') }}" class="btn bot-enviar w-100 py-4 h-100 shadow-sm d-flex flex-column align-items-center justify-content-center" title="Enviar para os não votantes senha por e-mail e sms">
                            <div class="d-flex">
                                <i class="fa-solid fa-envelope fa-2x mb-2 me-2 text-white"></i>
                                <i class="fa-solid fa-comment-sms fa-2x mb-2 text-white"></i>
                            </div>
                            <span class="fw-semibold text-white small">Enviar para os não votantes senha por e-mail e sms</span>
                        </a>
                    </div>

                    <!-- 5️⃣ Enviar para não votantes por e-mail -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('admin.adminEleitores.enviarSenhaNaoVotantesPorEmail') }}" class="btn bot-enviar w-100 py-4 h-100 shadow-sm d-flex flex-column align-items-center justify-content-center" title="Enviar para não votantes por e-mail">
                            <i class="fa-solid fa-envelope fa-2x mb-2 me-2 text-white"></i>
                            <span class="fw-semibold text-white small">Enviar para não votantes por e-mail</span>
                        </a>
                    </div>

                    <!-- 6️⃣ Enviar para não votantes por sms -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('admin.adminEleitores.enviarSenhaNaoVotantesPorSms') }}" class="btn bot-enviar w-100 py-4 h-100 shadow-sm d-flex flex-column align-items-center justify-content-center" title="Enviar para não votantes por sms">
                            <i class="fa-solid fa-comment-sms fa-2x mb-2 text-white"></i>
                            <span class="fw-semibold text-white small">Enviar para não votantes por sms</span>
                        </a>
                    </div>

                </div>

                <div class="mt-5 text-end">
                    <a href="{{ route('admin.adminEleitores.index') }}" class="btn bot-cancelar px-4">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
<!---------------- Início - Estilos CSS -------------------->
<style>
    .bot-enviar {
        background: linear-gradient(135deg, #183F77, #4A90E2);
        color: #fff !important;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .bot-enviar:hover {
        background: linear-gradient(135deg, #122b55, #3570c2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
    .bot-cancelar {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        color: #fff !important;
    }
    .bot-cancelar:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
</style>
<!---------------- Final - Estilos CSS -------------------->