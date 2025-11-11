@extends('layouts.appMasterFront')
@section('title', 'Union Eleições - Home')
@section('content')

@if(session('front_logado'))
<section class="hero d-flex align-items-center justify-content-center mt-5">
    <div class="card shadow-lg border-0 rounded-4 p-4 lar-mobile" style="max-width: 500px;">
        <div class="text-center">
            <h1 class="h4 fw-bold text-dark">Bem-vindo, {{ session('eleitor_nome') }}!</h1>
            <p class="text-muted">Você está logado na plataforma de votação online.</p>
        </div>
        <div id="acaoVoto">
    <a id="btnVotar" href="#" class="btn btn-hero btn-lg w-100">Votar</a>
</div>
    </div>
</section>
@endif

@endsection
<!-- -------------- Início - Estillos CSS ------------------ -->
<style>
    .logo-cliente {
        margin: 135px 0px 35px 0px;
    }
    .medida-logo-cliente {
        width: 150px;
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
      font-size: 30px;
      font-weight: 700;
      margin-bottom: 1rem;
    }
    .hero p {
      color: #000000;
      font-size: 1.2rem;
      margin-bottom: 2rem;
      /* text-transform: uppercase; */
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
    .temporizador-fundo {
        height: 80px;
        padding: 4px;
        width: 80px;
        background-color: {{ $dados['configuracoes']->cor_principal }} !important;
    }
    .temporizador-card {
        display: inline-block !important; 
        margin: 0 5px;         
        width: 80px;           
        text-align: center;   
    }
    .tit-ele {
      color: #000000;
      font-size: 16px !important;
      font-weight: 500 !important;
      margin-bottom: 10px;
    }
    #eleicao-encerrada {
        pointer-events: none; 
        opacity: 0.6;
        
    }
    .lar-mobile {
        min-width: 375px;
        width: 100%;
    }
    /* ---------- MOBILE ---------- */
    @media (max-width: 992px) {
        .lar-mobile {
            min-width: 375px;
            width: 100%;
        }
    }
</style>
<!-- -------------- Final - Estillos CSS ------------------ -->
<!---------------- Início - Script ------------------>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const eleitorId = "{{ session('eleitor_id') }}";
    const acaoVoto = document.getElementById('acaoVoto');

    if (!eleitorId || !acaoVoto) return;
    function atualizarBotao() {
        fetch(`{{ route('loginEleicao.dadosEleitor') }}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Erro:', data.error);
                    return;
                }

                console.log('Dados do eleitor:', data);

                if (data.votou) {
                    acaoVoto.innerHTML = `
                        <a href="{{ route('loginEleicao.homeLogadoFront') }}" class="btn btn-hero btn-lg w-100">
                            Visualizar Comprovante
                        </a>
                    `;
                } else {
                    acaoVoto.innerHTML = `
                        <a href="{{ route('loginEleicao.homeLogadoFront') }}" class="btn btn-hero btn-lg w-100">
                            Votar
                        </a>
                    `;
                }
            })
            .catch(error => console.error('Erro ao buscar dados:', error));
    }
    atualizarBotao();
    setInterval(atualizarBotao, 5000);
});
</script>
<!---------------- Final - Script ------------------>