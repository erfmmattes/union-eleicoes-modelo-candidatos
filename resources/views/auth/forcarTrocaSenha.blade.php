@extends('layouts.appTrocaDeSenhaUser')

@section('title', 'Unir Votações - Trocar Senha Obrigatória')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-5">
                    <h2 class="h4 fw-bold text-center text-dark mb-4">
                        🔒 Alteração obrigatória de senha
                    </h2>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- OBS: verifique se o nome da rota abaixo é exatamente esse no route:list -->
                    <form method="POST" action="{{ route('admin.forcarTrocaSenha.update') }}">
                        @csrf

                        {{-- SENHA ATUAL --}}
                        <div class="mb-3 position-relative">
                            <label for="senha_atual" class="form-label fw-semibold">Senha atual</label>
                            <div class="input-group">
                                <input type="password" id="senha_atual" name="senha_atual"
                                    class="form-control form-control-lg" required autocomplete="current-password">
                                <button type="button" class="btn btn-outline-secondary toggle-senha" data-target="senha_atual" aria-label="Mostrar senha">
                                    <!-- ícone inline (fallback se Font Awesome não carregar) -->
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        {{-- NOVA SENHA --}}
                        <div class="mb-3 position-relative">
                            <label for="nova_senha" class="form-label fw-semibold">Nova senha</label>
                            <div class="input-group">
                                <input type="password" id="nova_senha" name="nova_senha"
                                    class="form-control form-control-lg" required autocomplete="new-password">
                                <button type="button" class="btn btn-outline-secondary toggle-senha" data-target="nova_senha" aria-label="Mostrar nova senha">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        {{-- CONFIRMAR NOVA SENHA --}}
                        <div class="mb-4 position-relative">
                            <label for="nova_senha_confirmation" class="form-label fw-semibold">Confirme a nova senha</label>
                            <div class="input-group">
                                <input type="password" id="nova_senha_confirmation" name="nova_senha_confirmation"
                                    class="form-control form-control-lg" required autocomplete="new-password">
                                <button type="button" class="btn btn-outline-secondary toggle-senha" data-target="nova_senha_confirmation" aria-label="Mostrar confirmar senha">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn botao-pad w-100 btn-lg">
                            Atualizar senha
                        </button>
                    </form>

                    <p class="text-muted text-center mt-4 small">
                        Por segurança, você precisa definir uma nova senha antes de continuar.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
<!-- -------------- Início - Estillos CSS ------------------ -->
<style>
.botao-pad {
    background: linear-gradient(135deg, #183F77, #4A90E2);
    color: #ffffff !important;
}
.botao-pad:hover {
    background: linear-gradient(135deg, #122b55, #3570c2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    font-weight: 600 !important;
}
.input-group .btn {
    border-color: #ced4da;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
}
.icon-eye { font-size: 18px; line-height: 1; }
.toggle-senha.active {
    background-color: rgba(0,0,0,0.05);
}
</style>
<!-- -------------- Final - Estillos CSS ------------------ -->
<!---------------- Início - Scripts JavaScript -------------------->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-senha').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            var targetId = btn.getAttribute('data-target');
            var input = document.getElementById(targetId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                btn.classList.add('active');
                updateButtonIcon(btn, true);
            } else {
                input.type = 'password';
                btn.classList.remove('active');
                updateButtonIcon(btn, false);
            }
        });
    });

    function updateButtonIcon(btn, visible) {
        var fa = btn.querySelector('i');
        if (fa) {
            if (visible) {
                fa.classList.remove('fa-eye');
                fa.classList.add('fa-eye-slash');
            } else {
                fa.classList.remove('fa-eye-slash');
                fa.classList.add('fa-eye');
            }
            return;
        }
        var span = btn.querySelector('.icon-eye');
        if (span) {
            span.textContent = visible ? '🙈' : '👁️';
        }
    }
});
</script>
<!---------------- Final - Scripts JavaScript -------------------->