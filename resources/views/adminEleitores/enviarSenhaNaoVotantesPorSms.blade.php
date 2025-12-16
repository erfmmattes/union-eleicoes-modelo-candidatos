@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Enviar Senha para os eleitores não votates por sms')

@section('content')
<div class="container">
    <div class="container-fluid">

        <!-- Cabeçalho -->
        <div class="mb-5">
            <h1 class="h2 fw-bolder text-dark">Enviar Senha para os eleitores não votates por sms</h1>
            <p class="text-muted mt-1">
                Clique no botão abaixo para gerar e enviar a senha de acesso para todos os eleitores não votantes por sms.
            </p>
        </div>

        <!-- Card -->
        <div class="card shadow-lg border-0 rounded-3 stat-card">
            <div class="card-body p-4 p-md-5">

                <!-- Alertas -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                <!-- Barra de Progresso -->
                <div class="progress mt-4" style="height: 25px; display:none;" id="progressContainer">
                    <div class="progress-bar" id="progressBar" role="progressbar" style="width:0%">0%</div>
                </div>

                <!-- Botão de Envio e Abortar -->
                <form id="formEnviarSenha" action="{{ route('admin.adminEleitores.formEnviarSenhaParaTodosNaoVotantesPorSms') }}" method="GET">
                    @csrf
                    <div class="d-flex justify-content-center mt-4 gap-2">
                        <a href="{{ route('admin.adminEleitores.metodosDeEnviarSenha') }}" class="btn bot-cancelar px-4">
                            <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn bot-atualizar px-5">
                            <i class="fa-solid fa-envelope me-2"></i> Enviar Senhas
                        </button>
                        <button type="button" id="btnAbortar" class="btn btn-danger px-4" style="display:none;">
                            <i class="fa-solid fa-ban me-1"></i> Abortar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
<!---------------- Início - Estilos CSS -------------------->
<style>
    .bot-atualizar {
        background: linear-gradient(135deg, #183F77, #4A90E2);
        color: #fff !important;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .bot-atualizar:hover {
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
<!---------------- Início - Scripts JavaScript -------------------->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-temporaria');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 10000);
    });

    const form = document.getElementById('formEnviarSenha');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const btnAbortar = document.getElementById('btnAbortar');
    let abortController = null;

    form.addEventListener('submit', async function(e) {

        progressContainer.style.display = 'block';
        btnAbortar.style.display = 'inline-block';

        let percent = 0;
        const interval = setInterval(() => {
            if (percent >= 90) clearInterval(interval);
            else {
                percent += 5;
                progressBar.style.width = percent + '%';
                progressBar.textContent = percent + '%';
            }
        }, 200);

        abortController = new AbortController();
        const signal = abortController.signal;

        try {
            const response = await fetch(form.action, {
                method: form.method,
                signal,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            clearInterval(interval);
            progressBar.style.width = '100%';
            progressBar.textContent = '100%';
            btnAbortar.style.display = 'none';

            if (response.ok) {
            } else {
                alert('Erro ao enviar as senhas.');
            }
        } catch (error) {
            clearInterval(interval);
            progressBar.style.width = '0%';
            progressBar.textContent = '0%';
            btnAbortar.style.display = 'none';
            if (error.name === 'AbortError') {
                alert('Envio abortado pelo usuário.');
            } else {
                alert('Erro na requisição.');
            }
        }
    });

    btnAbortar.addEventListener('click', async function() {
        if (abortController) {
            abortController.abort(); // Cancela o fetch no frontend
        }

        try {
            // Envia sinal de abortar para o servidor
            await fetch('{{ route('admin.adminEleitores.abortarEnvioNaoVotantesPorSms') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        } catch (error) {
            console.warn('Falha ao notificar o backend sobre o abort.');
        }

        progressBar.style.width = '0%';
        progressBar.textContent = '0%';
        btnAbortar.style.display = 'none';
    });
});
</script>
<!---------------- Final - Scripts JavaScript -------------------->