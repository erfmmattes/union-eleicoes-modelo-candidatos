<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Union Eleições - Eleitores Logados</title>
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
            font-size: 11px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #183F77;
            color: #fff;
            font-weight: bold;
            font-size: 11px;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #ccc;
        }

        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background: #f7f9fc;
        }

        .page-number:before {
            content: counter(page);
        }

        .l-logo {
            width: 120px;
        }
    </style>
</head>
<body>

    <!-- CABEÇALHO FIXO -->
    <header>
        <div class="d-flex">
            <img src="{{ public_path('img/logotipo-union/union-eleicoes.png') }}" class="l-logo" alt="Union Eleições">
            @if(isset($configuracao) && !empty($configuracao->caminho))
                <img src="{{ public_path('storage/' . $configuracao->caminho) }}" class="l-logo" alt="{{ $configuracao->nome_cliente }}">
            @endif
        </div>
        <h2>Eleitores Logados</h2>
    </header>

    <!-- RODAPÉ FIXO -->
    <footer>
        Union Eleições © {{ date('Y') }} — 
        Página <span class="page-number"></span> —
        Emitido em {{ now()->format('d/m/Y') }} às {{ now()->format('H:i:s') }} (Horário de Brasília)
    </footer>

    <!-- CONTEÚDO -->
    <main>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF/CNPJ</th>
                    <th>E-mail</th>
                    <th>Celular</th>
                    <th>IP</th>
                    <th>Data e Horário de Login</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($eleitoresLogados as $eleitorLogado)
                    <tr>
                        <td>{{ $eleitorLogado->eleitore_logado_id }}</td>
                        <td>{{ $eleitorLogado->nome }}</td>
                        <td>{{ formatarCpfCnpj($eleitorLogado->cpf_cnpj) }}</td>
                        <td>{{ $eleitorLogado->email }}</td>
                        <td>{{ formatarTelefone($eleitorLogado->celular) }}</td>
                        <td>{{ $eleitorLogado->eleitore_logado_ip }}</td>
                        <td>
                            {{ $eleitorLogado->eleitore_logado_created_at ? \Carbon\Carbon::parse($eleitorLogado->eleitore_logado_created_at)->format('d/m/Y H:i') : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#777; padding:10px;">
                            Nenhum eleitor logado no momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>

</body>
</html>