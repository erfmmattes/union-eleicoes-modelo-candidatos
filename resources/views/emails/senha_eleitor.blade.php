<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Unir Votações - Senha da Eleição</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 0;
        }
        .header {
            padding: 20px;
            text-align: center;
        }
        .header img {
            max-width: 180px;
        }
        .content {
            padding: 30px 25px;
            color: #333333;
        }
        .content h2 {
            color: #000000;
            margin-bottom: 15px;
            text-align: center;
        }
        .content p {
            line-height: 1.6;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #183F77, #4A90E2);
            color: #ffffff !important;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin: 20px 0;
        }
        .btn:hover {
            background-color: #007550;
        }
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #888888;
            background-color: #f5f5f5;
        }
        .botao-pad {
            background: linear-gradient(135deg, #183F77, #4A90E2);
            color: #ffffff !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('img/logotipo-unir/unir-votacoes.png') }}" alt="Unir Votações">
        </div>
        <div class="content">
            <h2>Olá, {{ $eleitor->nome }}!</h2>
            <p>{!! $configuracao->mensagem_eleitor_email !!}</p>
            <p><strong>Usuário:</strong>CPF</p>
            <p><strong>Senha:</strong>{{ $senha }}</p>
            <p>
                <a href="{{ route('home.index') }}" target="_blank" class="btn botao-pad w-100">Acessar a Eleição</a>
            </p>
        </div>
        <div class="footer">
            <p>Atenciosamente,<br><em>Equipe Unir Votações</em></p>
        </div>
    </div>
</body>
</html>