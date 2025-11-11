<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Union Eleições - Senha do Admin</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            max-width: 600px;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center; /* centraliza todo o texto */
        }
        .header {
            padding: 20px;
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
        }
        .content p {
            line-height: 1.6;
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
            <img src="{{ asset('img/logotipo-union/union-eleicoes.png') }}" alt="Union Eleições">
        </div>
        <div class="content">
            <h2>Olá, {{ $usuario->name }}!</h2>
            <p>Você foi adicionado no admin da eleição <strong>{{ $configuracao->nome_cliente }}</strong>.</p>
            <p><strong>Usuário:</strong> {{ $usuario->email }}</p>
            <p><strong>Senha provisória:</strong> {{ $senha }}</p>
            <p>Por segurança, altere sua senha após o primeiro login.</p>
            <a href="{{ route('login') }}" target="_blank" class="btn botao-pad w-100">Acessar</a>
        </div>
        <div class="footer">
            <p>Atenciosamente,<br><em>Equipe Union Eleições</em></p>
        </div>
    </div>
</body>
</html>