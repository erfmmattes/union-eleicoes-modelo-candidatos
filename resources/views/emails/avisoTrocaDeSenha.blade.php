<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Aviso de Troca de Senha</title>
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
        .password-box {
            background-color: #f0f0f0;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            font-size: 1.2rem;
            color: #000000;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: blue;
            color: #ffffff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('img/logotipo-union/union-eleicoes.png') }}" alt="Union Eleições">
        </div>
        <div class="content">
            <h2>Olá, {{ $nome }}!</h2>
            <p>Este e-mail é só um aviso que você acabou de trocar a sua senha.</p>
        </div>
        <div class="footer">
            <p>Atenciosamente,<br><em>Equipe Union Eleições</em></p>
        </div>
    </div>
</body>
</html>