<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Union Eleições')</title>

  <!-- Fontes e icon -->
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wdth,wght@75..100,200..900&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/icon-union/union-eleicoes.png') }}">

  <!-- Font Awesome 6 for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
      color: white;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1rem;
      z-index: 1000;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-bottom-right-radius: 15px;
      background-color: {{ $configuracao->cor_principal }}; /* <<<<----- Aqui vai a cor do cliente ----->>>> */
    }
    header .logo-union {
      width: 200px;
    }
    /* ---------- SIDEBAR ---------- */
    .sidebar {
      position: fixed;
      top: 90px;
      left: 0;
      height: calc(100% - 60px);
      width: 220px;
      padding-top: 1rem;
      display: flex;
      flex-direction: column;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      background-color: {{ $configuracao->cor_principal }}; /* <<<<----- Aqui vai a cor do cliente ----->>>> */
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 0.75rem 1rem;
      transition: 0.3s;
    }
    .sidebar a:hover,
    .sidebar a.active {
      border-radius: 8px;
      font-weight: 600;
      text-decoration: underline;
    }
    /* ---------- MAIN ---------- */
    main {
      margin-left: 220px; /* espaço da sidebar */
      padding: 80px 2rem 2rem 2rem;
      flex-grow: 1;
    }
    /* ---------- FOOTER ---------- */
    footer {
      margin-left: 220px;
      color: white;
      padding: 2rem 1rem;
      border-top-right-radius: 15px;
      background-color: {{ $configuracao->cor_principal }}; /* <<<<----- Aqui vai a cor do cliente ----->>>> */
    }
    footer a {
      color: #FFD700;
      text-decoration: underline;
    }
    .whatsapp-float {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1050; 
      width: 60px;
      height: 60px;
      background-color: #25D366;
      color: white;
      border-radius: 50%;
      text-align: center;
      box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      font-size: 40px;
    }
    .whatsapp-float:hover {
        background-color: #1DA851;
        transform: scale(1.05);
        color: white;
        box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.3);
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
    <a href="{{ route('home.index') }}">
        <img src="{{ asset('img/logotipo-union/union-eleicoes.png') }}" alt="Union Eleições" class="logo-union">
    </a>

    <button class="hamburger-btn" onclick="toggleSidebar()">☰</button>
  </header>

  <!-- SIDEBAR -->
  <div class="sidebar" id="sidebarMenu">

      {{-- VISITANTE (não logado) --}}
      @if(!session('front_logado'))
          <a class="{{ Route::currentRouteName() == 'home.index' ? 'active' : '' }}"
            href="{{ route('home.index') }}">
              Home
          </a>
          <a class="{{ Route::currentRouteName() == 'recuperarSenha.index' ? 'active' : '' }}"
            href="{{ route('recuperarSenha.index') }}">
              Recuperar senha
          </a>
      @endif

      {{-- USUÁRIO LOGADO E ACEITOU OS TERMOS --}}
       @if(
              session('front_logado') &&
              isset($eleitorLogado) &&
              $eleitorLogado->aceitou_os_termos === 1 &&
              (
                  $configuracao->trocar_de_senha_depois_login === 0 ||
                  ($configuracao->trocar_de_senha_depois_login === 1 && $eleitorLogado->senha_trocada_depois_do_login === 1)
              )
          )
          <a class="{{ Route::currentRouteName() == 'loginEleicao.homeLogadoFront' ? 'active' : '' }}"
            href="{{ route('loginEleicao.homeLogadoFront') }}">
              Home
          </a>

          @if($configuracao->menu_documentos == 1)
              <a class="{{ Route::currentRouteName() == 'documentos.index' ? 'active' : '' }}"
                href="{{ route('documentos.index') }}">
                  Documentos
              </a>
          @endif

          @if($configuracao->menu_ajuda == 1)
              <a class="{{ Route::currentRouteName() == 'ajuda.index' ? 'active' : '' }}"
                href="{{ route('ajuda.index') }}">
                  Ajuda
              </a>
          @endif

          @if($configuracao->menu_trocar_senha == 1)
              <a class="{{ Route::currentRouteName() == 'trocarSenha.index' ? 'active' : '' }}"
                href="{{ route('trocarSenha.index') }}">
                  Trocar senha
              </a>
          @endif

          <a href="{{ route('loginEleicao.logout') }}">Sair</a>
      @endif

  </div>

  <!-- MAIN -->
  <main>
    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer>
    <div class="container text-center alt-telefone">
          &copy; {{ date('Y') }} Union Eleições - Todos os direitos reservados
          @if(!empty($configuracao->suporte_0800 == 1))
              | Suporte: 
              <a href="tel:{{ $configuracao->numero_suporte_0800 }}">
                  {{ formatar0800($configuracao->numero_suporte_0800) }}
              </a>
          @endif
    </div>
  </footer>

  @if(!empty($configuracao->suporte_0800 == 1))
    <a 
        href="https://wa.me/5511999998888?text=Ol%C3%A1%2C%20gostaria%20de%20mais%20informa%C3%A7%C3%B5es%20sobre%20o%20assunto." 
        target="_blank" 
        class="whatsapp-float"
        aria-label="Fale conosco pelo WhatsApp"
        title="Suporte da eleição via WhatsApp"
    >
        <!-- Ícone do WhatsApp em SVG (usado para evitar dependência de Font Awesome ou ícones externos) -->
        <i class="fa-brands fa-whatsapp"></i>
    </a>
  @endif

  <script>
    function toggleSidebar() {
      document.getElementById('sidebarMenu').classList.toggle('show');
    }
  </script>

