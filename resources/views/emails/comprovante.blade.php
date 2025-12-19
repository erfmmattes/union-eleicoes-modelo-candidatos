<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Unir Votações - Comprovante de Votação</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f7;
            margin: 0;
            padding: 25px;
        }
        .container {
            background: #ffffff;
            max-width: 650px;
            margin: 0 auto;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .header {
            padding: 25px;
            text-align: center;
        }
        .header img {
            width: 120px;
        }
        .content {
            padding: 30px 35px;
            color: #2d2d2d;
        }
        .content h2 {
            margin-top: 0;
            margin-bottom: 15px;
            text-align: center;
            color: #111827;
        }
        .content p {
            line-height: 1.6;
            text-align: center;
            color: #444;
        }
        .titulo-centralizado {
            text-align: center;
        }
        .info-box {
            background: #f6f6f6;
            padding: 18px;
            border-radius: 10px;
            margin: 18px 0;
            font-size: 0.95rem;
        }
        .info-box p {
            margin: 6px 0;
        }
        .chave {
            background: #e5e7eb;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin-top: 10px;
            font-size: 1rem;
            font-family: monospace;
        }
        .footer {
            background: #f4f4f7;
            padding: 18px 25px;
            text-align: center;
            color: #777;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- Cabeçalho -->
        <div class="header">
            <img src="{{ asset('img/logotipo-unir/unir-votacoes.png') }}" alt="Unir Votações">
        </div>

        @php
            $listaComprovantes = collect($listaComprovantes);
        @endphp

        @if ($listaComprovantes->isEmpty())
            <div class="content">
                <h2>Nenhum comprovante encontrado</h2>
                <p>Não existem registros de votação para este eleitor.</p>
            </div>
        @else

            @php $cabecalho = $listaComprovantes->first(); @endphp

            <div class="content">

                <h2>Olá, {{ $cabecalho->nome_eleitor }}!</h2>
                <p>Seu comprovante de votação foi gerado com sucesso. Guarde estas informações com segurança.</p>

                <!-- Dados Gerais -->
                <div class="info-box">
                    <p><strong>Nome da Votação:</strong> {{ $cabecalho->nome_votacao }}</p>
                    <p><strong>Nome do Eleitor:</strong> {{ $cabecalho->nome_eleitor }}</p>
                    <p><strong>CPF:</strong> {{ formatarCpfCnpj($cabecalho->cpf_cnpj) }}</p>
                </div>

                <!-- Lista das etapas -->
                @foreach ($listaComprovantes as $comp)
                    <div class="info-box">
                        <p><h5 class="fw-bold titulo-centralizado mb-1">{{ $comp->etapa_nome }}</h5></p>
                        <p>Etapa {{ $comp->sequencia }}</p>
                        <p><strong>IP:</strong> {{ $comp->ip }}</p>
                        <p><strong>Data e Hora:</strong>
                            {{ \Carbon\Carbon::parse($comp->data_hora)->format('d/m/Y H:i:s') }}
                        </p>

                        <div class="chave">
                            <strong>Chave de Autenticação:</strong><br>
                            {{ $comp->chave_autenticacao }}
                        </div>
                    </div>
                @endforeach

            </div>

        @endif

        <div class="footer">
            Atenciosamente, <br>
            <strong>Equipe Unir Votações</strong>
        </div>
    </div>
</body>
</html>
