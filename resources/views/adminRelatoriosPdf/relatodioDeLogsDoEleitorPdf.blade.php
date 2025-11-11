<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Union Eleições - Relatório de Logs do Eleitor</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ public_path('img/icon-union/union-eleicoes.png') }}">
    <style>
        @page {
            margin: 100px 40px 60px 40px; /* topo, direita, baixo, esquerda */
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

        .mensagem {
            max-width: 280px;
            word-wrap: break-word;
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
            <img src="{{ public_path('storage/' . $configuracao->caminho) }}" class="l-logo" alt="{{ $configuracao->nome_cliente }}">
        </div>
        <h2>Relatório de Logs do Eleitor</h2>
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
                    <th>Eleitor</th>
                    <th>Eleitor ID</th>
                    <th>Ação</th>
                    <th>Mensagem</th>
                    <th>IP</th>
                    <th>Página</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->eleitor_nome ?? '—' }}</td>
                        <td>{{ $log->eleitor_id ?? '—' }}</td>
                        <td>{{ $log->acao ?? '—' }}</td>
                        <td class="mensagem">{{ $log->mensagem }}</td>
                        <td>{{ $log->ip ?? '—' }}</td>
                        <td>{{ $log->pagina ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; color:#777; padding:10px;">
                            Nenhum log encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>

</body>
</html>