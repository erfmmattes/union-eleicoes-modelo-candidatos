@extends('layouts.appMasterFront')
@section('title', 'Unir Votações - Login')

@section('content')
<section class="hero d-flex align-items-center justify-content-center mt-5">
    <div class="card shadow-lg border-0 rounded-4 p-4 lar-mobile" style="max-width: 420px;">
        <div class="text-center mb-1">
            <h1 class="h4 fw-bold text-dark">Acesse sua votação</h1>
            <p class="text-muted">Insira seu CPF e senha para entrar</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-temporaria">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-temporaria">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('loginEleicao.authDois') }}">
            @csrf

            <div class="mb-3 text-start">
                <label for="cpf" class="form-label fw-semibold">CPF</label>
                <div class="input-group">
                    <input type="text" id="cpf" name="cpf" class="form-control form-control-lg" placeholder="000.000.000-00" maxlength="14" required>
                </div>
            </div>

            <div class="mb-4 text-start">
                <label for="password" class="form-label fw-semibold">Senha</label>
                <div class="input-group">
                    <input type="password" id="password" name="senha" class="form-control form-control-lg" placeholder="Digite sua senha" required>
                    <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-hero btn-lg w-100">
                Entrar
            </button>
        </form>

        <p class="text-muted text-center mt-2 small">
            Acesso exclusivo para eleitores autorizados.
        </p>
    </div>
</section>
@endsection
<!-- -------------- Início - Estilos CSS ------------------ -->
<style>
.hero {
  justify-content: center;
  align-items: center;
  text-align: center;
  padding: 0 2rem;
  color: #fff;
}
.hero h1 {
  color: #000000;
  font-size: 30px;
  font-weight: 700;
  margin-bottom: 1rem;
}
.hero .btn-hero {
  display: block;
  width: 100%;
  max-width: 525px;
  margin: 0 auto;
  color: #ffffff !important;
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
.lar-mobile {
  min-width: 375px;
  width: 100%;
}
</style>
<!-- -------------- Final - Estilos CSS ------------------ -->
<!---------------- Início - Script Mostrar/Ocultar Senha ------------------>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-temporaria');

    alerts.forEach(alert => {
        // Define o tempo em milissegundos (ex: 5 segundos)
        setTimeout(() => {
            // Anima o fade out
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            // Remove do DOM depois da animação
            setTimeout(() => alert.remove(), 500);
        }, 5000); // 5000ms = 5 segundos
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('cpf');
  if (!input) return;

  function formatCpf(value) {
    const digits = value.replace(/\D/g, ''); // remove tudo que não é número
    return digits
      .replace(/^(\d{3})(\d)/, '$1.$2')       // 000.
      .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3') // 000.000.
      .replace(/\.(\d{3})(\d)/, '.$1-$2')     // 000.000.000-
      .slice(0, 14);                           // limita a 14 caracteres (com pontos e traço)
  }

  function applyMaskAndKeepCaret(e) {
    const start = input.selectionStart;
    const oldValue = input.value;
    const oldLen = oldValue.length;

    const newValue = formatCpf(oldValue);
    input.value = newValue;

    const newLen = newValue.length;
    let newPos = start + (newLen - oldLen);

    if (newPos < 0) newPos = 0;
    if (newPos > newLen) newPos = newLen;

    try {
      input.setSelectionRange(newPos, newPos);
    } catch (err) {}
  }

  input.addEventListener('input', applyMaskAndKeepCaret);
  input.addEventListener('paste', function() {
    setTimeout(() => applyMaskAndKeepCaret(), 0);
  });

  // Formata valor inicial (modo edição)
  if (input.value) {
    input.value = formatCpf(input.value);
  }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Alterna o ícone
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
});
</script>
<!---------------- Final - Script Mostrar/Ocultar Senha ------------------>