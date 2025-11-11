@extends('layouts.appMasterFront')
@section('title', 'Union Eleições - Recuperar Senha')
@section('content')
<section class="hero">
    <div class="aba-geral">
        <h1>Recuperar senha</h1>

        <div class="descricao-ajuda card p-4">

            @if(session('success'))
                <div class="alert alert-success alert-temporaria">{{ session('success') }}</div>
            @endif

            <form action="{{ route('recuperarSenha.buscar') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="cpf" class="form-label">Digite seu CPF</label>
                    <input type="text" name="cpf" id="cpf" class="form-control" placeholder="000.000.000-00" required maxlength="14">
                </div>
                <button type="submit" class="btn btn-hero w-100">Buscar</button>
            </form>

            @if(session('email'))
                <div class="card shadow-sm p-1 mt-1 border-0" style="max-width: 500px; margin: auto;">
                    <div class="mb-3 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="{{ $dados['configuracoes']->cor_principal }}" class="bi bi-envelope-fill mb-2" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2.002 2h11.996a2 2 0 0 1 1.951 1.555L8 8.414.05 3.555z"/>
                            <path d="M0 4.697v7.104l5.803-3.551L0 4.697zM6.761 8.83l-6.76 4.145A2 2 0 0 0 2.002 14h11.996a2 2 0 0 0 1.998-1.025l-6.76-4.145L8 9.586l-1.239-.756zM16 4.697l-5.803 3.556L16 11.801V4.697z"/>
                        </svg>
                        <h5 class="card-title mt-2"><strong>Email associado:</strong></h5>
                        <p class="card-text text-muted mb-0">{{ mascarar_email(session('email')) }}</p>
                    </div>

                    <form action="{{ route('recuperarSenha.enviar') }}" method="POST" class="text-center mt-3">
                        @csrf
                        <button type="submit" class="btn btn-en btn-lg env-nova-senha">
                            Enviar nova senha
                        </button>
                    </form>
                </div>
            @endif

            <div class="card shadow-sm p-1 mt-1 border-0 alert-temporaria" id="cpf-error" style="max-width: 500px; margin: auto; display: none;">
                <div class="mb-3 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#FF4D4F" class="bi bi-x-circle-fill mb-2" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.646 4.646a.5.5 0 0 0 0 .708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646a.5.5 0 0 0-.708 0z"/>
                    </svg>
                    <h5 class="card-title mt-2"><strong>CPF inválido</strong></h5>
                </div>
            </div>

            @if(session('error'))
                <div class="card shadow-sm p-1 mt-1 border-0 alert-temporaria" style="max-width: 500px; margin: auto;">
                    <div class="mb-3 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#FF4D4F" class="bi bi-exclamation-triangle-fill mb-2" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1 1 0 0 0-1.964 0L.165 13.233c-.457.778.091 1.767.982 1.767h13.706c.89 0 1.439-.99.982-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1-2.002 0 1 1 0 0 1 2.002 0z"/>
                        </svg>
                        <h5 class="card-title mt-2"><strong>CPF não encontrado</strong></h5>
                    </div>
                </div>
            @endif
            
        </div>

    </div>
</section>
@endsection

<!-- -------------- Início - Estillos CSS ------------------ -->
<style>
    .aba-geral {
        margin: 100px 0px 35px 0px;
    }
    .medida-logo-cliente {
        width: 150px;;
    }
    /* ---------- HERO ---------- */
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
    .hero p {
      color: #000000;
      font-size: 1.2rem;
      margin-bottom: 2rem;
    }
    .hero .btn-hero {
        display: block;
        width: 100%;
        max-width: 525px;
        margin: 0 auto;
    }
    .hero .btn-hero {
        color: #ffffff;
        font-weight: 500;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        background-color: {{ $dados['configuracoes']->cor_principal }}; /* Cor do botão para trocar do cliente */
    }
    .hero .btn-hero:hover {
        color: #ffffff;
        font-weight: bold;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        background-color: {{ $dados['configuracoes']->cor_hover }}; /* Cor ao passar o mouse para trocar do cliente */
    }
    .btn-en {
        color: #ffffff !important;
        transition: background-color 0.3s ease !important;
        background-color: {{ $dados['configuracoes']->cor_principal }} !important; /* Cor do botão para trocar do cliente */
    }
    .btn-en:hover {
        color: #ffffff;
        font-weight: bold;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        background-color: {{ $dados['configuracoes']->cor_hover }} !important; /* Cor ao passar o mouse para trocar do cliente */
    }
    .descricao-ajuda {
        font-size: 16px !important;
        margin: auto;
        text-align: justify;
        width: 50%;
    }
    .env-nova-senha {
        border-radius: 8px; 
        font-size: 16px !important;
        padding: 5px 10px;
    }
    /* ---------- MOBILE ---------- */
    @media (max-width: 992px) {
        .descricao-ajuda {
            width: 100%;
        }
    }
</style>
<!---------------- Final - Estillos CSS -------------------->
<!---------------- Início - Scripts JavaScript e Jquery ------------------ -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cpfInput = document.getElementById('cpf');
    const cpfError = document.getElementById('cpf-error');

    // Função para validar CPF
    function validarCPF(strCPF) {
        strCPF = strCPF.replace(/\D/g, ''); // Remove não números
        if (strCPF.length !== 11 || /^(\d)\1{10}$/.test(strCPF)) return false;

        let sum = 0, remainder;

        for (let i = 1; i <= 9; i++)
            sum += parseInt(strCPF.substring(i-1, i)) * (11 - i);

        remainder = (sum * 10) % 11;
        if (remainder === 10 || remainder === 11) remainder = 0;
        if (remainder !== parseInt(strCPF.substring(9, 10))) return false;

        sum = 0;
        for (let i = 1; i <= 10; i++)
            sum += parseInt(strCPF.substring(i-1, i)) * (12 - i);

        remainder = (sum * 10) % 11;
        if (remainder === 10 || remainder === 11) remainder = 0;
        if (remainder !== parseInt(strCPF.substring(10, 11))) return false;

        return true;
    }

    // Máscara e validação
    cpfInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');

        if (value.length > 3 && value.length <= 6)
            value = value.replace(/(\d{3})(\d+)/, '$1.$2');
        else if (value.length > 6 && value.length <= 9)
            value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
        else if (value.length > 9)
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d+)/, '$1.$2.$3-$4');

        e.target.value = value;

        // Validar CPF completo
        if (value.replace(/\D/g, '').length === 11) {
            if (validarCPF(value)) {
                cpfError.style.display = 'none';
            } else {
                cpfError.style.display = 'block';
            }
        } else {
            cpfError.style.display = 'none';
        }
    });
});
</script>
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
    document.addEventListener('DOMContentLoaded', () => {
        const cpfErrorCard = document.getElementById('cpf-error');
        const cpfInput = document.getElementById('cpf');

        // Observa mudanças no atributo 'style' do card
        const observer = new MutationObserver(() => {
            if (cpfErrorCard.style.display !== 'none') {
                cpfInput.value = '';
            }
        });

        observer.observe(cpfErrorCard, { attributes: true, attributeFilter: ['style'] });
    });
</script>
<!---------------- Final - Scripts JavaScript e Jquery -------------------->