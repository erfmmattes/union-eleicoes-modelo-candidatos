@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Detalhes do Setor')

@section('content')
<div class="container">

    <!-- Cabeçalho -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Detalhes do Setor</h1>
        <p class="text-muted mt-1">
            Visualize as informações completas do setor selecionado.
        </p>
    </div>

    <!-- Card de Visualização -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">

            <!-- Detalhes -->
            <div class="row mb-3">

                <div class="col-md-12 mb-3">
                    <label class="form-label fw-semibold">Nome do Setor</label>
                    <div class="form-control bg-light">{{ $setor->nome }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <div class="mt-1">
                        @if($setor->status)
                            <span class="badge bg-success px-3 py-2">Ativo</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">Inativo</span>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Botões -->
            <div class="d-flex justify-content-end gap-3 mt-4">

                <a href="{{ route('admin.adminSetor.index') }}" class="btn bot-cancelar-ele px-4">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>

            </div>

        </div>
    </div>

</div>
@endsection
<!---------------- Início - Estilos CSS -------------------->
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
<!---------------- Final - Estilos CSS -------------------->