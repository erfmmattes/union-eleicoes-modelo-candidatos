@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Zerésima de Votos')

@section('content')
<div class="container">
    <div class="mb-5 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h2 fw-bolder text-dark">Zerésima de Votos</h1>
            <p class="text-muted mt-1">
                Verificação inicial da urna - deve estar zerada antes do início da eleição.
            </p>
        </div>
        <div class="d-flex">
            <button type="button" class="btn botao-geral" title="Gerar PDF" data-bs-toggle="modal" data-bs-target="#divZerModalPdf">
                <i class="fa-solid fa-file-pdf me-2"></i>
            </button>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body text-center">
            <h4 class="{{ $dados['total_votos'] === 0 ? 'text-success' : 'text-danger' }}">
                <i class="fa-solid {{ $dados['total_votos'] === 0 ? 'fa-circle-check' : 'fa-triangle-exclamation' }} me-2"></i>
                {{ $dados['status'] }}
            </h4>

            <p class="mt-3">
                Total de votos registrados: <strong>{{ $dados['total_votos'] }}</strong>
            </p>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="divZerModalPdf" tabindex="-1" aria-labelledby="modalPdfLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header text-white fund-cont">
                <h5 class="modal-title m-auto" id="modalPdfLabel">Gerar PDF - Zerésima de Votos</h5>
            </div>
            <div>
                <form id="zerFormGerarPdf" target="_blank" action="{{ route('admin.adminZeresima.gerarPdf') }}" method="POST">
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
                                placeholder="Ex: zeresima_de_votos">
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
@endsection
<!---------------- Início - Estilos CSS -------------------->
<style>
.botao-geral {
    background: linear-gradient(135deg, #183F77, #4A90E2);
    color: #fff !important;
    height: 50px;
    display: flex !important;          
    align-items: center !important;   
    justify-content: center !important;
}
.botao-geral:hover {
    background: linear-gradient(135deg, #122b55, #3570c2);
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
</style>
<!---------------- Final - Estilos CSS -------------------->
<!---------------- Início - Scripts JavaScript -------------------->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('zerFormGerarPdf');
        const modal = document.getElementById('divZerModalPdf');

        form.addEventListener('submit', function () {
            // Fecha o modal
            const bsModal = bootstrap.Modal.getInstance(modal);
            if(bsModal) bsModal.hide();
        });
    });
</script>
<!---------------- Final - Scripts JavaScript -------------------->