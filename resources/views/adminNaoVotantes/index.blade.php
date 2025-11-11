@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Não Votantes')

@section('content')
<div class="container">
    <div class="container-fluid">

        <!-- Cabeçalho da Página -->
        <div class="mb-5">
            <h1 class="h2 fw-bolder text-dark">Não Votantes</h1>
            <p class="text-muted mt-1">
                Lista de eleitores que não votaram na eleição.
            </p>
        </div>

        <!-- Filtro / Busca -->
        <div class="card shadow-lg border-0 rounded-3 stat-card mb-4 px-4 py-3">
            <form action="{{ route('admin.adminNaoVotantes.index') }}" method="GET" class="d-flex align-items-center form-o">
                <input type="text" name="busca" value="{{ request('search') }}" class="form-control me-2 tam-in"
                       placeholder="Buscar votantes por nome, CPF/CNPJ">
                <div class="col-md-3 d-flex">
                    <button type="submit" class="btn botao-buscar me-2 w-100" title="Buscar">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="btn botao-buscar me-2 w-100" title="Baixar Relatório de Votantes em PDF" data-bs-toggle="modal" data-bs-target="#gerarModalPdfVotantes">
                        <i class="fa-solid fa-file-pdf me-2"></i>
                    </button>
                    <a data-bs-toggle="modal" data-bs-target="#gerarModalExcel" title="Exportar Excel" class="btn botao-buscar w-100">
                        <i class="fa-solid fa-file-excel me-2"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Início - Modal Baixar Relatório de Não Votantes em PDF -->
        <div class="modal fade" id="gerarModalPdfVotantes" tabindex="-1" aria-labelledby="modalPdfVotantesLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <div class="modal-header text-white fund-cont">
                        <h5 class="modal-title m-auto" id="modalPdfVotantesLabel">Gerar PDF - Não Votantes</h5>
                    </div>
                    <div>
                        <form id="votantesFormGerarPdf" target="_blank" action="{{ route('admin.adminNaoVotantes.gerarPdf') }}" method="POST">
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
                                        placeholder="Ex: relatorio_nao_votantes">
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
        <!-- Final - Modal Baixar Relatório de Votantes em PDF -->

        <!-- Modal Excel -->
        <div class="modal fade" id="gerarModalExcel" tabindex="-1" aria-labelledby="modalExcelLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <div class="modal-header text-white fund-cont">
                        <h5 class="modal-title m-auto" id="modalExcelLabel">Gerar Excel - Não Votantes</h5>
                    </div>
                    <form id="excelForm" target="_blank" action="{{ route('admin.adminNaoVotantes.exportarExcel') }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="nome_arquivo" class="form-label fw-semibold">Nome do arquivo:</label>
                                <input type="text" class="form-control" id="nome_arquivo" name="nome_arquivo" placeholder="Ex: relatorio_nao_votantes">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Campos:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="id" checked>
                                    <label class="form-check-label">ID</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="nome" checked>
                                    <label class="form-check-label">Nome</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="cpf_cnpj" checked>
                                    <label class="form-check-label">CPF/CNPJ</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="celular" checked>
                                    <label class="form-check-label">Celular</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="email" checked>
                                    <label class="form-check-label">E-mail</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="votou" checked>
                                    <label class="form-check-label">Votou</label>
                                </div>
                            </div>

                            <input type="hidden" name="busca" value="{{ request('busca') }}">
                        </div>

                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn bot-cancelar px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn bot-confirmar px-4">Gerar Excel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabela de Votantes -->
        <div class="card shadow-lg border-0 rounded-3 stat-card">
            <div class="card-body p-3 p-md-5">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light bod-tabled">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>CPF/CNPJ</th>
                                <th>Celular</th>
                                <th>E-mail</th>
                                <th>Votou</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($naoVotantes as $naoVotante)
                                <tr>
                                    <td>{{ $naoVotante->id }}</td>
                                    <td>{{ $naoVotante->nome }}</td>
                                    <td>{{ formatarCpfCnpj($naoVotante->cpf_cnpj) }}</td>
                                    <td>{{ formatarTelefone($naoVotante->celular) }}</td>
                                    <td>{{ $naoVotante->email }}</td>
                                    <td>
                                        @if($naoVotante->votou == '1')
                                            <span class="badge bg-success">Sim</span>
                                        @else
                                            <span class="badge bg-secondary">Não</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.adminNaoVotantes.show', $naoVotante->id) }}" title="Ver" class="btn btn-sm btn-outline-primary mb-2 mb-md-0 me-md-2">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        Nenhum não votante registrado até o momento.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Paginação -->
                    @if (method_exists($naoVotantes, 'links'))
                        <div class="mt-4 custom-pagination-wrapper">
                            {{ $naoVotantes->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!---------------- Início - Estilos CSS -------------------->
<style>
.form-o { margin: 0; }
.tam-in { height: 50px; }
.botao-buscar {
    background: linear-gradient(135deg, #183F77, #4A90E2);
    color: #ffffff!important;
    height: 50px;
    display: flex !important;          
    align-items: center !important;   
    justify-content: center !important;
}
.botao-buscar:hover {
    background: linear-gradient(135deg, #122b55, #3570c2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    font-weight: 600 !important;
}
.botao-confirmar {
    background-color: #dc3545 !important;
    color: #fff !important;
}
.botao-confirmar:hover {
    background: linear-gradient(135deg, #dc3545) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    font-weight: 600 !important;
}
.bot-cancelar {
    background: linear-gradient(135deg, #6c757d, #6c757d);
    color: #fff !important;
}
.bot-cancelar:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    font-weight: 600 !important;
}
.custom-pagination-wrapper .pagination {
    justify-content: center !important;
    margin-top: 1.5rem !important;
    flex-wrap: wrap !important;
}
.custom-pagination-wrapper .page-item .page-link {
    border: none !important;
    color: #183F77 !important;
    font-weight: 600 !important;
    border-radius: 10px !important;
    padding: 8px 12px !important;
    margin: 0 6px !important;
    background-color: #f8f9fa !important;
    transition: transform .12s ease, box-shadow .12s ease !important;
    box-shadow: none !important;
}
.custom-pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #183F77, #4A90E2) !important;
    color: #fff !important;
    border: none !important;
    box-shadow: 0 6px 18px rgba(74,144,226,0.12) !important;
}
.custom-pagination-wrapper .page-item:hover .page-link {
    transform: translateY(-3px) !important;
    background-color: #e9f0fb !important;
    color: #122b55 !important;
}
.custom-pagination-wrapper .page-item.disabled .page-link,
.custom-pagination-wrapper .page-item.disabled .page-link:hover {
    color: #adb5bd !important;
    background-color: transparent !important;
    transform: none !important;
    pointer-events: none !important;
}
.custom-pagination-wrapper .page-link:focus {
    box-shadow: none !important;
    outline: none !important;
}
</style>
<!---------------- Final - Estilos CSS -------------------->
<!---------------- Início - Scripts JavaScript -------------------->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-temporaria');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('votantesFormGerarPdf');
        const modal = document.getElementById('gerarModalPdfVotantes');

        form.addEventListener('submit', function () {
            // Fecha o modal
            const bsModal = bootstrap.Modal.getInstance(modal);
            if(bsModal) bsModal.hide();
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('excelForm');
        const modal = document.getElementById('gerarModalExcel');

        form.addEventListener('submit', function () {
            // Fecha o modal
            const bsModal = bootstrap.Modal.getInstance(modal);
            if(bsModal) bsModal.hide();
        });
    });
</script>
<!---------------- Final - Scripts JavaScript -------------------->