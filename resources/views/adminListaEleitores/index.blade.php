@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Lista de Eleitores')

@section('content')
<div class="container">
    <div class="container-fluid">

        <!-- Cabeçalho -->
        <div class="mb-5">
            <h1 class="h2 fw-bolder text-dark">
                Lista de Eleitores
            </h1>
            <p class="text-muted mt-1">Lista de eleitores cadastrados no sistema para exportar.</p>
        </div>

        <!-- Filtro / Busca -->
        <div class="card shadow-lg border-0 rounded-3 stat-card mb-4 px-4 py-3">
            <form action="{{ route('admin.adminListaEleitores.index') }}" method="GET" class="row g-2 align-items-center form-o">
                <div class="col-md-6">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control tam-in" placeholder="Pesquisar por nome, e-mail ou CPF/CNPJ">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select tam-in">
                        <option value="">Todos</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Ativos</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex">
                    <button type="submit" title="Buscar" class="btn botao-geral me-2 w-100">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="btn botao-buscar me-2 w-100" title="Baixar Lista de Eleitores em PDF" data-bs-toggle="modal" data-bs-target="#gerarModalPdfListaDeEleitores">
                        <i class="fa-solid fa-file-pdf"></i>
                    </button>
                    <a data-bs-toggle="modal" data-bs-target="#listaGerarModalExcel"title="Exportar Excel" class="btn botao-buscar w-100 me-3">
                        <i class="fa-solid fa-file-excel"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Início - Modal Baixar Lista de Eleitores em PDF -->
        <div class="modal fade" id="gerarModalPdfListaDeEleitores" tabindex="-1" aria-labelledby="modalPdfVotantesLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <div class="modal-header text-white fund-cont">
                        <h5 class="modal-title m-auto" id="modalPdfVotantesLabel">Gerar PDF - Lista de Eleitores</h5>
                    </div>
                    <div>
                        <form id="listaEleitoresFormGerarPdf" target="_blank" action="{{ route('admin.adminListaEleitores.gerarPdf') }}" method="POST">
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
                                        placeholder="Ex: lista_de_eleitores">
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
        <!-- Final - Modal Baixar Lista de Eleitores em PDF -->

        <!-- Modal Excel -->
        <div class="modal fade" id="listaGerarModalExcel" tabindex="-1" aria-labelledby="modalExcelLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <div class="modal-header text-white fund-cont">
                        <h5 class="modal-title m-auto" id="modalExcelLabel">Gerar Excel - Lista de Eleitores</h5>
                    </div>
                    <form id="ListaExcelForm" target="_blank" action="{{ route('admin.adminListaEleitores.exportarExcel') }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="nome_arquivo" class="form-label fw-semibold">Nome do arquivo:</label>
                                <input type="text" class="form-control" id="nome_arquivo" name="nome_arquivo" placeholder="Ex: lista_de_eleitores">
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
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="email" checked>
                                    <label class="form-check-label">E-mail</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="celular" checked>
                                    <label class="form-check-label">Celular</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="passou_por_ajuste" checked>
                                    <label class="form-check-label">Atualizou</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="recuperacao_senha" checked>
                                    <label class="form-check-label">Recuperação de Senha</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="troca_senha" checked>
                                    <label class="form-check-label">Troca de Senha</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="status" checked>
                                    <label class="form-check-label">Status</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="created_at" checked>
                                    <label class="form-check-label">Data do Cadastro</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="campos[]" value="updated_at" checked>
                                    <label class="form-check-label">Data da Atualização</label>
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

        <!-- Tabela -->
        <div class="card shadow-lg border-0 rounded-3 stat-card">
            <div class="card-body p-3 p-md-5">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light bod-tabled">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>CPF/CNPJ</th>
                                <th>E-mail</th>
                                <th>Celular</th>
                                <th>Atualizado</th>
                                <th>Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($eleitores as $eleitor)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $eleitor->id }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($eleitor->nome, 40) }}</td>
                                    <td>{{ formatarCpfCnpj($eleitor->cpf_cnpj) }}</td>
                                    <td>{{ $eleitor->email }}</td>
                                    <td>{{ formatarTelefone($eleitor->celular) }}</td>
                                    <td>
                                        @if($eleitor->passou_por_ajuste == '1')
                                            <span class="badge bg-success">Sim</span>
                                        @else
                                            <span class="badge bg-secondary">Não</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($eleitor->status == '1')
                                            <span class="badge bg-success">Ativo</span>
                                        @else
                                            <span class="badge bg-secondary">Inativo</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.adminListaEleitores.show', $eleitor->id) }}" title="Ver" class="btn btn-sm btn-outline-primary mb-2 mb-md-0 me-md-2">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        Nenhum eleitor encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginação --}}
                @if(method_exists($eleitores, 'links'))
                    <div class="mt-4 custom-pagination-wrapper">
                        {{ $eleitores->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
<!---------------- Início - Estilos CSS -------------------->
<style>
.form-o { margin: 0; }
.tam-in { height: 50px; }
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
}
.custom-pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #183F77, #4A90E2) !important;
    color: #fff !important;
    border: none !important;
}
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
        const form = document.getElementById('listaEleitoresFormGerarPdf');
        const modal = document.getElementById('gerarModalPdfListaDeEleitores');

        form.addEventListener('submit', function () {
            // Fecha o modal
            const bsModal = bootstrap.Modal.getInstance(modal);
            if(bsModal) bsModal.hide();
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('ListaExcelForm');
        const modal = document.getElementById('listaGerarModalExcel');

        form.addEventListener('submit', function () {
            // Fecha o modal
            const bsModal = bootstrap.Modal.getInstance(modal);
            if(bsModal) bsModal.hide();
        });
    });
</script>
<!---------------- Final - Scripts JavaScript -------------------->