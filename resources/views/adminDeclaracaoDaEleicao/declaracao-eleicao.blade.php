<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Unir Votações - Declaração da Eleição</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ public_path('img/icon-union/union-eleicoes.png') }}">
    <style>
        @page {
            margin: 100px 40px 60px 40px;
        }

        /* HEADER FIXO */
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

        /* FOOTER FIXO */
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
            line-height: 1.6;
        }

        main {
            margin-top: 40px;
            text-align: justify;
        }

        h1 {
            text-align: center;
            color: #183F77;
            font-size: 22px;
            margin-bottom: 30px;
        }

        .dados {
            margin: 30px 0;
        }

        .dados p {
            margin: 6px 0;
        }

        .assinatura {
            margin-top: 80px;
            text-align: center;
        }

        .assinatura p {
            border-top: 1px solid #000;
            width: 300px;
            margin: 0 auto;
            padding-top: 10px;
        }

        .page-number:before {
            content: counter(page);
        }

        .logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
        }

        .logos img {
            width: 80px;
        }
    </style>
</head>
<body>

    <!-- HEADER FIXO -->
    <header>
        <div class="logos">
            <img src="{{ public_path('img/logotipo-union/union-eleicoes.png') }}" alt="Unir Votações">
            @if(!empty($dados['caminho']))
                <img src="{{ public_path('storage/' . $dados['caminho']) }}" class="l-logo" alt="{{ $dados['razao_social'] }}">
            @endif
        </div>
        <h2>Declaração da Eleição</h2>
    </header>

    <!-- FOOTER FIXO -->
    <footer>
        Unir Votações © {{ date('Y') }} —
        Página <span class="page-number"></span> —
        Emitido em {{ now()->format('d/m/Y') }} às {{ now()->format('H:i') }} (Horário de Brasília)
    </footer>

    <!-- CONTEÚDO -->
    <main>
        <p>Declaramos, para os devidos fins, que a eleição abaixo foi organizada e conduzida pela plataforma <strong>Unir Votações</strong>, garantindo sigilo, integridade e autenticidade dos votos, conforme parâmetros técnicos e jurídicos vigentes.</p>

        <div class="dados">
            <p><strong>Cliente:</strong> {{ $dados['razao_social'] }}</p>
            <p><strong>CNPJ:</strong> {{ formatarCpfCnpj($dados['cnpj']) }}</p>
            <p><strong>Título da Eleição:</strong> {{ $dados['nome_eleicao'] }}</p>
            <p><strong>Período:</strong> {{ $dados['data_hora_inicio_eleicao'] ? \Carbon\Carbon::parse($dados['data_hora_inicio_eleicao'])->format('d/m/Y H:i') : '—' }} até {{ $dados['data_hora_final_eleicao'] ? \Carbon\Carbon::parse($dados['data_hora_final_eleicao'])->format('d/m/Y H:i') : '—' }}</p>
            <p><strong>Total de Votantes:</strong> {{ $dados['total_votantes'] }}</p>
            <p><strong>Data de Geração:</strong> {{ $dados['data_geracao'] }}</p>
        </div>

        <p>Este documento tem validade para comprovação da realização do processo eleitoral eletrônico, conforme registros oficiais armazenados na base de dados da Unir Votações.</p>

        <div class="assinatura">
            <p>Responsável pela Eleição</p>
        </div>
    </main>

</body>
</html>