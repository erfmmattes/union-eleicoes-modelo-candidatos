@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Ajuda')

@section('content')
<div class="container">

    <!-- Cabeçalho -->
    <div class="mb-4">
        <h1 class="h2 fw-bolder text-dark">Gerenciamento de Ajudas</h1>
        <p class="text-muted mt-1">
            Crie, edite, visualize e exclua as ajudas do sistema.
        </p>
    </div>

    <!-- Filtro / Busca -->
    <div class="card shadow-lg border-0 rounded-3 stat-card mb-4 px-4 py-3">
        <form action="{{ route('admin.adminAjuda.index') }}" method="GET" class="row g-2 align-items-center form-o">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control tam-in" placeholder="Pesquisar por título ou descrição">
            </div>
            <div class="col-md-3">
                <select name="ativo" class="form-select tam-in">
                    <option value="">Status</option>
                    <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" title="Buscar" class="btn botao-buscar w-100">
                    <i class="fas fa-search"></i>
                </button>
                @if($todasPermissoes['ajuda']['criar'] === true)
                    <a href="{{ route('admin.adminAjuda.create') }}" title="Criar" class="btn botao-buscar w-100">
                        <i class="fas fa-plus"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Lista de Ajuda -->
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
                            <th>Título</th>
                            <th>Sequência</th>
                            @if($todasPermissoes['ajuda']['editar'] === true)
                                <th>Status</th>
                            @endif
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ajudas as $ajuda)
                            <tr>
                                <td>{{ $ajuda->id }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($ajuda->titulo, 40) }}</td>
                                <td>{{ $ajuda->sequencia }}</td>
                                @if($todasPermissoes['ajuda']['editar'] === true)
                                    <td>
                                        <button class="btn btn-sm {{ $ajuda->ativo ? 'btn-success' : 'btn-secondary' }} toggle-ativo-btn"
                                                data-id="{{ $ajuda->id }}">
                                            {{ $ajuda->ativo ? 'Ativo' : 'Inativo' }}
                                        </button>
                                    </td>
                                @endif
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center flex-column flex-md-row">
                                        @if($todasPermissoes['ajuda']['ver'] === true)
                                            <a href="{{ route('admin.adminAjuda.show', $ajuda->id) }}" title="Ver" class="btn btn-sm btn-outline-primary mb-2 mb-md-0 me-md-2">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        @endif

                                        @if($todasPermissoes['ajuda']['editar'] === true)
                                            <a href="{{ route('admin.adminAjuda.edit', $ajuda->id) }}" title="Editar" class="btn btn-sm btn-outline-primary mb-2 mb-md-0 me-md-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        @if($todasPermissoes['ajuda']['deletar'] === true)
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Excluir" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $ajuda->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Nenhuma ajuda encontrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if(method_exists($ajudas, 'links'))
                <div class="mt-4 custom-pagination-wrapper">
                    {{ $ajudas->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>
</div>
<!-- Modais de Confirmação -->
@foreach ($ajudas as $ajuda)
    <div class="modal fade" id="confirmDeleteModal{{ $ajuda->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel{{ $ajuda->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title m-auto" id="confirmDeleteModalLabel{{ $ajuda->id }}">
                        Confirmar Exclusão
                    </h5>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="fs-6">Deseja realmente excluir a ajuda <strong>{{ $ajuda->titulo }}</strong>?</p>
                    <p class="text-muted small">Esta ação não poderá ser desfeita.</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn bot-cancela px-4" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('admin.adminAjuda.destroy', $ajuda->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn botao-confirmar px-4">Sim, Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
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
    background-color: #00B070 !important;
    color: #fff !important;
    padding-top: 15px !important;
}
.botao-criar:hover {
    background-color: #00B070 !important;
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
document.addEventListener('DOMContentLoaded', function() {
    const urlStatusTemplate = "{{ route('admin.adminAjuda.status', ['id' => 'ID_AJUDA']) }}";

    document.querySelectorAll('.toggle-ativo-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const url = urlStatusTemplate.replace('ID_AJUDA', id);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.ativo) {
                        this.classList.remove('btn-secondary');
                        this.classList.add('btn-success');
                        this.textContent = 'Ativo';
                    } else {
                        this.classList.remove('btn-success');
                        this.classList.add('btn-secondary');
                        this.textContent = 'Inativo';
                    }
                }
            })
            .catch(err => console.error(err));
        });
    });
});
</script>
<!---------------- Final - Scripts JavaScript e Jquery ------------------ -->