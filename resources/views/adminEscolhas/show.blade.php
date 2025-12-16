@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Detalhes da Escolha')

@section('content')
<div class="container">

    <!-- Cabeçalho -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Detalhes da Escolha</h1>
        <p class="text-muted mt-1">
            Visualize todas as informações da escolha selecionada.
        </p>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">

            <div class="row">

                <!-- Nome -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nome</label>
                    <div class="form-control bg-light">{{ $escolha->nome }}</div>
                </div>

                <!-- Cargo -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Cargo</label>
                    <div class="form-control bg-light">
                        {{ $escolha->branco_nulo_abstencao ? '---' : $escolha->cargo }}
                    </div>
                </div>

                <!-- Etapa -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Etapa</label>
                    <div class="form-control bg-light">
                        {{ $escolha->etapa->nome ?? 'Não definida' }}
                    </div>
                </div>

                <!-- Sequência -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Sequência</label>
                    <div class="form-control bg-light">{{ $escolha->sequencia }}</div>
                </div>

                <!-- Branco / Nulo / Abstenção -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tipo</label>
                    <div>
                        @if($escolha->branco_nulo_abstencao)
                            <span class="badge bg-warning text-dark px-3 py-2">Branco / Nulo / Abstenção</span>
                        @else
                            <span class="badge bg-primary px-3 py-2">Candidato Normal</span>
                        @endif
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <div>
                        @if($escolha->status)
                            <span class="badge bg-success px-3 py-2">Ativo</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">Inativo</span>
                        @endif
                    </div>
                </div>

            </div>


            <!-- Foto -->
            @if(!$escolha->branco_nulo_abstencao)

                <h5 class="fw-semibold mt-3 mb-3">Foto do Candidato</h5>

                <div class="col-md-12 mb-3">
                    @if($escolha->tem_foto)
                        <img src="{{ asset('storage/'.$escolha->caminho) }}" 
                             style="max-width: 200px;" 
                             class="rounded shadow-sm">
                    @else
                        <img src="{{ asset('img/outras/sem-foto.png') }}" 
                             style="max-width: 200px;" 
                             class="rounded shadow-sm">
                    @endif
                </div>
            @endif


            <!-- Botões -->
            <div class="d-flex justify-content-end gap-3 mt-4">
                
                <a href="{{ route('admin.adminEscolhas.index') }}" class="btn bot-cancelar-ele px-4">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>

            </div>

        </div>
    </div>

</div>
@endsection

<style>
    .bot-atualizar {
        background: linear-gradient(135deg, #183F77, #4A90E2);
        color: #fff !important;
    }
    .bot-atualizar:hover {
        background: linear-gradient(135deg, #122b55, #3570c2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }

    .bot-cancelar-ele {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        color: #fff !important;
    }
    .bot-cancelar-ele:hover {
        background: linear-gradient(135deg, #5c636a, #5c636a);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
</style>