<!-- BARRA DE CONSENTIMENTO DE COOKIES -->
<!-- <div id="consentBar" style="
    display:none;
    position:fixed;
    bottom:0;
    left:0;
    width:100%;
    background: linear-gradient(135deg, #183F77, #4A90E2);
    color:white;
    padding:15px 20px;
    text-align:center;
    font-weight:500;
    z-index:2000;
    box-shadow:0 -3px 15px rgba(0,0,0,0.2);
    border-top-left-radius:15px;
    border-top-right-radius:15px;
    display:flex;
    flex-wrap:wrap;
    align-items:center;
    justify-content:center;
    gap:10px;
    font-family: 'Source Sans 3', sans-serif;
">
    <span style="flex:1; min-width:200px;">
        Este site utiliza cookies para melhorar sua experiência, analizar visitas e personalizar conteúdos. 
        <a href="{{ route('politicaDePrivacidade.index') }}" target="_blank" style="color:#FFD700; text-decoration:underline; font-weight:bold;">Saiba mais</a>
    </span>
    <div>
    <button onclick="acceptConsent()" style="
        padding:8px 18px; 
        background:#004C40; 
        color:white; 
        border:none; 
        border-radius:8px; 
        font-weight:bold;
        cursor:pointer;
    ">Aceitar</button>
    <button onclick="declineConsent()" style="
        padding:8px 18px; 
        background:#FF4C4C; 
        color:white; 
        border:none; 
        border-radius:8px; 
        font-weight:bold;
        cursor:pointer;
    ">Recusar</button>
    </div>
</div> -->

</body>
</html>
<!---------------- Início - Scripts JavaScript e Jquery ------------------ -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const consent = localStorage.getItem('siteConsent');
        if (!consent) {
            document.getElementById('consentBar').style.display = 'flex';
        }
    });

    function acceptConsent() {
        localStorage.setItem('siteConsent', 'accepted');
        document.getElementById('consentBar').style.display = 'none';
        console.log('Usuário aceitou.');
    }

    function declineConsent() {
        localStorage.setItem('siteConsent', 'declined');
        document.getElementById('consentBar').style.display = 'none';
        console.log('Usuário recusou.');
    }
</script>
<!---------------- Final - Scripts JavaScript e Jquery ------------------ -->