<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperação de Senha</title>
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
            <img src="{{ asset('img/logotipo-union/union-eleicoes.png') }}" alt="Union Eleições">
        </div>
        <div class="content">
            <h2>Olá, {{ $nome }}!</h2>
            <p>Você está recebendo este e-mail porque foi solicitado um reset de senha para a sua conta.</p>
            
            <p style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn botao-pad">Redefinir Senha</a>
            </p>

            <p>Este link para redefinição de senha expira em <strong>60 minutos</strong>.</p>
            <p>Se você não solicitou a redefinição, nenhuma ação adicional é necessária.</p>
        </div>
        <div class="footer">
            <p>Atenciosamente,<br><em>Equipe Union Eleições</em></p>
        </div>
    </div>
</body>
</html>