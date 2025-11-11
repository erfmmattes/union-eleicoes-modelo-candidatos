@extends('layouts.appMasterFront')
@section('title', 'Union Eleições - Trocar Senha')
@section('content')
<section class="hero">
    <div class="aba-geral">
        <h1>Trocar senha</h1>

        <div class="descricao-ajuda card p-4">

            @if ($errors->any())
                <div class="alert alert-danger alert-temporaria">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-temporaria">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-temporaria">{{ session('error') }}</div>
            @endif

            <form action="{{ route('loginEleicao.senhaTrocarAposLogin') }}" method="POST" id="formTrocaSenha">
                @csrf

                <!-- Senha Atual -->
                <div class="mb-4 text-start">
                    <label for="senha_atual" class="form-label fw-semibold">Senha Atual</label>
                    <div class="input-group">
                        <input type="password" id="senha_atual" name="senha_atual" class="form-control form-control-lg" placeholder="Digite sua senha atual" required minlength="6">
                        <span class="input-group-text toggle-password" data-target="senha_atual" style="cursor:pointer;">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>

                <!-- Nova Senha -->
                <div class="mb-4 text-start">
                    <label for="nova_senha" class="form-label fw-semibold">Nova Senha</label>
                    <div class="input-group">
                        <input type="password" id="nova_senha" name="nova_senha" class="form-control form-control-lg" placeholder="Digite a nova senha" required minlength="6">
                        <span class="input-group-text toggle-password" data-target="nova_senha" style="cursor:pointer;">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>

                <!-- Confirmar Nova Senha -->
                <div class="mb-4 text-start">
                    <label for="confirmar_senha" class="form-label fw-semibold">Confirmar Nova Senha</label>
                    <div class="input-group">
                        <input type="password" id="confirmar_senha" name="nova_senha_confirmation" class="form-control form-control-lg" placeholder="Confirme a nova senha" required minlength="6">
                        <span class="input-group-text toggle-password" data-target="confirmar_senha" style="cursor:pointer;">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-hero w-100">Salvar nova senha</button>
            </form>

        </div>
    </div>
</section>
@endsection
<!-- -------------- Início - Estilos CSS ------------------ -->
<style>
    .aba-geral {
        margin: 100px 0px 35px 0px;
    }
    .hero {
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 0 2rem;
        color: #fff;
    }
    .hero h1 {
        color: #000000;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    .hero .btn-hero {
        display: block;
        width: 100%;
        max-width: 525px;
        margin: 0 auto;
        color: #ffffff;
        font-weight: 500;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        background-color: {{ $dados['configuracoes']->cor_principal }};
    }
    .hero .btn-hero:hover {
        color: #ffffff;
        font-weight: bold;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        background-color: {{ $dados['configuracoes']->cor_hover }};
    }
    .descricao-ajuda {
        font-size: 16px !important;
        margin: auto;
        text-align: justify;
        width: 50%;
    }
    .input-group-text {
        background-color: #f8f9fa;
        border-left: 0;
    }
    .input-group .form-control {
        border-right: 0;
    }
    @media (max-width: 992px) {
        .descricao-ajuda {
            width: 100%;
        }
    }
</style>
<!---------------- Final - Estilos CSS -------------------->
<!---------------- Início - Scripts JavaScript -------------------->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 🔹 Alerta temporário
    const alerts = document.querySelectorAll('.alert-temporaria');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // 🔹 Mostrar/ocultar senha com ícone
    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }); 
});
</script>
<!---------------- Final - Scripts JavaScript -------------------->