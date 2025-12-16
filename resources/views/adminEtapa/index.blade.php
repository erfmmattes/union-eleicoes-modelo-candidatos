@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Etapas de Candidatos')

@section('content')
<div class="container">
    <div class="container-fluid">

        <!-- Cabeçalho -->
        <div class="mb-5">
            <h1 class="h2 fw-bolder text-dark">Gerenciamento de Etapas de Candidatos</h1>
            <p class="text-muted mt-1">
                Crie, organize e controle as etapas de escolha dos candidatos.
            </p>
        </div>

        <!-- Filtro / Busca -->
        <div class="card shadow-lg border-0 rounded-3 stat-card mb-4 px-4 py-3">
            <form action="{{ route('admin.adminEtapa.index') }}" method="GET" class="row g-2 align-items-center form-o">

                <div class="col-md-6">
                    <input type="text" name="q" value="{{ request('q') }}" 
                           class="form-control tam-in" placeholder="Pesquisar por etapa">
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select tam-in">
                        <option value="">Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Ativo</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex">
                    <button type="submit" title="Buscar" class="btn botao-buscar me-2 w-100">
                        <i class="fas fa-search"></i>
                    </button>

                    @if($todasPermissoes['etapas']['criar'] === true)
                        <a href="{{ route('admin.adminEtapa.create') }}" 
                        title="Criar" 
                        class="btn botao-buscar w-100">
                            <i class="fas fa-plus"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabela -->
        <div class="card shadow-lg border-0 rounded-3 stat-card">
            <div class="card-body p-3 p-md-5">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Mensagens de Erro -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                <div class="mensagens-retorno"></div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light bod-tabled">
                            <tr>
                                <th>ID</th>
                                <th>Pauta</th>
                                <th>Ordem Voto</th>
                                @if($todasPermissoes['etapas']['editar'] === true)
                                    <th>Status</th>
                                @endif
                                <th class="text-center">Abre | Pula | Finaliza</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($etapas as $etapa)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $etapa->id }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($etapa->nome, 40) }}</td>
                                    <td>{{ $etapa->sequencia }}</td>
                                    {{-- STATUS --}}
                                    <td class="col-status"
                                        data-status="{{ $etapa->status }}">
                                    </td>

                                    <td class="col-botoes text-center"
                                        data-status="{{ $etapa->status }}"
                                        data-id="{{ $etapa->id }}">
                                    </td>

                                    {{-- AÇÕES PADRÃO --}}
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center flex-column flex-md-row">

                                            @if($todasPermissoes['etapas']['ver'] === true)
                                                <a href="{{ route('admin.adminEtapa.show', $etapa->id) }}" title="Ver"
                                                class="btn btn-sm btn-outline-primary mb-2 mb-md-0 me-md-2">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            @if($todasPermissoes['etapas']['editar'] === true)
                                                <a href="{{ route('admin.adminEtapa.edit', $etapa->id) }}" title="Editar"
                                                class="btn btn-sm btn-outline-primary mb-2 mb-md-0 me-md-2">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            @if($todasPermissoes['etapas']['deletar'] === true)
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Excluir"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteEtapa{{ $etapa->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        Nenhuma etapa encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginação (se existir) --}}
                @if(method_exists($etapas, 'links'))
                    <div class="mt-4 custom-pagination-wrapper">
                        {{ $etapas->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<!-- Modais de Exclusão -->
@foreach ($etapas as $etapa)
    <div class="modal fade" id="deleteEtapa{{ $etapa->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title m-auto">Confirmar Exclusão</h5>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="fs-6">Deseja realmente excluir a etapa <strong>{{ $etapa->nome }}</strong>?</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn bot-cancela px-4" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('admin.adminEtapa.destroy', $etapa->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn botao-confirmar px-4">Sim, Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Modal de Erro de Validação -->
<div class="modal fade" id="modalErroValidacao" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title m-auto">Validação Necessária</h5>
            </div>

            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <p class="fs-6" id="textoErroValidacao"></p>
            </div>

            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn bot-cancela px-4" data-bs-dismiss="modal">
                    Ok, entendi
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
<!---------------- Início - Estillos CSS -------------------->
<style>
.form-o { margin: 0; }
.tam-in { height: 50px; }
.botao-buscar {
    background: linear-gradient(135deg, #183F77, #4A90E2);
    color: #fff !important;
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
.botao-criar {
    background-color: #00B070;
    color: #fff !important;
    padding-top: 15px !important;
}
.botao-criar:hover {
    background-color: #00B070;
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
.bot-cancela {
    background: linear-gradient(135deg, #6c757d, #6c757d);
    color: #fff !important;
}
.bot-cancela:hover {
    background: linear-gradient(135deg, #6c757d, #6c757d);
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
.bg-purple {
    background-color: #C8A2FF !important;
    color: #442477 !important;
}
.bg-warning {
    background-color: #FFEAB5 !important;
    color: #8C6A00 !important;
    font-weight: 600;
}
.bg-success {
    background-color: #C8FFE0 !important;
    color: #007744 !important;
    font-weight: 600;
}
.texto-padrao {
    color: #ffffff !important;
    font-weight: 600 !important;
}
</style>
<!---------------- Final - Estillos CSS -------------------->
<!---------------- Início - Scripts JavaScript e Jquery ------------------ -->
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
document.addEventListener("DOMContentLoaded", () => {

    const routes = {
        abrir:      "{{ route('admin.adminEtapa.abrir', ['id' => 'ID']) }}",
        pular:      "{{ route('admin.adminEtapa.pular', ['id' => 'ID']) }}",
        finalizar:  "{{ route('admin.adminEtapa.finalizar', ['id' => 'ID']) }}"
    };

    const statusMap = {
        0: { label: 'AGUARDANDO', color: 'bg-warning' },
        1: { label: 'ATIVA',      color: 'bg-success texto-padrao' },
        2: { label: 'FINALIZADA', color: 'bg-purple' },
        3: { label: 'PULADA',     color: 'bg-secondary' }
    };

    /* ===============================
       RENDERIZA UMA LINHA
    =============================== */
    function renderRow(row, status) {
        status = Number(status);

        const tdStatus  = row.querySelector('.col-status');
        const tdButtons = row.querySelector('.col-botoes');
        const id        = tdButtons.dataset.id;

        const cfg = statusMap[status];
        if (!cfg) return;

        // STATUS
        tdStatus.innerHTML = `
            <span class="badge ${cfg.color}">
                ${cfg.label}
            </span>
        `;

        // BOTÕES
        if (status === 0) {
            tdButtons.innerHTML = `
                <button class="btn btn-sm btn-info me-1 btn-etapa texto-padrao"
                    data-id="${id}" data-action="abrir">ABRIR</button>
                <button class="btn btn-sm btn-warning me-1 btn-etapa texto-padrao"
                    data-id="${id}" data-action="pular">PULAR</button>
            `;
        }
        else if (status === 1) {
            tdButtons.innerHTML = `
                <button class="btn btn-sm btn-primary btn-etapa texto-padrao"
                    data-id="${id}" data-action="finalizar">FINALIZAR</button>
            `;
        }
        else {
            tdButtons.innerHTML = `<span class="text-muted">—</span>`;
        }

        // mantém DOM sincronizado
        tdStatus.dataset.status  = status;
        tdButtons.dataset.status = status;
    }

    /* ===============================
       RENDERIZA AO CARREGAR
    =============================== */
    document.querySelectorAll("tbody tr").forEach(row => {
        const tdStatus = row.querySelector(".col-status");
        if (!tdStatus) return;

        renderRow(row, tdStatus.dataset.status);
    });

    /* ===============================
       CLICK (EVENT DELEGATION)
    =============================== */
    document.addEventListener("click", async (e) => {

        const btn = e.target.closest(".btn-etapa");
        if (!btn) return;

        const id     = btn.dataset.id;
        const action = btn.dataset.action;
        const row    = btn.closest("tr");

        if (!routes[action]) return;

        try {
            const res = await fetch(
                routes[action].replace("ID", id),
                {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    }
                }
            );

            const data = await res.json();

            if (!data.success) {
                const textoModal = document.getElementById('textoErroValidacao');
                textoModal.textContent = data.message || "Erro ao processar ação.";

                const modal = new bootstrap.Modal(document.getElementById('modalErroValidacao'));
                modal.show();

                return;
            }

            renderRow(row, data.status);

        } catch (err) {
            alert("Erro de comunicação com o servidor.");
        }
    });

});
</script>
<!---------------- Final - Scripts JavaScript e Jquery ------------------ -->