<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Union Eleições')</title>

  <!-- Fontes e icon -->
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wdth,wght@75..100,200..900&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/icon-union/union-eleicoes.png') }}">

  <!-- Bootstrap + Estilo customizado -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      font-family: 'Source Sans 3', sans-serif;
      margin: 0;
      padding: 0;
    }
    /* ---------- HEADER ---------- */
    header {
      position: fixed;
      width: 100%;
      top: 0;
      left: 0;
      height: 90px;
      background-color: #00B070; /* <<<<----- Aqui vai a cor do cliente ----->>>> */
      color: white;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1rem;
      z-index: 1000;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-bottom-left-radius: 15px;
      border-bottom-right-radius: 15px;
    }
    header .logo-union {
      width: 200px;
    }
    /* ---------- MAIN ---------- */
    main {
      padding: 80px 2rem 2rem 2rem;
      flex-grow: 1;
    }
    /* ---------- FOOTER ---------- */
    footer {
      /* margin-left: 220px; */
      background-color: #00B070; /* <<<<----- Aqui vai a cor do cliente ----->>>> */
      color: white;
      padding: 2rem 1rem;
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
    }
    footer a {
      color: #FFD700;
      text-decoration: underline;
    }
    /* ---------- MOBILE ---------- */
    @media (max-width: 992px) {
      .sidebar {
        position: fixed;
        left: -220px;
        transition: 0.3s;
        z-index: 1000;
      }
      .sidebar.show {
        left: 0;
      }
      main, footer {
        margin-left: 0;
        padding-top: 80px;
      }
      .hamburger-btn {
        display: block;
        font-size: 1.5rem;
        color: white;
        border: none;
        background: none;
      }
      .alt-telefone {
        margin-bottom: 25px;
      }
    }
    @media (min-width: 993px) {
      .hamburger-btn {
        display: none;
      }
    }
  </style>
</head>

<body class="d-flex flex-column min-vh-100">

  <!-- HEADER -->
  <header>
    <a href="{{ route('home.index') }}" class="m-auto">
        <img src="{{ asset('img/logotipo-union/union-eleicoes.png') }}" alt="Union Eleições" class="logo-union">
    </a>
  </header>

  <!-- MAIN -->
  <main>
    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer>
    <div class="container text-center alt-telefone">
          &copy; {{ date('Y') }} Union Eleições - Todos os direitos reservados
          @if(!empty($configuracao->suporte == 1))
              | Suporte: 
              <a href="tel:{{ $configuracao->numero_suporte }}">
                  {{ $configuracao->numero_suporte }}
              </a>
          @endif
    </div>
  </footer>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebarMenu').classList.toggle('show');
    }
  </script>

</body>
</html>