<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Unir Votações - Relatório de Zerésima</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ public_path('img/icon-union/union-eleicoes.png') }}">
    <style>
        @page { margin: 100px 40px 60px 40px; }
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
            margin: 5px 0 0 0;
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
            font-size: 11px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: center;
            font-size: 12px;
        }
        th {
            background: #183F77;
            color: #fff;
            text-transform: uppercase;
        }
        .status-valido {
            color: #2e7d32;
            font-weight: bold;
        }
        .status-invalido {
            color: #c62828;
            font-weight: bold;
        }
        .resumo {
            margin-top: 40px;
            font-size: 13px;
            text-align: center;
        }
        .page-number:before {
            content: counter(page);
        }
        .l-logo {
            width: 80px;
        }
    </style>
</head>
<body>

<header>
     <div class="d-flex">
        <img src="{{ public_path('img/logotipo-union/union-eleicoes.png') }}" class="l-logo" alt="Unir Votações">
        <img src="{{ public_path('storage/' . $configuracao->caminho) }}" class="l-logo" alt="{{ $configuracao->nome_cliente }}">
    </div>
    <h2>Relatório de Zerésima</h2>
</header>

<footer>
    Unir Votações © {{ date('Y') }} —
    Página <span class="page-number"></span> —
    Emitido em {{ now()->format('d/m/Y H:i:s') }}
</footer>

<main>
    <table>
        <thead>
            <tr>
                <th>Data/Hora de Emissão</th>
                <th>Total de Votos</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ now()->format('d/m/Y H:i:s') }}</td>
                <td>{{ $dados['total_votos'] }}</td>
                <td class="{{ $dados['total_votos'] === 0 ? 'status-valido' : 'status-invalido' }}">
                    {{ $dados['status'] }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="resumo">
        <p>
            Este documento certifica que, no momento da geração desta Zerésima,
            <strong>{{ $dados['total_votos'] === 0 ? 'não havia votos computados' : 'já havia votos registrados' }}</strong>
            na urna eletrônica.
        </p>
        <p>
            A Zerésima é um relatório oficial que deve ser emitido antes do início da votação,
            garantindo a transparência e integridade do processo eleitoral.
        </p>
    </div>
</main>

</body>
</html>