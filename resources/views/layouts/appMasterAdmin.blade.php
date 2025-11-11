<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Union Eleições')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/icon-union/union-eleicoes.png') }}">
    
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
        .logo-union {
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
                margin-left: 290px; /* Empurra o conteúdo para a direita */
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
        
        <!-- ================== SIDEBAR (Desktop Fixed) ================== -->
        <div class="sidebar-desktop d-none d-lg-block vh-100">
            <div class="d-flex flex-column h-100 overflow-auto">
                <div class="sidebar-header d-flex justify-content-center pt-2 pb-4">
                    <a class="navbar-brand fw-bold text-dark fs-5" href="{{ route('admin.home') }}">
                        <img src="{{ asset('img/logotipo-union/union-eleicoes.png') }}" alt="Union Eleições" class="logo-union">
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <ul class="nav flex-column sidebar-nav" id="accordionSidebar">
                    @if ($permissoesService->verificarPermissao('dashboard', 'ver'))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.home' ? 'active' : '' }}" href="{{ route('admin.home') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                    @endif

                    @if ($permissoesService->verificarPermissao('declaracaoDaEleicao', 'ver'))
                        <li class="nav-item">
                            <a class="nav-link c-point {{ Route::currentRouteName() == 'admin.declaracaoEleicao.pdf' ? 'active' : '' }}" data-bs-toggle="modal" data-bs-target="#layouModalPdf">
                                <i class="fa-solid fa-file-signature"></i> Declaração da Eleição
                            </a>
                        </li>
                    @endif

                    <!-- Eleitores -->
                     @if ($permissoesService->verificarPermissao('eleitores', 'ver'))
                        <li class="nav-item">
                            <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminEleitores.') ? 'active' : '' }}" href="{{ route('admin.adminEleitores.index') }}">
                                <i class="fas fa-users"></i> Eleitores
                            </a>
                        </li>
                    @endif

                    <!-- Menu Menu -->
                    <li class="nav-item">
                        @php
                            $menuAtivo = Str::startsWith(Route::currentRouteName(), ['admin.adminDocumentos.', 'admin.adminAjuda.']);
                        @endphp

                        @if ($permissoesService->verificarPermissao('menus', 'ver'))
                            <a class="nav-link {{ $menuAtivo ? '' : 'collapsed' }}" 
                            href="#" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#usuariosMenu" 
                            aria-expanded="{{ $menuAtivo ? 'true' : 'false' }}">
                                <i class="fa-solid fa-bars"></i> Menus 
                                <i class="fas fa-chevron-down float-end"></i>
                            </a>
                        @endif

                        <ul class="collapse nav flex-column ms-3 {{ $menuAtivo ? 'show' : '' }}" 
                            id="usuariosMenu" 
                            data-bs-parent="#accordionSidebar">

                            @if ($permissoesService->verificarPermissao('ajuda', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminAjuda.') ? 'active' : '' }}" href="{{ route('admin.adminAjuda.index') }}">
                                        <i class="fa-solid fa-info"></i> Ajuda
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('documentos', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminDocumentos.') ? 'active' : '' }}" 
                                    href="{{ route('admin.adminDocumentos.index') }}">
                                        <i class="fa-solid fa-file"></i> Documentos
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    <!-- Perguntas -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#perguntasMenu" aria-expanded="false">
                            <i class="fa-solid fa-question"></i> Perguntas <i class="fas fa-chevron-down float-end"></i>
                        </a>
                        <ul class="collapse nav flex-column ms-3" id="perguntasMenu" data-bs-parent="#accordionSidebar">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fa-solid fa-file"></i> Documentos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fa-solid fa-info"></i> Ajuda
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Relatórios -->
                    <li class="nav-item">
                        @php
                            $rMenuAtivo = Str::startsWith(Route::currentRouteName(), ['admin.adminRelatorioDeLogsDoEleitor.', 'admin.adminDadosEleicao.', 'admin.adminZeresima.', 'admin.adminVotantes.', 'admin.adminNaoVotantes.', 'admin.adminListaEleitores.', 'admin.adminListaChamada.', 'admin.adminEleitorLogado.']);
                        @endphp

                        @if ($permissoesService->verificarPermissao('relatorios', 'ver'))
                            <a class="nav-link {{ $rMenuAtivo ? '' : 'collapsed' }}" 
                            href="#" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#relatoriosMenu" 
                            aria-expanded="{{ $rMenuAtivo ? 'true' : 'false' }}">
                                <i class="fas fa-chart-bar"></i> Relatórios 
                                <i class="fas fa-chevron-down float-end"></i>
                            </a>
                        @endif

                        <ul class="collapse nav flex-column ms-3 {{ $rMenuAtivo ? 'show' : '' }}" 
                            id="relatoriosMenu" 
                            data-bs-parent="#accordionSidebar">

                            @if ($permissoesService->verificarPermissao('dadosDaEleicao', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminDadosEleicao.') ? 'active' : '' }}" href="{{ route('admin.adminDadosEleicao.index') }}">
                                        <i class="fa-solid fa-database"></i> Dados da Eleição
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('eleitoresLogados', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminEleitorLogado.') ? 'active' : '' }}" href="{{ route('admin.adminEleitorLogado.index') }}">
                                        <i class="fa-solid fa-user-circle"></i> Eleitores Logados
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('listaDeEleitores', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminListaEleitores.') ? 'active' : '' }}" href="{{ route('admin.adminListaEleitores.index') }}">
                                        <i class="fa-solid fa-list-check"></i> Lista de Eleitores
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('listaDeChamada', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminListaChamada.') ? 'active' : '' }}" href="{{ route('admin.adminListaChamada.index') }}">
                                        <i class="fa-solid fa-rectangle-list"></i> Lista de Chamada
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('logsDoEleitor', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminRelatorioDeLogsDoEleitor.') ? 'active' : '' }}" href="{{ route('admin.adminRelatorioDeLogsDoEleitor.index') }}">
                                        <i class="fa-solid fa-font-awesome"></i> Logs do Eleitor
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('votantes', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminVotantes.') ? 'active' : '' }}" href="{{ route('admin.adminVotantes.index') }}">
                                        <i class="fa-solid fa-user-check"></i> Votantes
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('naoVotantes', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminNaoVotantes.') ? 'active' : '' }}" href="{{ route('admin.adminNaoVotantes.index') }}">
                                        <i class="fa-solid fa-user-xmark"></i> Não Votantes
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('zeresimaDeVotos', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminZeresima.') ? 'active' : '' }}" href="{{ route('admin.adminZeresima.index') }}">
                                        <i class="fa-solid fa-file-lines"></i> Zerésima de Votos
                                    </a>
                                </li>
                            @endif
                            
                        </ul>
                    </li>

                    <!-- Configurações -->
                     @if ($permissoesService->verificarPermissao('configuracoes', 'ver'))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.adminConfiguracoes.index' ? 'active' : '' }}" href="{{ route('admin.adminConfiguracoes.index') }}">
                                <i class="fas fa-cog"></i> Configurações
                            </a>
                        </li>
                    @endif

                    <!-- Logs de Erro -->
                     @if ($permissoesService->verificarPermissao('logsDeErro', 'ver'))
                        <li class="nav-item">
                            <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminLogs.') ? 'active' : '' }}" href="{{ route('admin.adminLogs.index') }}">
                                <i class="fa-solid fa-font-awesome"></i> Logs de Erro
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Início - Modal declaração da eleição -->
        <div class="modal fade" id="layouModalPdf" tabindex="-1" aria-labelledby="modalPdfLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <div class="modal-header text-white fund-cont">
                        <h5 class="modal-title m-auto" id="modalPdfLabel">Gerar PDF - Declaração da Eleição</h5>
                    </div>
                    <div>
                        <form id="declaFormGerarPdf" target="_blank" action="{{ route('admin.declaracaoEleicao.pdf') }}" method="POST">
                            @csrf

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="orientacao" class="form-label fw-semibold">Orientação:</label>
                                    <select class="form-select" name="orientacao" id="orientacao" required>
                                        <option value="portrait" selected>Retrato (vertical)</option>
                                        <option value="landscape">Paisagem (horizontal)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="nome_arquivo" class="form-label fw-semibold">Nome do arquivo:</label>
                                    <input type="text" class="form-control" id="nome_arquivo" name="nome_arquivo"
                                        placeholder="Ex: declaracao_da_eleicao">
                                </div>
                            </div>

                            <div class="modal-footer d-flex justify-content-center">
                                <button type="button" class="btn px-4 bot-cancelar me-2" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn px-4 bot-confirmar">
                                    Gerar PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Final - Modal declaração da eleição -->
        
        <!-- ================== OFFCANVAS (Mobile Sidebar) ================== -->
        <div class="offcanvas offcanvas-start bg-white" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold" id="mobileSidebarLabel">Navegação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="nav flex-column sidebar-nav" id="mobileAccordionSidebar">
                    @if ($permissoesService->verificarPermissao('dashboard', 'ver'))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.home' ? 'active' : '' }}" href="{{ route('admin.home') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                    @endif

                    <!-- Declaração da Eleição -->
                     @if ($permissoesService->verificarPermissao('declaracaoDaEleicao', 'ver'))
                        <li class="nav-item">
                            <a class="nav-link c-point {{ Route::currentRouteName() == 'admin.declaracaoEleicao.pdf' ? 'active' : '' }}" data-bs-toggle="modal" data-bs-target="#layouModalPdf">
                                <i class="fa-solid fa-file-signature"></i> Declaração da Eleição
                            </a>
                        </li>
                     @endif

                    <!-- Eleitores -->
                     @if ($permissoesService->verificarPermissao('eleitores', 'ver'))
                        <li class="nav-item">
                            <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminEleitores.') ? 'active' : '' }}" href="{{ route('admin.adminEleitores.index') }}">
                                <i class="fas fa-users"></i> Eleitores
                            </a>
                        </li>
                    @endif

                   <!-- Menu com subitens -->
                    <li class="nav-item">
                        @php
                            $menuAtivoMobile = Str::startsWith(Route::currentRouteName(), ['admin.adminDocumentos.', 'admin.adminAjuda.']);
                        @endphp

                        @if ($permissoesService->verificarPermissao('menus', 'ver'))
                            <a class="nav-link {{ $menuAtivoMobile ? '' : 'collapsed' }}" 
                            href="#" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#usuariosMenu" 
                            aria-expanded="{{ $menuAtivoMobile ? 'true' : 'false' }}">
                                <i class="fa-solid fa-bars"></i> Menus 
                                <i class="fas fa-chevron-down float-end"></i>
                            </a>
                        @endif

                        <ul class="collapse nav flex-column ms-3 {{ $menuAtivoMobile ? 'show' : '' }}" 
                            id="usuariosMenu" 
                            data-bs-parent="#mobileAccordionSidebar">
                            @if ($permissoesService->verificarPermissao('ajuda', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminAjuda.') ? 'active' : '' }}" href="{{ route('admin.adminAjuda.index') }}">
                                        <i class="fa-solid fa-info"></i> Ajuda
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('documentos', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminDocumentos.') ? 'active' : '' }}" 
                                    href="{{ route('admin.adminDocumentos.index') }}">
                                        <i class="fa-solid fa-file"></i> Documentos
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    <!-- Perguntas -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#perguntasMenu" aria-expanded="false">
                            <i class="fa-solid fa-question"></i> Perguntas <i class="fas fa-chevron-down float-end"></i>
                        </a>
                        <ul class="collapse nav flex-column ms-3" id="perguntasMenu" data-bs-parent="#mobileAccordionSidebar">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fa-solid fa-file"></i> Documentos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fa-solid fa-info"></i> Ajuda
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Relatórios -->
                    <li class="nav-item">
                        @php
                            $rMenuAtivo = Str::startsWith(Route::currentRouteName(), ['admin.adminRelatorioDeLogsDoEleitor.', 'admin.adminDadosEleicao.', 'admin.adminZeresima.', 'admin.adminVotantes.', 'admin.adminNaoVotantes.', 'admin.adminListaEleitores.', 'admin.adminListaChamada.', 'admin.adminEleitorLogado.']);
                        @endphp

                        @if ($permissoesService->verificarPermissao('relatorios', 'ver'))
                            <a class="nav-link {{ $rMenuAtivo ? '' : 'collapsed' }}" 
                            href="#" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#relatoriosMenu" 
                            aria-expanded="{{ $rMenuAtivo ? 'true' : 'false' }}">
                                <i class="fas fa-chart-bar"></i> Relatórios 
                                <i class="fas fa-chevron-down float-end"></i>
                            </a>
                        @endif

                        <ul class="collapse nav flex-column ms-3 {{ $rMenuAtivo ? 'show' : '' }}" 
                            id="relatoriosMenu" 
                            data-bs-parent="#accordionSidebar">

                            @if ($permissoesService->verificarPermissao('dadosDaEleicao', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminDadosEleicao.') ? 'active' : '' }}" href="{{ route('admin.adminDadosEleicao.index') }}">
                                        <i class="fa-solid fa-database"></i> Dados da Eleição
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('eleitoresLogados', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminEleitorLogado.') ? 'active' : '' }}" href="{{ route('admin.adminEleitorLogado.index') }}">
                                        <i class="fa-solid fa-user-circle"></i> Eleitores Logados
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('listaDeEleitores', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminListaEleitores.') ? 'active' : '' }}" href="{{ route('admin.adminListaEleitores.index') }}">
                                        <i class="fa-solid fa-list-check"></i> Lista de Eleitores
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('listaDeChamada', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminListaChamada.') ? 'active' : '' }}" href="{{ route('admin.adminListaChamada.index') }}">
                                        <i class="fa-solid fa-rectangle-list"></i> Lista de Chamada
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('logsDoEleitor', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminRelatorioDeLogsDoEleitor.') ? 'active' : '' }}" href="{{ route('admin.adminRelatorioDeLogsDoEleitor.index') }}">
                                        <i class="fa-solid fa-font-awesome"></i> Logs do Eleitor
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('votantes', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminVotantes.') ? 'active' : '' }}" href="{{ route('admin.adminVotantes.index') }}">
                                        <i class="fa-solid fa-user-check"></i> Votantes
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('naoVotantes', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminNaoVotantes.') ? 'active' : '' }}" href="{{ route('admin.adminNaoVotantes.index') }}">
                                        <i class="fa-solid fa-user-xmark"></i> Não Votantes
                                    </a>
                                </li>
                            @endif

                            @if ($permissoesService->verificarPermissao('zeresimaDeVotos', 'ver'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.adminZeresima.') ? 'active' : '' }}" href="{{ route('admin.adminZeresima.index') }}">
                                        <i class="fa-solid fa-file-lines"></i> Zerésima de Votos
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    <!-- Configurações -->
                     @if ($permissoesService->verificarPermissao('configuracoes', 'ver'))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.adminConfiguracoes.index' ? 'active' : '' }}" href="{{ route('admin.adminConfiguracoes.index') }}">
                                <i class="fas fa-cog"></i> Configurações
                            </a>
                        </li>
                    @endif

                    <!-- Logs de Erro -->
                     @if ($permissoesService->verificarPermissao('logsDeErro', 'ver'))
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.adminLogs.index' ? 'active' : '' }}" href="{{ route('admin.adminLogs.index') }}">
                                <i class="fa-solid fa-font-awesome"></i> Logs de Erro
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>


        <!-- ================== CONTENT WRAPPER (NAVBAR + MAIN) ================== -->
        <div class="content-wrapper flex-grow-1 d-flex flex-column">
            
            <!-- Navigation Bar (Sticky Top) -->
            <nav class="navbar navbar-expand-md navbar-light bg-white sticky-top" style="z-index: 1020; width: 100%;">
                <div class="container-fluid px-4 px-md-5">
                    
                    <!-- Sidebar Toggle Button (Only visible on mobile/tablet) -->
                    <button class="btn btn-light d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <!-- Brand (Only visible on mobile, hidden on desktop since sidebar has it) -->
                    <a class="navbar-brand fw-bold text-dark d-lg-none" href="{{ route('admin.home') }}">
                        <img src="{{ asset('img/logotipo-union/union-eleicoes.png') }}" alt="Union Eleições" class="logo-union">
                    </a>

                    <!-- Spacer for layout consistency -->
                    <div class="d-none d-lg-block me-auto"></div>
                    
                    <!-- Navigation Content -->
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Right Side (User and Authentication Links) -->
                        <ul class="navbar-nav ms-auto">
                            
                            {{-- @guest/else block simulation --}}
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="fundo-circular text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 14px; font-weight: 600;">{{ iniciais_nome(Auth::user()->name ?? '') }}</span>
                                    <span class="d-none d-lg-inline text-dark">{{ Auth::user()->name }}</span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.adminPerfil.index') }}"><i class="fas fa-user-circle me-2"></i> Perfil</a></li>
                                    @if ($permissoesService->verificarPermissao('configuracoes', 'ver'))
                                        <li><a class="dropdown-item" href="{{ route('admin.adminConfiguracoes.index') }}"><i class="fas fa-cog me-2"></i> Configurações</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <!-- Logout Link -->
                                        <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"
                                            onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> Sair
                                        </a>
                                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            {{-- @endguest --}}
                        </ul>
                    </div>
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