<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Unir Votações')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/icon-unir/unir-votacoes.png') }}">
    
    <!-- Font Awesome 6 for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Custom Styles for Clean Aesthetic (Inter Font + Sidebar) -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5; /* Fundo levemente mais escuro */
            min-height: 100vh;
        }
        
        /* Custom card styling for a clean look - Mais arredondado (estilo Sneat) */
        .stat-card {
            border-radius: 0.75rem; /* ~12px */
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important; /* Sombra mais forte no hover */
        }

        /* --- Sidebar Styling (Desktop View - Fixed Position) --- */
        .sidebar-desktop {
            width: 290px; /* Fixed width for desktop sidebar */
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #ffffff; 
            /* Sombra mais definida para separação visual */
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.05);
            padding-top: 1rem;
            z-index: 1000;
            border-right: 1px solid #dee2e6; /* Linha sutil de separação */
        }
        /* Aumenta o padding e adiciona margem nos links para um visual mais espaçoso */
        .sidebar-nav .nav-link {
            color: #495057;
            padding: 0.7rem 1.25rem; 
            border-radius: 0.375rem; 
            margin: 0.2rem 0.75rem; /* Links "flutuando" dentro da barra lateral */
            transition: all 0.2s;
            font-weight: 500;
        }
        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 10px; /* Mais espaço para o ícone */
        }
        /* Estilo ativo com sombra para destaque */
        .sidebar-nav .nav-link.active {
            background: linear-gradient(135deg, #183F77, #4A90E2);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 0.125rem 0.25rem #183F77;
        }
        .sidebar-nav .nav-link:hover {
            background: linear-gradient(135deg, #ffffff);
            color: #183F77;
        }
        .sidebar-nav .nav-link.collapsed i.fas.fa-chevron-down {
            transition: transform 0.3s;
        }
        .sidebar-nav .nav-link[aria-expanded="true"] i.fas.fa-chevron-down {
            transform: rotate(180deg);
        }
        .logo-unir {
            width: 180px;
        }
        .fundo-circular {
            background: linear-gradient(135deg, #183F77, #4A90E2);
        }
        .fund-cont {
            background: linear-gradient(135deg, #122b55, #3570c2);
        }
        .bot-confirmar {
            background: linear-gradient(135deg, #183F77, #4A90E2);
            color: #fff !important;
            width: 47%;
        }
        .bot-confirmar:hover {
            background: linear-gradient(135deg, #122b55, #3570c2);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            font-weight: 600 !important;
        }
        .bot-cancelar {
            background: linear-gradient(135deg, #6c757d, #6c757d);
            color: #fff !important;
            width: 47%;
        }
        .bot-cancelar:hover {
            background: linear-gradient(135deg, #6c757d, #6c757d);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            font-weight: 600 !important;

        }
        .c-point {
            cursor: pointer;
        }
        
        /* Ajuste de conteúdo quando a barra lateral está presente */
        @media (min-width: 992px) { 
            .content-wrapper {
                width: calc(100% - 260px);
            }
            .navbar {
                width: calc(100% - 260px);
            }
        }

        /* Navbar mais plana (sem sombra forte) */
        .navbar {
            border-bottom: 1px solid #dee2e6;
            box-shadow: none !important; 
        }

        /* Esconder a barra lateral desktop no mobile/tablet */
        @media (max-width: 991.98px) {
            .sidebar-desktop {
                display: none !important;
            }
            .content-wrapper {
                width: 100%;
            }
            .navbar {
                width: 100%;
                margin-left: 0;
                /* Sombra no mobile para separar do conteúdo */
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important; 
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('declaFormGerarPdf');
            const modal = document.getElementById('layouModalPdf');

            form.addEventListener('submit', function () {
                // Fecha o modal
                const bsModal = bootstrap.Modal.getInstance(modal);
                if(bsModal) bsModal.hide();
            });
        });
    </script>
</head>
<body>
    <!-- Main Application Wrapper -->
    <div id="app" class="d-flex">

        <!-- ================== CONTENT WRAPPER (NAVBAR + MAIN) ================== -->
        <div class="content-wrapper flex-grow-1 d-flex flex-column">
            
            <!-- Navigation Bar (Sticky Top) -->
            <nav class="navbar navbar-expand-md navbar-light bg-white sticky-top" style="z-index: 1020; width: 100%;">
                <div class="container-fluid px-4 px-md-5">

                    <div class="sidebar-header d-flex justify-content-center">
                        <a class="navbar-brand fw-bold text-dark fs-5" href="{{ route('admin.home') }}">
                            <img src="{{ asset('img/logotipo-unir/unir-votacoes.png') }}" alt="Unir Votações" class="logo-unir">
                        </a>
                    </div>

                    <!-- Spacer for layout consistency -->
                    <div class="d-none d-lg-block me-auto"></div>
                    
                    
                </div>
            </nav>

            <!-- Main Content Area -->
             <main class="p-4 p-md-5 flex-grow-1">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>