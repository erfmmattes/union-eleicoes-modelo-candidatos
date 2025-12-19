@extends('layouts.appMasterFront')
@section('title', 'Unir Votações - Home')
@section('content')
<section class="hero">
    {{-- Logo Cliente --}}
    <div class="logo-cliente">
        @if(!empty($configuracao?->caminho))
            <img src="{{ asset('storage/' . $configuracao->caminho) }}" 
                alt="Logo {{ $configuracao->nome_eleicao }}" 
                class="medida-logo-cliente">
        @else
            <img src="{{ asset('img/logotipo-unir/unir-votacoes.png') }}" 
                alt="Logo Unir Votações" 
                class="medida-logo-cliente">
        @endif
    </div>
    <div>
      <h1>Plataforma de Votação Online</h1>
      <p>{{ $configuracao->nome_eleicao }}<br>
          @php
            use Carbon\Carbon;

            $inicio = Carbon::parse($configuracao->data_hora_inicio_eleicao);
            $fim = Carbon::parse($configuracao->data_hora_final_eleicao);
          @endphp

          @if ($inicio->isSameDay($fim))
            Período: {{ $inicio->format('d/m/Y') }}, das {{ $inicio->format('H:i') }} às {{ $fim->format('H:i') }} (Horário de Brasília)
          @else
            Período: de {{ $inicio->format('d/m/Y') }} às {{ $inicio->format('H:i') }} 
            até {{ $fim->format('d/m/Y') }} às {{ $fim->format('H:i') }} (Horário de Brasília)
          @endif
      </p>
        @if($dados['configuracoes'])
            <div class="d-flex flex-column align-items-center" id="votacao-container">
                <div class="text-center lar-mobile" id="temporizador">
                    <div class="tit-ele">
                        Eleição inicia em:
                    </div>
                    <div class="card text-white temporizador-fundo temporizador-card">
                        <div id="dias" class="fs-3 fw-bold">0</div>
                        <div class="small">Dias</div>
                    </div>
                    <div class="card text-white temporizador-fundo temporizador-card">
                        <div id="horas" class="fs-3 fw-bold">0</div>
                        <div class="small">Horas</div>
                    </div>
                    <div class="card text-white temporizador-fundo temporizador-card">
                        <div id="minutos" class="fs-3 fw-bold">0</div>
                        <div class="small">Minutos</div>
                    </div>
                    <div class="card text-white temporizador-fundo temporizador-card">
                        <div id="segundos" class="fs-3 fw-bold">0</div>
                        <div class="small">Segundos</div>
                    </div>
                </div>

                <a href="{{ route('loginEleicao.index') }}" id="botao-votar" class="btn btn-lg btn-hero" style="display:none;">
                    Entrar na Eleição
                </a>

                <a id="eleicao-encerrada" class="btn btn-lg btn-hero" title="A eleição foi encerrada" disebled>
                    Eleição Encerrada
                </a>
            </div>

            <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
            <script>
                const inicioEleicao = new Date("{{ \Carbon\Carbon::parse($dados['configuracoes']->data_hora_inicio_eleicao)->format('Y-m-d H:i:00') }}").getTime();
                const fimEleicao = new Date("{{ \Carbon\Carbon::parse($dados['configuracoes']->data_hora_final_eleicao)->format('Y-m-d H:i:00') }}").getTime();

                function atualizarContador() {
                    $.ajax({
                        url: "{{ route('hora.servidor') }}", // deve retornar JSON: {hora: "2025-10-03 20:38:51"}
                        method: "GET",
                        success: function(response) {
                            const agora = new Date(response.hora).getTime();
                            let distancia = 0;

                            if (agora < inicioEleicao) {
                                // Antes do início da eleição
                                $('#botao-votar').hide();
                                $('#temporizador').show();
                                $('#eleicao-encerrada').hide();
                                distancia = inicioEleicao - agora;
                                atualizarTemporizador(distancia);
                            } else if (agora >= inicioEleicao && agora <= fimEleicao) {
                                // Durante a eleição
                                $('#botao-votar').show();
                                $('#temporizador').hide();
                                $('#eleicao-encerrada').hide();
                            } else {
                                // Após o fim da eleição
                                $('#botao-votar').hide();
                                $('#temporizador').hide();
                                $('#eleicao-encerrada').show();
                            }
                        },
                        error: function() {
                            console.error('Erro ao buscar hora do servidor');
                        }
                    });
                }

                function atualizarTemporizador(distancia) {
                    const dias = Math.floor(distancia / (1000 * 60 * 60 * 24));
                    const horas = Math.floor((distancia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutos = Math.floor((distancia % (1000 * 60 * 60)) / (1000 * 60));
                    const segundos = Math.floor((distancia % (1000 * 60)) / 1000);

                    $('#dias').text(dias);
                    $('#horas').text(horas);
                    $('#minutos').text(minutos);
                    $('#segundos').text(segundos);
                }

                // Atualiza a cada segundo via AJAX
                setInterval(atualizarContador, 1000);
                atualizarContador();
            </script>
        @endif
    </div>
</section>
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
    /* ---------- MOBILE ---------- */
    @media (max-width: 992px) {
        .lar-mobile {
            min-width: 375px;
            width: 100%;
        }
    }
</style>
<!-- -------------- Final - Estillos CSS ------------------ -->