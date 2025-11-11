@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Configurações')

@section('content')
<div class="container-fluid">

    <!-- Cabeçalho -->
    <div class="mb-4">
        <h1 class="h2 fw-bolder text-dark">Configurações da Eleição</h1>
        <p class="text-muted">Gerencie os parâmetros gerais e visuais do sistema de votação.</p>
    </div>

    <!-- Formulário Principal e Estrutura de Abas -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-0">
            
            <!-- Navegação das Abas -->
            <ul class="nav nav-tabs justify-content-center flex-column flex-md-row text-center" id="configTabs" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link active w-100 text-center text-md-start" 
                        id="geral-tab" data-bs-toggle="tab" data-bs-target="#geral-tab-pane" 
                        type="button" role="tab" aria-controls="geral-tab-pane" aria-selected="true">
                        Geral e Data
                    </button>
                </li>

                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100 text-center text-md-start"
                        id="visual-tab" data-bs-toggle="tab" data-bs-target="#visual-tab-pane"
                        type="button" role="tab" aria-controls="visual-tab-pane" aria-selected="false">
                        Identidade Visual
                    </button>
                </li>

                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100 text-center text-md-start"
                        id="suporte-tab" data-bs-toggle="tab" data-bs-target="#suporte-tab-pane"
                        type="button" role="tab" aria-controls="suporte-tab-pane" aria-selected="false">
                        Suporte e Mensagens
                    </button>
                </li>

                @if(Auth::user()->tipo_usuario == "admin-master")
                    <li class="nav-item flex-fill" role="presentation">
                        <button class="nav-link w-100 text-center text-md-start"
                            id="dados-comissao-tab" data-bs-toggle="tab" data-bs-target="#dados-comissao-tab-pane"
                            type="button" role="tab" aria-controls="dados-comissao-tab-pane" aria-selected="false">
                            Dados da Comissão
                        </button>
                    </li>
                @endif

                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100 text-center text-md-start"
                        id="menus-tab" data-bs-toggle="tab" data-bs-target="#menus-tab-pane"
                        type="button" role="tab" aria-controls="menus-tab-pane" aria-selected="false">
                        Segurança e Menus
                    </button>
                </li>

                @if(Auth::user()->tipo_usuario == "admin-master")
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100 text-center text-md-start"
                        id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin-tab-pane"
                        type="button" role="tab" aria-controls="admin-tab-pane" aria-selected="false">
                        Ações Admin
                    </button>
                </li>
                @endif
            </ul>

            <!-- Mensagens de Sucesso e Erro (Mantidas no topo para visibilidade) -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-4 alert-temporaria" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show m-4 alert-temporaria" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Formulário que engloba todas as abas -->
            <form action="{{ route('admin.adminConfiguracoes.update', $configuracao->id ?? 1) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <!-- Conteúdo das Abas -->
                <div class="tab-content p-4 p-md-5" id="configTabsContent">
                    
                    <!-- Aba 1: Geral e Data -->
                    <div class="tab-pane fade show active" id="geral-tab-pane" role="tabpanel" aria-labelledby="geral-tab" tabindex="0">
                        <div class="row g-4">
                            <!-- Nome Eleição -->
                            <div class="col-md-6">
                                <label for="nome_eleicao" class="form-label fw-semibold">Nome da Eleição</label>
                                <input type="text" name="nome_eleicao" id="nome_eleicao" class="form-control"
                                       value="{{ old('nome_eleicao', $configuracao->nome_eleicao ?? '') }}">
                                <small class="text-muted">Ex: Eleição de Candidatos</small>
                            </div>

                            <!-- Nome Cliente(Razão Social) -->
                            <div class="col-md-6">
                                <label for="razao_social" class="form-label fw-semibold">Nome Cliente (Razão Social)</label>
                                <input type="text" name="razao_social" id="razao_social" class="form-control"
                                       value="{{ old('razao_social', $configuracao->razao_social ?? '') }}">
                            </div>

                            <!-- CNPJ -->
                            <div class="col-md-12">
                                <label for="cnpj" class="form-label fw-semibold">CNPJ</label>
                                <input type="text" name="cnpj" id="cnpj" class="form-control"
                                       value="{{ old('cnpj', $configuracao->cnpj ?? '') }}"
                                       maxlength="18" placeholder="00.000.000/0000-00" autocomplete="off">
                            </div>

                            <!-- Data e Hora do Início da Eleição -->
                            <div class="col-md-6">
                                <label for="data_hora_inicio_eleicao" class="form-label fw-semibold">Início da Eleição</label>
                                <input type="datetime-local" name="data_hora_inicio_eleicao" id="data_hora_inicio_eleicao"
                                       class="form-control"
                                       value="{{ old('data_hora_inicio_eleicao', $configuracao->data_hora_inicio_eleicao ? \Carbon\Carbon::parse($configuracao->data_hora_inicio_eleicao)->format('Y-m-d\TH:i') : '') }}">
                            </div>

                            <!-- Data e Hora do Final da Eleição -->
                            <div class="col-md-6">
                                <label for="data_hora_final_eleicao" class="form-label fw-semibold">Final da Eleição</label>
                                <input type="datetime-local" name="data_hora_final_eleicao" id="data_hora_final_eleicao"
                                       class="form-control"
                                       value="{{ old('data_hora_final_eleicao', $configuracao->data_hora_final_eleicao ? \Carbon\Carbon::parse($configuracao->data_hora_final_eleicao)->format('Y-m-d\TH:i') : '') }}">
                            </div>

                        </div>
                    </div>
                    
                    <!-- Aba 2: Identidade Visual -->
                    <div class="tab-pane fade" id="visual-tab-pane" role="tabpanel" aria-labelledby="visual-tab" tabindex="0">
                        <div class="row g-4">
                            <!-- Cor Principal -->
                            <div class="col-md-6 mb-4">
                                <label for="cor_principal" class="form-label fw-semibold">Cor Principal</label>
                                <div class="input-group align-items-center">
                                    <input type="color"
                                        id="cor_principal_picker"
                                        value="{{ old('cor_principal', $configuracao->cor_principal ?? '#3498db') }}"
                                        class="form-control form-control-color flex-shrink-0"
                                        title="Escolha a cor principal">

                                    <input type="text"
                                        name="cor_principal"
                                        id="cor_principal"
                                        class="form-control"
                                        placeholder="#3498db ou rgb(52,152,219)"
                                        value="{{ old('cor_principal', $configuracao->cor_principal ?? '#3498db') }}">
                                </div>
                                <small class="text-muted">Cor usada em botões e destaques (HEX, RGB ou HSL).</small>
                            </div>

                            <!-- Cor de Hover -->
                            <div class="col-md-6 mb-4">
                                <label for="cor_hover" class="form-label fw-semibold">Cor de Hover</label>
                                <div class="input-group align-items-center">
                                    <input type="color"
                                        id="cor_hover_picker"
                                        value="{{ old('cor_hover', $configuracao->cor_hover ?? '#2980b9') }}"
                                        class="form-control form-control-color flex-shrink-0"
                                        title="Escolha a cor do hover">

                                    <input type="text"
                                        name="cor_hover"
                                        id="cor_hover"
                                        class="form-control"
                                        placeholder="#2980b9 ou rgb(41,128,185)"
                                        value="{{ old('cor_hover', $configuracao->cor_hover ?? '#2980b9') }}">
                                </div>
                                <small class="text-muted">Cor dos elementos interativos ao passar o mouse.</small>
                            </div>

                            <!-- Logotipo -->
                            <div class="col-12">
                                <label for="logotipo" class="form-label fw-semibold">Logotipo</label>
                                <input type="file" 
                                    name="logotipo" 
                                    id="logotipo"
                                    accept=".png"
                                    class="form-control @error('logotipo') is-invalid @enderror">
                                <small class="text-muted mt-2">
                                    Tipo de arquivo aceito: PNG. Tamanho recomendado: até 1MB.
                                </small>

                                @error('logotipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- Exibe o logotipo atual se existir -->
                                @if(isset($configuracao) && !empty($configuracao->logotipo))
                                    <div class="mt-3">
                                        <p class="fw-semibold mb-1">Logotipo atual:</p>
                                        <img src="{{ asset('storage/' . $configuracao->caminho) }}" 
                                            alt="Logotipo Atual"
                                            class="img-fluid border rounded shadow-sm exibe-logo"
                                            style="max-height: 100px;">
                                    </div>
                                @endif

                                <!-- Preview da nova imagem selecionada -->
                                <div id="preview-container" class="mt-3 d-none">
                                    <p class="fw-semibold mb-1">Pré-visualização da nova imagem:</p>
                                    <img id="preview-image" 
                                        class="img-fluid border rounded shadow-sm"
                                        style="max-height: 100px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aba 3: Suporte e Mensagens -->
                    <div class="tab-pane fade" id="suporte-tab-pane" role="tabpanel" aria-labelledby="suporte-tab" tabindex="0">
                        <div class="row g-4">
                            <!-- Ativar Suporte 0800? -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-block">Ativar Suporte 0800?</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input cp-tr" type="checkbox" role="switch"
                                        id="suporte_0800" name="suporte_0800"
                                        value="1" {{ old('suporte_0800', $configuracao->suporte_0800 ?? false) ? 'checked' : '' }}>
                                </div>
                            </div>

                            <!-- Número de Suporte do 0800 -->
                            <div class="col-md-6">
                                <label for="numero_suporte_0800" class="form-label fw-semibold">Número de Suporte do 0800</label>
                                <input type="text" name="numero_suporte_0800" id="numero_suporte_0800"
                                    class="form-control"
                                    value="{{ old('numero_suporte_0800', formatar0800($configuracao->numero_suporte_0800) ?? '') }}"
                                    placeholder="0800 000 0000"
                                    maxlength="13">
                            </div>

                            <!-- Ativar Suporte WhatsApp? -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-block">Ativar Suporte WhatsApp?</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input cp-tr" type="checkbox" role="switch"
                                        id="suporte_whatsapp" name="suporte_whatsapp"
                                        value="1" {{ old('suporte_whatsapp', $configuracao->suporte_whatsapp ?? false) ? 'checked' : '' }}>
                                </div>
                            </div>

                            <!-- Número de Suporte do WhatsApp -->
                            <div class="col-md-6">
                                <label for="numero_suporte_whatsapp" class="form-label fw-semibold">Número de Suporte do WhatsApp</label>
                                <input type="text" name="numero_suporte_whatsapp" id="numero_suporte_whatsapp"
                                    class="form-control"
                                    value="{{ old('numero_suporte_whatsapp', formatarTelefone($configuracao->numero_suporte_whatsapp) ?? '') }}"
                                    placeholder="(99) 99999-9999"
                                    maxlength="15">
                            </div>

                            <hr>

                            <!-- Bloco do envio de mesagem de senha por e-mail e sms -->
                            <div class="col-md-6">
                                <!-- Remetente do e-mail para envio de senha -->
                                <div>
                                    <label for="remetente_do_email" class="form-label fw-semibold">Remetente do e-mail para envio de senha</label>
                                    <input type="text" name="remetente_do_email" id="remetente_do_email"
                                        class="form-control"
                                        value="{{ old('remetente_do_email', $configuracao->remetente_do_email ?? '') }}"
                                        placeholder='"Union Eleições - <no-reply@unioneleicoes.com.br>"'>
                                </div>

                                <!-- Assunto do e-mail para envio de senha -->
                                <div class="mt-1">
                                    <label for="assunto_do_email" class="form-label fw-semibold">Assunto do e-mail para envio de senha</label>
                                    <input type="text" name="assunto_do_email" id="assunto_do_email"
                                        class="form-control"
                                        value="{{ old('assunto_do_email', $configuracao->assunto_do_email ?? '') }}"
                                        placeholder='"Eleição de Cipa 2025 - 05/11/2025"'>
                                </div>

                                <!-- Mensagem E-mail -->
                                <div class="mt-1">
                                    <label for="mensagem_eleitor_email" class="form-label fw-semibold">Mensagem E-mail</label>
                                    <textarea name="mensagem_eleitor_email" id="mensagem_eleitor_email"
                                        class="form-control"
                                        rows="3">{{ old('mensagem_eleitor_email', $configuracao->mensagem_eleitor_email ?? '') }}</textarea>
                                    <small class="text-muted">Mensagem padrão enviada ao eleitor por e-mail.</small>
                                </div>

                                <!-- Mensagem SMS -->
                                <div class="mt-2">
                                    <label for="mensagem_eleitor_sms" class="form-label fw-semibold">Mensagem SMS</label>
                                    <textarea name="mensagem_eleitor_sms" id="mensagem_eleitor_sms"
                                        class="form-control"
                                        rows="3">{{ old('mensagem_eleitor_sms', $configuracao->mensagem_eleitor_sms ?? '') }}</textarea>
                                    <small class="text-muted">Mensagem padrão enviada ao eleitor por SMS.</small>
                                </div>
                            </div>

                            <!-- Termos de Uso -->
                            <div class="col-md-6">
                                <label for="termos" class="form-label fw-semibold">Termos de Uso</label>
                                <textarea name="termos" id="termos"
                                    class="form-control"
                                    rows="3">{{ old('termos', $configuracao->termos ?? '') }}</textarea>
                                <small class="text-muted">Texto que será exibido na seção de Termos de Uso.</small>
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->tipo_usuario == "admin-master")
                        <!-- Aba 4: Suporte e Mensagens -->
                        <div class="tab-pane fade" id="dados-comissao-tab-pane" role="tabpanel" aria-labelledby="dados-comissao-tab" tabindex="0">
                            <div class="row g-4">

                                <!-- Ativar Dados da Comissão -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold d-block">Ativar Dados da Comissão</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input cp-tr" type="checkbox" role="switch"
                                            id="dados_da_comissao" name="dados_da_comissao"
                                            value="1" {{ old('dados_da_comissao', $configuracao->dados_da_comissao ?? false) ? 'checked' : '' }}>
                                        <small class="text-muted">Ativa dados da comissão para os relatórios.</small>
                                    </div>
                                </div>

                                <!-- ====================== PRESIDENTE ====================== -->
                                <div class="col-12 mt-4 mb-1">
                                    <h5 class="fw-bold border-bottom pb-2">
                                        Dados do Presidente
                                    </h5>
                                </div>

                                <!-- Nome do Presidente -->
                                <div class="col-md-6">
                                    <label for="nome_presidente" class="form-label fw-semibold">Nome</label>
                                    <input type="text" name="nome_presidente" id="nome_presidente" class="form-control"
                                        value="{{ old('nome_presidente', $configuracao->nome_presidente ?? '') }}">
                                </div>

                                <!-- CPF do Presidente -->
                                <div class="col-md-6">
                                    <label for="cpf_presidente" class="form-label fw-semibold">CPF</label>
                                    <input type="text" name="cpf_presidente" id="cpf_presidente" class="form-control"
                                        value="{{ old('cpf_presidente', $configuracao->cpf_presidente ?? '') }}"
                                        maxlength="14" placeholder="000.000.000-00" autocomplete="off">
                                </div>

                                <!-- E-mail do Presidente -->
                                <div class="col-md-6">
                                    <label for="email_presidente" class="form-label fw-semibold">E-mail</label>
                                    <input type="email" name="email_presidente" id="email_presidente" class="form-control"
                                        value="{{ old('email_presidente', $configuracao->email_presidente ?? '') }}">
                                </div>

                                <!-- Celular do Presidente -->
                                <div class="col-md-6">
                                    <label for="numero_suporte_whatsapp" class="form-label fw-semibold">Celular</label>
                                    <input type="text" name="celular_presidente" id="celular_presidente"
                                        class="form-control"
                                        value="{{ old('celular_presidente', formatarTelefone($configuracao->celular_presidente) ?? '') }}"
                                        placeholder="(99) 99999-9999"
                                        maxlength="15">
                                </div>

                                <!-- ====================== MEMBRO 1 ====================== -->
                                <div class="col-12 mt-5 mb-1">
                                    <h5 class="fw-bold border-bottom pb-2">
                                        Dados do Membro 1
                                    </h5>
                                </div>

                                <!-- Nome do Membro 1 -->
                                <div class="col-md-6">
                                    <label for="nome_mebro_1" class="form-label fw-semibold">Nome</label>
                                    <input type="text" name="nome_mebro_1" id="nome_mebro_1" class="form-control"
                                        value="{{ old('nome_mebro_1', $configuracao->nome_mebro_1 ?? '') }}">
                                </div>

                                <!-- CPF do Membro 1 -->
                                <div class="col-md-6">
                                    <label for="cpf_mebro_1" class="form-label fw-semibold">CPF</label>
                                    <input type="text" name="cpf_mebro_1" id="cpf_mebro_1" class="form-control"
                                        value="{{ old('cpf_mebro_1', $configuracao->cpf_mebro_1 ?? '') }}"
                                        maxlength="14" placeholder="000.000.000-00" autocomplete="off">
                                </div>

                                <!-- E-mail do Membro 1 -->
                                <div class="col-md-6">
                                    <label for="email_mebro_1" class="form-label fw-semibold">E-mail</label>
                                    <input type="email" name="email_mebro_1" id="email_mebro_1" class="form-control"
                                        value="{{ old('email_mebro_1', $configuracao->email_mebro_1 ?? '') }}">
                                </div>

                                <!-- Celular do Membro 1 -->
                                <div class="col-md-6">
                                    <label for="celular_mebro_1" class="form-label fw-semibold">Celular</label>
                                    <input type="text" name="celular_mebro_1" id="celular_mebro_1"
                                        class="form-control"
                                        value="{{ old('celular_mebro_1', formatarTelefone($configuracao->celular_mebro_1) ?? '') }}"
                                        placeholder="(99) 99999-9999"
                                        maxlength="15">
                                </div>

                                <!-- ====================== MEMBRO 2 ====================== -->
                                <div class="col-12 mt-5 mb-1">
                                    <h5 class="fw-bold border-bottom pb-2">
                                        Dados do Membro 2
                                    </h5>
                                </div>

                                <!-- Nome do Membro 2 -->
                                <div class="col-md-6">
                                    <label for="nome_mebro_2" class="form-label fw-semibold">Nome</label>
                                    <input type="text" name="nome_mebro_2" id="nome_mebro_2" class="form-control"
                                        value="{{ old('nome_mebro_2', $configuracao->nome_mebro_2 ?? '') }}">
                                </div>

                                <!-- CPF do Membro 2 -->
                                <div class="col-md-6">
                                    <label for="cpf_mebro_2" class="form-label fw-semibold">CPF</label>
                                    <input type="text" name="cpf_mebro_2" id="cpf_mebro_2" class="form-control"
                                        value="{{ old('cpf_mebro_2', $configuracao->cpf_mebro_2 ?? '') }}"
                                        maxlength="14" placeholder="000.000.000-00" autocomplete="off">
                                </div>

                                <!-- E-mail do Membro 2 -->
                                <div class="col-md-6">
                                    <label for="email_mebro_2" class="form-label fw-semibold">E-mail</label>
                                    <input type="email" name="email_mebro_2" id="email_mebro_2" class="form-control"
                                        value="{{ old('email_mebro_2', $configuracao->email_mebro_2 ?? '') }}">
                                </div>

                                <!-- Celular do Membro 2 -->
                                <div class="col-md-6">
                                    <label for="celular_mebro_2" class="form-label fw-semibold">Celular</label>
                                    <input type="text" name="celular_mebro_2" id="celular_mebro_2"
                                        class="form-control"
                                        value="{{ old('celular_mebro_2', formatarTelefone($configuracao->celular_mebro_2) ?? '') }}"
                                        placeholder="(99) 99999-9999"
                                        maxlength="15">
                                </div>

                            </div>
                        </div>
                    @endif

                    <!-- Aba 5: Segurança e Menus -->
                    <div class="tab-pane fade" id="menus-tab-pane" role="tabpanel" aria-labelledby="menus-tab" tabindex="0">
                        <div class="row g-4">

                            <!-- ====================== SEGURANÇA ====================== -->
                            <div class="col-12 mt-4 mb-1">
                                <h5 class="fw-bold border-bottom pb-2">
                                    Segurança
                                </h5>
                            </div>

                            <!-- Autenticação de 2 Etapas -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-block">Autenticação de 2 Etapas</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input cp-tr" type="checkbox" role="switch"
                                        id="autenticacao_de_2_etapas" name="autenticacao_de_2_etapas"
                                        value="1" {{ old('autenticacao_de_2_etapas', $configuracao->autenticacao_de_2_etapas ?? false) ? 'checked' : '' }}>
                                    <small class="text-muted">Ativa a autenticação de dois fatores para os eleitores.</small>
                                </div>
                            </div>

                            <!-- Troca de Senha Depois do Login -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-block">Troca de Senha Depois do Login</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input cp-tr" type="checkbox" role="switch"
                                        id="trocar_de_senha_depois_login" name="trocar_de_senha_depois_login"
                                        value="1" {{ old('trocar_de_senha_depois_login', $configuracao->trocar_de_senha_depois_login ?? false) ? 'checked' : '' }}>
                                    <small class="text-muted">Ativa a troca de senha depois do login para os eleitores.</small>
                                </div>
                            </div>
                             
                            <!-- ====================== MENUS ====================== -->
                            <div class="col-12 mt-5 mb-1">
                                <h5 class="fw-bold border-bottom pb-2">
                                    Menus
                                </h5>
                            </div>

                            <!-- Menu Ajuda -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-block">Menu Ajuda</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input cp-tr" type="checkbox" role="switch"
                                        id="menu_ajuda" name="menu_ajuda"
                                        value="1" {{ old('menu_ajuda', $configuracao->menu_ajuda ?? false) ? 'checked' : '' }}>
                                </div>
                            </div>

                            <!-- Menu Documentos -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-block">Menu Documentos</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input cp-tr" type="checkbox" role="switch"
                                        id="menu_documentos" name="menu_documentos"
                                        value="1" {{ old('menu_documentos', $configuracao->menu_documentos ?? false) ? 'checked' : '' }}>
                                </div>
                            </div>

                            <!-- Menu Trocar Senha -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-block">Menu Trocar Senha</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input cp-tr" type="checkbox" role="switch"
                                        id="menu_trocar_senha" name="menu_trocar_senha"
                                        value="1" {{ old('menu_trocar_senha', $configuracao->menu_trocar_senha ?? false) ? 'checked' : '' }}>
                                </div>
                            </div>

                            <!-- Menu Recuperar Senha -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-block">Menu Recuperar Senha</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input cp-tr" type="checkbox" role="switch"
                                        id="menu_recuperar_senha" name="menu_recuperar_senha"
                                        value="1" {{ old('menu_recuperar_senha', $configuracao->menu_recuperar_senha ?? false) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Botão Salvar (Fica DENTRO do form, mas após todo o conteúdo das abas) -->
                    <div id="btn-salvar" class="mt-5 text-end border-top pt-4">
                        <button type="submit" class="btn fundo-botao px-4">
                            <i class="fas fa-save"></i> Salvar Configurações
                        </button>
                    </div>
                </div>
            </form>

            <!-- Aba 6: Ações Admin (Fica fora do formulário de UPDATE, pois é uma ação DELETE/POST separada) -->
            @if(Auth::user()->tipo_usuario == "admin-master")
            <div class="tab-content px-4 px-md-5 pb-5 pt-0 ort-mtabs">
                <div class="tab-pane fade" id="admin-tab-pane" role="tabpanel" aria-labelledby="admin-tab" tabindex="0">
                    <div class="mt-4 p-4 border rounded-3 bg-light">
                        <h5 class="fw-bold text-danger mb-3">Reiniciar Eleição</h5>
                        <p class="mb-3 text-muted">Atenção! Esta ação é irreversível e apagará todos os votos, logs de auditoria e resultados. Utilize com extrema cautela.</p>
                        
                        <button type="button" class="btn botao-reiniciar px-3" id="abrirSenhaModal">
                            <i class="fa-solid fa-triangle-exclamation"></i> Reiniciar Eleição
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Solicitar Senha (Sem alteração, funciona com o JS abaixo) -->
<div class="modal fade" id="senhaModal" tabindex="-1" aria-labelledby="senhaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header text-white fund-cont">
                <h5 class="modal-title m-auto" id="senhaModalLabel">
                    Confirmação de Identidade
                </h5>
            </div>

            <div class="modal-body text-center">
                <p class="fs-6">Digite sua senha para continuar:</p>
                <div class="input-group mb-3">
                    <input id="senhaUsuario" type="password" class="form-control" placeholder="Senha">
                    <button class="btn btn-outline-secondary toggle-senha-btn" type="button" data-target="senhaUsuario" aria-label="Mostrar senha" aria-pressed="false">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
                <div id="senhaErro" class="text-danger small d-none">Senha incorreta.</div>
            </div>

            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn px-4 bot-cancelar" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn px-4 bot-confirmar" id="confirmarSenha">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmação -->
<div class="modal fade" id="confirmResetModal" tabindex="-1" aria-labelledby="confirmResetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title m-auto" id="confirmResetModalLabel">
                    Confirmar Reinício da Eleição
                </h5>
            </div>

            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <p class="fs-6">Tem certeza de que deseja <strong>reiniciar toda a eleição</strong>?</p>
                <p class="text-muted small">Essa ação apagará votos, logs e resultados. Não poderá ser desfeita.</p>

                <!-- Barra de progresso (inicialmente oculta) -->
                <div id="progress-container" class="mt-4" style="display: none;">
                    <div class="progress" style="height: 20px;">
                        <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-danger fw-bold" 
                             style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    <p class="text-muted mt-2 small">Reiniciando, por favor aguarde...</p>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-center" id="modal-footer-buttons">
                <button type="button" class="btn px-4 bot-can-reiciciar" data-bs-dismiss="modal">Cancelar</button>
                <form id="form-reiniciar" action="{{ route('admin.adminConfiguracoes.reiniciarEleicao') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn px-4 bot-reiniciar">
                        Sim, Reiniciar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmação (Sem alteração, funciona com o JS abaixo) -->
<!-- <div class="modal fade" id="confirmResetModal" tabindex="-1" aria-labelledby="confirmResetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title m-auto" id="confirmResetModalLabel">
                    Confirmar Reinício da Eleição
                </h5>
            </div>

            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <p class="fs-6">Tem certeza de que deseja <strong>reiniciar toda a eleição</strong>?</p>
                <p class="text-muted small">Essa ação apagará votos, logs e resultados. Não poderá ser desfeita.</p>
            </div>

            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn px-4 bot-can-reiciciar" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.adminConfiguracoes.reiniciarEleicao') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn px-4 bot-reiniciar">
                        Sim, Reiniciar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div> -->
@endsection
<!---------------- Início - Scripts JavaScript e Jquery ------------------ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Alerts temporários ---
    const alerts = document.querySelectorAll('.alert-temporaria');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // --- Inicializa modais ---
    const senhaModal = new bootstrap.Modal(document.getElementById('senhaModal'));
    const resetModal = new bootstrap.Modal(document.getElementById('confirmResetModal'));

    // --- Abrir modal de senha ---
    document.getElementById('abrirSenhaModal').addEventListener('click', () => {
        document.getElementById('senhaUsuario').value = '';
        document.getElementById('senhaErro').classList.add('d-none');
        senhaModal.show();
    });

    // --- Confirmar senha via AJAX ---
    document.getElementById('confirmarSenha').addEventListener('click', async function() {
        const senha = document.getElementById('senhaUsuario').value;
        const erroEl = document.getElementById('senhaErro');
        erroEl.classList.add('d-none');

        const formData = new FormData();
        formData.append('senha', senha);

        try {
            const response = await fetch('{{ route('admin.adminConfiguracoes.verificarSenha') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ senha })
            });

            const data = await response.json();

            if (data.success) {
                senhaModal.hide();
                resetModal.show();
            } else {
                erroEl.textContent = data.message || 'Senha incorreta.';
                erroEl.classList.remove('d-none');
            }

        } catch (error) {
            console.error('Erro ao validar senha:', error);
            erroEl.textContent = 'Erro de conexão ou sistema.';
            erroEl.classList.remove('d-none');
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // inicializa toggles existentes
  function initToggleSenha() {
    document.querySelectorAll('.toggle-senha-btn').forEach(btn => {
      // previne múltiplos handlers
      if (btn._toggleSenhaAttached) return;
      btn._toggleSenhaAttached = true;

      btn.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.dataset.target || 'senhaUsuario';
        const input = document.getElementById(targetId);

        if (!input) {
          console.warn('Toggle senha: input não encontrado:', targetId);
          return;
        }

        // alterna type
        if (input.type === 'password') {
          input.type = 'text';
          this.setAttribute('aria-pressed', 'true');
          this.setAttribute('aria-label', 'Ocultar senha');
        } else {
          input.type = 'password';
          this.setAttribute('aria-pressed', 'false');
          this.setAttribute('aria-label', 'Mostrar senha');
        }

        // troca o ícone se existir (FontAwesome compatível)
        const icon = this.querySelector('i');
        if (icon) {
          // remove/adiciona as classes de olho (fa-eye / fa-eye-slash)
          if (icon.classList.contains('fa-eye')) {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
          } else if (icon.classList.contains('fa-eye-slash')) {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
          } else {
            // caso use 'fa-solid' em vez de 'fas', apenas toggle via classList toggle de uma flag
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
          }
        } else {
          // fallback sem ícone: alterna texto interno
          this.textContent = (input.type === 'password') ? 'Mostrar' : 'Esconder';
        }
      });
    });
  }

  initToggleSenha();

  // Se conteúdo da modal for injetado dinamicamente, re-inicializar ao abrir modal:
  // exemplo com Bootstrap modal (se você usa bootstrap):
  try {
    document.querySelectorAll('.modal').forEach(modalEl => {
      modalEl.addEventListener('shown.bs.modal', initToggleSenha);
    });
  } catch (err) {
    // bootstrap não presente -> ignora
  }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function sincronizarCor(pickerId, inputId) {
        const picker = document.getElementById(pickerId);
        const input = document.getElementById(inputId);

        // Atualiza input ao mudar a paleta
        picker.addEventListener('input', () => input.value = picker.value);

        // Atualiza paleta ao digitar um HEX válido
        input.addEventListener('input', () => {
            const val = input.value.trim();
            if (/^#([0-9A-Fa-f]{6})$/.test(val)) {
                picker.value = val;
            }
        });
    }

    sincronizarCor('cor_principal_picker', 'cor_principal');
    sincronizarCor('cor_hover_picker', 'cor_hover');
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('logotipo');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');

    input.addEventListener('change', function () {
        const file = this.files[0];

        if (!file) {
            previewContainer.classList.add('d-none');
            previewImage.src = '';
            return;
        }

        // Verifica se é PNG
        if (!file.type.includes('png')) {
            alert('Apenas arquivos PNG são aceitos!');
            this.value = '';
            previewContainer.classList.add('d-none');
            previewImage.src = '';
            return;
        }

        // Cria preview
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('cnpj');
  if (!input) return;

  function formatCnpj(value) {
    const digits = value.replace(/\D/g, '');
    return digits
      .replace(/^(\d{2})(\d)/, '$1.$2')
      .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
      .replace(/\.(\d{3})(\d)/, '.$1/$2')
      .replace(/(\d{4})(\d)/, '$1-$2')
      .slice(0, 18);
  }

  function applyMaskAndKeepCaret(e) {
    const start = input.selectionStart;
    const oldValue = input.value;
    const oldLen = oldValue.length;

    const newValue = formatCnpj(oldValue);
    input.value = newValue;

    const newLen = newValue.length;
    let newPos = start + (newLen - oldLen);

    if (newPos < 0) newPos = 0;
    if (newPos > newLen) newPos = newLen;

    try {
      input.setSelectionRange(newPos, newPos);
    } catch (err) {}
  }

  input.addEventListener('input', applyMaskAndKeepCaret);
  input.addEventListener('paste', function () {
    setTimeout(() => applyMaskAndKeepCaret(), 0);
  });

  // Formata valor inicial (modo edição)
  if (input.value) {
    input.value = formatCnpj(input.value);
  }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const suporteCheckbox = document.getElementById('suporte_0800');
    const mensagemEmail = document.getElementById('numero_suporte_0800');
    function toggleMensagem() {
        mensagemEmail.disabled = !suporteCheckbox.checked;
    }
    toggleMensagem();
    suporteCheckbox.addEventListener('change', toggleMensagem);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const suporteCheckbox = document.getElementById('suporte_whatsapp');
    const mensagemEmail = document.getElementById('numero_suporte_whatsapp');
    function toggleMensagem() {
        mensagemEmail.disabled = !suporteCheckbox.checked;
    }
    toggleMensagem();
    suporteCheckbox.addEventListener('change', toggleMensagem);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('numero_suporte_0800');

    input.addEventListener('input', function(e) {
        let valor = e.target.value.replace(/\D/g, ''); // remove tudo que não for número

        // força começar com 0800
        if (!valor.startsWith('0800')) {
            valor = '0800' + valor;
        }

        // formata: 0800 123 4567
        valor = valor.substring(0, 11); // limita a 11 dígitos (0800 + 7 números)
        let formatado = valor.replace(/^(\d{4})(\d{3})(\d{0,4})$/, function(_, p1, p2, p3) {
            return p3 ? `${p1} ${p2} ${p3}` : `${p1} ${p2}`;
        });

        e.target.value = formatado;
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('numero_suporte_whatsapp');

    input.addEventListener('input', function(e) {
        let valor = e.target.value.replace(/\D/g, ''); // remove tudo que não for número

        // limita a 11 dígitos (DDD + 9 números)
        valor = valor.substring(0, 11);

        // aplica a máscara (99) 99999-9999
        if (valor.length > 6) {
            valor = valor.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
        } else if (valor.length > 2) {
            valor = valor.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
        } else {
            valor = valor.replace(/^(\d*)/, '($1');
        }

        e.target.value = valor;
    });
});
</script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('#mensagem_eleitor_email');

    if (!textarea) {
        console.error('❌ O elemento #mensagem_eleitor_email não foi encontrado no DOM.');
        return;
    }

    ClassicEditor.create(textarea, {
        language: 'pt-br',
        toolbar: [
            'undo', 'redo', '|',
            'heading', '|',
            'bold', 'italic', 'underline', '|',
            'link', 'bulletedList', 'numberedList', '|',
            'blockQuote', 'insertTable', 'mediaEmbed'
        ],
        // ⚙️ Permitir HTML completo
        htmlSupport: {
            allow: [
                {
                    name: /.*/,               // todas as tags
                    attributes: true,         // todos os atributos
                    classes: true,            // todas as classes
                    styles: true              // todos os estilos inline
                }
            ]
        },
        // ⚙️ Permitir colagem de HTML bruto (sem sanitização)
        htmlEmbed: {
            showPreviews: true
        }
    })
    .then(editor => {
        // aumentar a altura
        editor.ui.view.editable.element.style.minHeight = '350px';
        console.log('✅ CKEditor inicializado com suporte total a HTML.');
        window.editorMensagemEleitor = editor;
    })
    .catch(error => {
        console.error('❌ Erro ao inicializar o CKEditor:', error);
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('#mensagem_eleitor_sms');

    if (!textarea) {
        console.error('❌ O elemento #mensagem_eleitor_sms não foi encontrado no DOM.');
        return;
    }

    ClassicEditor.create(textarea, {
        language: 'pt-br',
        toolbar: [
            'undo', 'redo', '|',
            'heading', '|',
            'bold', 'italic', 'underline', '|',
            'link', 'bulletedList', 'numberedList', '|',
            'blockQuote', 'insertTable', 'mediaEmbed'
        ],
        // ⚙️ Permitir HTML completo
        htmlSupport: {
            allow: [
                {
                    name: /.*/,               // todas as tags
                    attributes: true,         // todos os atributos
                    classes: true,            // todas as classes
                    styles: true              // todos os estilos inline
                }
            ]
        },
        // ⚙️ Permitir colagem de HTML bruto (sem sanitização)
        htmlEmbed: {
            showPreviews: true
        }
    })
    .then(editor => {
        // aumentar a altura
        editor.ui.view.editable.element.style.minHeight = '350px';
        console.log('✅ CKEditor inicializado com suporte total a HTML.');
        window.editorMensagemEleitor = editor;
    })
    .catch(error => {
        console.error('❌ Erro ao inicializar o CKEditor:', error);
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('#termos');

    if (!textarea) {
        console.error('❌ O elemento #termos não foi encontrado no DOM.');
        return;
    }

    ClassicEditor.create(textarea, {
        language: 'pt-br',
        toolbar: [
            'undo', 'redo', '|',
            'heading', '|',
            'bold', 'italic', 'underline', '|',
            'link', 'bulletedList', 'numberedList', '|',
            'blockQuote', 'insertTable', 'mediaEmbed'
        ],
        // ⚙️ Permitir HTML completo
        htmlSupport: {
            allow: [
                {
                    name: /.*/,               // todas as tags
                    attributes: true,         // todos os atributos
                    classes: true,            // todas as classes
                    styles: true              // todos os estilos inline
                }
            ]
        },
        // ⚙️ Permitir colagem de HTML bruto (sem sanitização)
        htmlEmbed: {
            showPreviews: true
        }
    })
    .then(editor => {
        // aumentar a altura
        editor.ui.view.editable.element.style.minHeight = '350px';
        console.log('✅ CKEditor inicializado com suporte total a HTML.');
        window.editorMensagemEleitor = editor;
    })
    .catch(error => {
        console.error('❌ Erro ao inicializar o CKEditor:', error);
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnSalvar = document.getElementById('btn-salvar');
    const tabLinks = document.querySelectorAll('.nav-link[data-bs-toggle="tab"]');

    if (!btnSalvar || !tabLinks.length) return;

    function atualizarBotao(event) {
        // event.target é a aba que foi clicada
        const targetTab = event.target.getAttribute('data-bs-target'); // ex: #admin-tab-pane

        if (targetTab === '#admin-tab-pane') {
            btnSalvar.style.display = 'none';
        } else {
            btnSalvar.style.display = 'block';
        }
    }

    tabLinks.forEach(link => {
        link.addEventListener('shown.bs.tab', atualizarBotao);
    });

    // Executa na carga inicial da página
    const abaAtiva = document.querySelector('.tab-pane.show.active');
    if (abaAtiva && abaAtiva.id === 'admin-tab-pane') {
        btnSalvar.style.display = 'none';
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-reiniciar');
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const modalFooterButtons = document.getElementById('modal-footer-buttons');

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        // Oculta os botões
        modalFooterButtons.style.display = 'none';
        // Mostra o loader
        progressContainer.style.display = 'block';

        // Simula progresso (para visual feedback)
        let progress = 0;
        const interval = setInterval(() => {
            if (progress >= 100) {
                clearInterval(interval);
                form.submit(); // Envia o form real ao atingir 100%
            } else {
                progress += 10;
                progressBar.style.width = `${progress}%`;
                progressBar.textContent = `${progress}%`;
                progressBar.setAttribute('aria-valuenow', progress);
            }
        }, 200); // 2 segundos total (ajuste se quiser mais rápido/lento)
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("form-reiniciar");
    const progressContainer = document.getElementById("progress-container");
    const progressBar = document.getElementById("progress-bar");
    const modalFooter = document.getElementById("modal-footer-buttons");

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        // Esconde os botões e mostra o loader
        modalFooter.style.display = "none";
        progressContainer.style.display = "block";
        progressBar.style.width = "0%";
        progressBar.innerText = "0%";

        let progress = 0;
        const interval = setInterval(() => {
            progress += 10;
            progressBar.style.width = progress + "%";
            progressBar.innerText = progress + "%";
            progressBar.setAttribute("aria-valuenow", progress);

            if (progress >= 100) {
                clearInterval(interval);

                // Após a barra completar, envia o form real
                fetch(form.action, {
                    method: form.method,
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: new FormData(form)
                })
                .then(response => {
                    if (!response.ok) throw new Error("Erro no servidor");
                    return response.json().catch(() => ({}));
                })
                .then(() => {
                    progressBar.classList.remove("bg-danger");
                    progressBar.classList.add("bg-success");
                    progressBar.innerText = "Reinício concluído!";

                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('confirmResetModal'));
                        modal.hide();
                        window.location.reload(); // Recarrega a página
                    }, 1500);
                })
                .catch(() => {
                    progressBar.classList.remove("bg-danger");
                    progressBar.classList.add("bg-warning");
                    progressBar.innerText = "Erro ao reiniciar!";
                    setTimeout(() => window.location.reload(), 2000);
                });
            }
        }, 200);
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cpfInputPresidente = document.getElementById('cpf_presidente');
    const cpfInputMembro1 = document.getElementById('cpf_mebro_1');
    const cpfInputMembro2 = document.getElementById('cpf_mebro_2');
    cpfInputPresidente.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // remove tudo que não é número
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        this.value = value;
    });
    cpfInputMembro1.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // remove tudo que não é número
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        this.value = value;
    });
    cpfInputMembro2.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // remove tudo que não é número
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        this.value = value;
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const celularInputPresidente = document.getElementById('celular_presidente');
    const celularInputMembro1 = document.getElementById('celular_mebro_1');
    const celularInputMembro2 = document.getElementById('celular_mebro_2');
    celularInputPresidente.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // remove tudo que não é número

        if (value.length > 11) value = value.slice(0, 11);

        if (value.length > 10) {
            // formato com nono dígito: (99) 99999-9999
            value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
        } else if (value.length > 5) {
            // formato sem nono dígito: (99) 9999-9999
            value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
        } else {
            value = value.replace(/^(\d*)/, '($1');
        }

        this.value = value;
    });
    celularInputMembro1.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // remove tudo que não é número

        if (value.length > 11) value = value.slice(0, 11);

        if (value.length > 10) {
            // formato com nono dígito: (99) 99999-9999
            value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
        } else if (value.length > 5) {
            // formato sem nono dígito: (99) 9999-9999
            value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
        } else {
            value = value.replace(/^(\d*)/, '($1');
        }

        this.value = value;
    });
    celularInputMembro2.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // remove tudo que não é número

        if (value.length > 11) value = value.slice(0, 11);

        if (value.length > 10) {
            // formato com nono dígito: (99) 99999-9999
            value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
        } else if (value.length > 5) {
            // formato sem nono dígito: (99) 9999-9999
            value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
        } else {
            value = value.replace(/^(\d*)/, '($1');
        }

        this.value = value;
    });
});
</script>
<!---------------- Final - Scripts JavaScript e Jquery ------------------ -->
<!---------------- Início - Estillos CSS -------------------->
<style>
    .fundo-botao {
        background: linear-gradient(135deg, #183F77, #4A90E2);
        color: #fff !important;
    }
    .fundo-botao:hover {
        background: linear-gradient(135deg, #122b55, #3570c2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
    .botao-reiniciar {
        background-color: #dc3545 !important;
        color: #fff !important;
    }
    .botao-reiniciar:hover {
        background: linear-gradient(135deg, #dc3545) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
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
    .bot-can-reiciciar {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        color: #fff !important;
    }
    .bot-can-reiciciar:hover {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
    .bot-reiniciar {
        background-color: #dc3545 !important;
        color: #fff !important;
    }
    .bot-reiniciar:hover {
        background: linear-gradient(135deg, #dc3545) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
    .toggle-senha-btn { cursor: pointer; }
    .cp-tr { cursor: pointer; }
    .exibe-logo {
        width: 180px;
    }
    .ck-editor__editable {
        min-height: 150px !important;
    }
    .ck-editor__editable_inline {
        min-height: 150px !important;
    }
    .ort-mtabs {
        margin-top: -75px;
    }
</style>
<!---------------- Final - Estillos CSS -------------------->