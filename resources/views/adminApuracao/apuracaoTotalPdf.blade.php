<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Unir Votações - Apuração da Etapa</title>

    <link rel="icon" type="image/png" sizes="32x32"
          href="{{ public_path('img/icon-unir/unir-votacoes.png') }}">

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
            width: 140px;
            margin: 0 10px;
        }
        header h2 {
            color: #183F77;
            font-size: 18px;
            margin: 35px 0 0 0;
        }
        h2 {
            border-bottom: 1px solid #ddd;
            color: #183F77;
            font-size: 20px;
            margin: 5px 0 0 0;
            text-align: left;
        }
        h3 {
            color: #183F77;
            font-size: 14px;
            margin: 5px 0 0 0;
            text-align: left;
        }
        h4 {
            border-bottom: 1px solid #ddd;
            color: #183F77;
            font-size: 17px;
            margin: 5px 0 0 0;
            text-align: left;
        }
        h5 {
            border-bottom: 1px solid #ddd;
            color: #666;
            font-size: 13px;
            margin: 5px 0 0 0;
            text-align: left;
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
            font-size: 11px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            font-size: 11px;
        }
        th {
            background: #183F77;
            color: #fff;
            text-align: left;
        }
        th.text-center,
        td.text-center {
            text-align: center;
        }
        tr:nth-child(even) {
            background: #f7f9fc;
        }
        .total-box {
            margin-top: 20px;
            border: 1px solid #ccc;
            background: #f7f9fc;
            padding: 10px;
        }
        .total-label {
            font-size: 10px;
            color: #666;
        }
        .total-value {
            font-size: 18px;
            font-weight: bold;
            color: #183F77;
        }
        .page-number:before {
            content: counter(page);
        }
        .altura-dados-comissao {
            margin-top: 250px;
        }
        .tabela-comissao th {
            background: #ffffff;
            color: #000000;
        }
        .tabela-comissao tr:nth-child(even) {
            background: #f0f0f0;
        }
    </style>
</head>
<body>

<header>
    <img src="{{ public_path('img/logotipo-unir/unir-votacoes.png') }}" alt="Unir Votações">
    @if(!empty($configuracao['caminho']))
        <img src="{{ public_path('storage/' . $configuracao['caminho']) }}" class="l-logo" alt="{{ $configuracao['razao_social'] }}">
    @endif
    
</header>

<footer>
    Unir Votações © {{ date('Y') }} —
    Página <span class="page-number"></span> —
    Emitido em {{ now()->format('d/m/Y H:i:s') }}
</footer>

<main>

    <h2>Relatório de Apuração - Unir Votações</h2>
    <h4>{{ $configuracao['razao_social'] }}</h4>
    <h4>{{ $configuracao['nome_eleicao'] }}</h4>
    <h5>Data e Hora Inicial da Votação: {{ \Carbon\Carbon::parse($configuracao['data_hora_inicio_eleicao'])->format('d/m/Y H:i') }}</h5>
    <h5>Data e Hora Final da Votação: {{ \Carbon\Carbon::parse($configuracao['data_hora_final_eleicao'])->format('d/m/Y H:i') }}</h5>
    <h3>Apuração da Etapa {{ $etapa->sequencia }} - {{ $etapa->nome }}</h3>

    <!-- Tabela -->
    <table>
        <thead>
            <tr>
                <th>Candidato</th>
                <th class="text-center">Votos</th>
                <th class="text-center">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($apuracao as $item)
                <tr>
                    <td>{{ $item['candidato'] }}</td>
                    <td class="text-center">{{ $item['quantidade'] }}</td>
                    <td class="text-center">{{ $item['percentual'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total -->
    <div class="total-box">
        <div class="total-label">Total de votos computados</div>
        <div class="total-value">
            {{ $apuracao->sum('quantidade') }}
        </div>
    </div>

    @if($configuracao['dados_da_comissao'] === 1)

        <h4 class="altura-dados-comissao">Comissão Eleitoral</h4>

        <table>
            <thead class="tabela-comissao">
                <tr>
                    <th>Função</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>E-mail</th>
                    <th>Assinatura</th>
                </tr>
            </thead>
            <tbody>
                {{-- Presidente --}}
                <tr>
                    <td>Presidente</td>
                    <td>{{ $configuracao['nome_presidente'] }}</td>
                    <td>{{ formatarCpfCnpj($configuracao['cpf_presidente']) }}</td>
                    <td>{{ $configuracao['email_presidente'] }}</td>
                    <td></td>
                </tr>

                {{-- Membro 1 --}}
                <tr>
                    <td>Membro</td>
                    <td>{{ $configuracao['nome_mebro_1'] }}</td>
                    <td>{{ formatarCpfCnpj($configuracao['cpf_mebro_1']) }}</td>
                    <td>{{ $configuracao['email_mebro_1'] }}</td>
                    <td></td>
                </tr>

                {{-- Membro 2 --}}
                <tr>
                    <td>Membro</td>
                    <td>{{ $configuracao['nome_mebro_2'] }}</td>
                    <td>{{ formatarCpfCnpj($configuracao['cpf_mebro_2']) }}</td>
                    <td>{{ $configuracao['email_mebro_2'] }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

    @endif

</main>

</body>
</html>