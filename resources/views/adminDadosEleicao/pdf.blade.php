<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Unir Votações - Dados da Eleição</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ public_path('img/icon-unir/unir-votacoes.png') }}">
    <style>
        @page { margin: 100px 40px 60px 40px; }
        header { position: fixed; top: -80px; left: 0; right: 0; height: 70px; text-align: center; border-bottom: 3px solid #183F77; }
        header img { width: 140px; margin: 0 10px; }
        header h2 { color: #183F77; font-size: 18px; margin: 30px 0 0 0; text-transform: uppercase; }
        footer { position: fixed; bottom: -40px; left: 0; right: 0; height: 40px; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ddd; padding-top: 5px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 35px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; font-size: 11px; }
        th { background: #183F77; color: #fff; }
        tr:nth-child(even) { background: #f7f9fc; }
        .page-number:before { content: counter(page); }
    </style>
</head>
<body>

<header>
    <img src="{{ public_path('img/logotipo-unir/unir-votacoes.png') }}" alt="Unir Votações">
    @if(!empty($configuracao['caminho']))
        <img src="{{ public_path('storage/' . $configuracao['caminho']) }}" class="l-logo" alt="{{ $configuracao['razao_social'] }}">
    @endif
    <h2>Dados da Eleição</h2>
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
                <th>Item</th>
                <th>Quantidade</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total de Eleitores</td>
                <td>{{ $dados['total_eleitores'] }}</td>
                <td>
                    @if(optional($dados['statusEleicao'])->total_eleitores)
                        Concluído
                    @else
                        Pendente
                    @endif
                </td>
            </tr>
            <tr>
                <td>Senhas Geradas</td>
                <td>{{ $dados['senhas_geradas'] }}</td>
                <td>
                    @if(optional($dados['statusEleicao'])->senhas_geradas)
                        Concluído
                    @else
                        Pendente
                    @endif
                </td>
            </tr>
            <tr>
                <td>Emails Enviados</td>
                <td>{{ $dados['emails_enviados'] }}</td>
                <td>
                    @if(optional($dados['statusEleicao'])->emails_enviados)
                        Concluído
                    @else
                        Pendente
                    @endif
                </td>
            </tr>
            <tr>
                <td>Telefones</td>
                <td>{{ $dados['telefones'] }}</td>
                <td>
                    @if(optional($dados['statusEleicao'])->telefones)
                        Concluído
                    @else
                        Pendente
                    @endif
                </td>
            </tr>
            <tr>
                <td>SMS Enviados</td>
                <td>{{ $dados['sms_enviados'] }}</td>
                <td>
                    @if(optional($dados['statusEleicao'])->sms_enviados)
                        Concluído
                    @else
                        Pendente
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</main>

</body>
</html>