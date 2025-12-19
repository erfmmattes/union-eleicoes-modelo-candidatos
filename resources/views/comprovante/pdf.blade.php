<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Unir Votações - Comprovante de Votação</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ public_path('img/icon-unir/unir-votacoes.png') }}">

    <style>
        @page {
            margin: 100px 40px 60px 40px;
        }
        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 70px;
            text-align: center;
            border-bottom: 3px solid #183F77;
        }
        header img {
            width: 160px;
        }
        header h2 {
            color: #183F77;
            font-size: 18px;
            margin: 2px 0 0 0;
            text-transform: uppercase;
        }
        footer {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            height: 40px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .titulo-centralizado {
            text-align: center;
        }
        .linha {
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 1px solid #ccc;
            font-size: 13px;
            text-align: center;
        }
        .etapa-bloco {
            margin-top: 25px;
            padding: 15px;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            background: #fafafa;
        }
        .etapa-bloco h3 {
            margin: 0 0 8px;
            font-size: 15px;
            color: #183F77;
        }
        .chave {
            background: #f2f2f2;
            padding: 12px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 13px;
            word-break: break-all;
        }
        .label {
            font-weight: bold;
        }
        .mt-30 {
            margin-top: 30px;
        }
    </style>
</head>

<body>

<header>
    <div>
        <img src="{{ public_path('img/logotipo-unir/unir-votacoes.png') }}" alt="Unir Votações">

        @if(isset($configuracao) && !empty($configuracao->caminho))
            <img src="{{ public_path('storage/' . $configuracao->caminho) }}" class="l-logo" alt="{{ $configuracao->nome_cliente }}">
        @endif
    </div>

    <h2 style="margin-top: 30px;">Comprovante de Votação</h2>
</header>

<footer>
    Unir Votações © {{ date('Y') }} —
    Página <span class="page-number"></span> —
    Emitido em {{ now()->format('d/m/Y') }} às {{ now()->format('H:i:s') }} (Horário de Brasília)
</footer>

@php
    // Garante sempre uma Collection
    $listaComprovantes = collect($listaComprovantes ?? []);

    // Primeiro item (ou null)
    $cabecalho = $listaComprovantes->first();
@endphp

<main style="margin-top: 40px;">

    {{-- 🔹 Se não houver comprovantes --}}
    @if($listaComprovantes->isEmpty())

        <div class="linha">
            <strong>Nenhum comprovante encontrado.</strong>
            <p>Não existem registros de votação para este eleitor.</p>
        </div>

    @else

        {{-- 🔹 Dados gerais do eleitor --}}
        <div class="linha">
            <span class="label">Nome da Votação:</span> {{ $cabecalho->nome_votacao ?? '' }}
        </div>

        <div class="linha">
            <span class="label">Nome do Eleitor:</span> {{ $cabecalho->nome_eleitor ?? '' }}
        </div>

        <div class="linha">
            <span class="label">CPF:</span>
            {{ isset($cabecalho) ? formatarCpfCnpj($cabecalho->cpf_cnpj) : '' }}
        </div>

        {{-- 🔹 Loop das Etapas --}}
        @foreach($listaComprovantes as $comp)
            <div class="etapa-bloco">

                <h3 class="titulo-centralizado">{{ $comp->etapa_nome }}</h3>

                <div class="linha">
                    Etapa {{ $comp->sequencia }}
                </div>

                <div class="linha">
                    <span class="label">IP:</span> {{ $comp->ip }}
                </div>

                <div class="linha">
                    <span class="label">Data/Hora da Votação:</span>
                    {{ \Carbon\Carbon::parse($comp->data_hora)->format('d/m/Y H:i:s') }}
                </div>

                <div class="linha">
                    <span class="label">Chave de Autenticação:</span>
                    <p class="chave">{{ $comp->chave_autenticacao }}</p>
                </div>

            </div>
        @endforeach

    @endif

</main>

</body>
</html>