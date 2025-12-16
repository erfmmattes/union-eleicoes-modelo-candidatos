@extends('layouts.appMasterFront')
@section('title', 'Unir Votações - Comprovante')

@section('content')

@php
    // Evita erro se a view for carregada sem passar dados
    $listaComprovantes = $listaComprovantes ?? collect();
@endphp

<section class="hero">
    <div class="aba-geral">
        <h1>Comprovante de Votação</h1>

        <div class="descricao-ajuda card p-4 shadow-lg card-comprovante">

            {{-- 🔹 Se NÃO existir comprovante --}}
            @if ($listaComprovantes->isEmpty())

                <div class="alert alert-danger text-center">
                    Nenhum comprovante foi encontrado para este eleitor.
                </div>

                <a href="{{ route('loginEleicao.homeLogadoFront') }}" class="btn bt-home-voltar mt-3">
                    Voltar
                </a>

            @else

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- 🔹 Cabeçalho geral (dados do eleitor e votação) --}}
                @php
                    $primeiro = $listaComprovantes->first();
                @endphp

                <div class="linha-info"><strong>Nome da Votação:</strong> {{ $primeiro->nome_votacao }}</div>
                <div class="linha-info"><strong>Nome do Eleitor:</strong> {{ $primeiro->nome_eleitor }}</div>
                <div class="mb-1"><strong>CPF:</strong> {{ formatarCpfCnpj($primeiro->cpf_cnpj) }}</div>

                {{-- 🔹 LOOP DOS COMPROVANTES (um por etapa) --}}
                @foreach ($listaComprovantes as $comp)
                    <div class="card p-3 shadow-sm mb-4 bloco-etapa">

                        <h4 class="fw-bold mb-1">{{ $comp->etapa_nome }}</h4>

                        <div class="text-muted mb-3">
                            Etapa {{ $comp->sequencia }}
                        </div>

                        <div class="mb-1">
                            <strong>IP:</strong>
                            {{ $comp->ip }}
                        </div>

                        <div class="mb-1">
                            <strong>Data e Hora:</strong>
                            {{ \Carbon\Carbon::parse($comp->data_hora)->format('d/m/Y H:i:s') }}
                        </div>

                        <div class="chave mb-1">
                            <strong>Chave de Autenticação:</strong>
                            <span>{{ $comp->chave_autenticacao }}</span>
                        </div>

                    </div>
                @endforeach

                <div class="texto-final">
                    Guarde estas chaves — elas são seus comprovantes oficiais.
                </div>

                {{-- 🔹 BOTÕES FINAIS (somente uma vez) --}}
                <div class="acoes mt-4 d-flex justify-content-center gap-3">

                    <form action="{{ route('comprovante.receberPorEmail') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-acao botao-email">
                            Receber todos por e-mail
                        </button>
                    </form>

                    <form action="{{ route('comprovante.baixarPdfComprovante') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-acao botao-pdf">
                            Baixar PDF completo
                        </button>
                    </form>

                </div>

            @endif
        </div>
    </div>
</section>
@endsection
<!-- -------------- Início - Estilos CSS ------------------ -->
<style>
    .aba-geral {
        margin: 80px 0 35px 0;
    }
    .hero {
        text-align: center;
        padding: 0 2rem;
    }
    .hero h1 {
        color: #000;
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    .card-comprovante {
        width: 60%;
        margin: auto;
        border-radius: 12px;
    }
    .linha-info {
        font-size: 16px;
        margin-bottom: 10px;
        padding-bottom: 6px;
        border-bottom: 1px solid #ececec;
    }
    .chave span {
        font-family: monospace;
        font-size: 18px;
        display: block;
        margin-top: 5px;
        background: #f4f4f4;
        padding: 8px;
        border-radius: 6px;
    }
    .texto-final {
        font-size: 14px;
        text-align: center;
        color: #444;
    }
    .btn-acao {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        color: #fff;
        transition: .2s;
    }
    .bt-home-voltar {
        color: #fff !important;
        transition: .2s !important;
        background: #6c757d !important;
    }
    .bt-home-voltar:hover {
        background: #5c636a !important;
        font-weight: 600 !important;
    }
    .botao-email {
        background: #007bff;
        border: none !important;
        font-weight: 500 !important;
        width: 300px;
    }
    .botao-email:hover {
        background: #0062cc;
        font-weight: 700 !important;
    }
    .botao-pdf {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        border: none !important;
        font-weight: 500 !important;
        width: 300px;
    }
    .botao-pdf:hover {
        background: linear-gradient(135deg, #5c636a, #5c636a);
        font-weight: 700 !important;
    }
    @media (max-width: 992px) {
        .card-comprovante {
            width: 100%;
        }
        .acoes {
            flex-direction: column;
        }
    }
</style>
<!-- -------------- Final - Estilos CSS ------------------ -->
<!---------------- Início - Scripts JavaScript -------------------->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-temporaria');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>
<!---------------- Final - Scripts JavaScript -------------------->