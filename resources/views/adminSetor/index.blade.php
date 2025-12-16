@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Setores')

@section('content')
<div class="container">
    <div class="container-fluid">

        <!-- Cabeçalho -->
        <div class="mb-5">
            <h1 class="h2 fw-bolder text-dark">Gerenciamento de Setores</h1>
            <p class="text-muted mt-1">
                Crie, organize e controle os setores usados no sistema.
            </p>
        </div>

        <!-- Filtro / Busca -->
        <div class="card shadow-lg border-0 rounded-3 stat-card mb-4 px-4 py-3">
            <form action="{{ route('admin.adminSetor.index') }}" method="GET" class="row g-2 align-items-center form-o">

                <div class="col-md-6">
                    <input type="text" name="q" value="{{ request('q') }}"
                           class="form-control tam-in" placeholder="Pesquisar por nome">
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

                @if($todasPermissoes['setores']['criar'] === true)
                    <a href="{{ route('admin.adminSetor.create') }}"
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

                <!-- Sucesso -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Erros -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="mensagens-retorno"></div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light bod-tabled">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                @if($todasPermissoes['setores']['editar'] === true)
                                    <th>Status</th>
                                @endif
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($setores as $setor)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $setor->id }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($setor->nome, 40) }}</td>

                                    @if($todasPermissoes['setores']['editar'] === true)
                                        <td>
                                            <button class="btn btn-sm toggle-status-btn {{ $setor->status ? 'btn-success' : 'btn-secondary' }}"
                                                    data-id="{{ $setor->id }}">
                                                {{ $setor->status ? 'Ativo' : 'Inativo' }}
                                            </button>
                                        </td>
                                    @endif

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center flex-column flex-md-row">

                                            @if($todasPermissoes['setores']['ver'] === true)
                                                <a href="{{ route('admin.adminSetor.show', $setor->id) }}"
                                                title="Ver"
                                                class="btn btn-sm btn-outline-primary mb-2 mb-md-0 me-md-2">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            @if($todasPermissoes['setores']['editar'] === true)
                                                <a href="{{ route('admin.adminSetor.edit', $setor->id) }}"
                                                title="Editar"
                                                class="btn btn-sm btn-outline-primary mb-2 mb-md-0 me-md-2">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            @if($todasPermissoes['setores']['deletar'] === true)
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Excluir"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteSetor{{ $setor->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        Nenhum setor encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <!-- Paginação -->
                @if(method_exists($setores, 'links'))
                    <div class="mt-4 custom-pagination-wrapper">
                        {{ $setores->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<!-- Modais de exclusão -->
@foreach ($setores as $setor)
    <div class="modal fade" id="deleteSetor{{ $setor->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title m-auto">Confirmar Exclusão</h5>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="fs-6">
                        Deseja realmente excluir o setor
                        <strong>{{ $setor->nome }}</strong>?
                    </p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn bot-cancela px-4" data-bs-dismiss="modal">Cancelar</button>

                    <form action="{{ route('admin.adminSetor.destroy', $setor->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn botao-confirmar px-4">Sim, Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
<!---------------- Início - Estilos CSS -------------------->
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

.botao-confirmar {
    background-color: #dc3545 !important;
    color: #fff !important;
}
.botao-confirmar:hover {
    background: linear-gradient(135deg, #dc3545) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.bot-cancela {
    background: linear-gradient(135deg, #6c757d, #6c757d);
    color: #fff !important;
}
.bot-cancela:hover {
    background: linear-gradient(135deg, #6c757d, #6c757d);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
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
}
.custom-pagination-wrapper .page-item:hover .page-link {
    transform: translateY(-3px) !important;
    background-color: #e9f0fb !important;
    color: #122b55 !important;
}
</style>
<!---------------- Final - Estilos CSS -------------------->
<!---------------- Scripts -------------------->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.alert-temporaria').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity .5s';
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>
<script>
function mostrarAlertaErro(msg) {
    const container = document.querySelector('.mensagens-retorno');

    const alerta = document.createElement('div');
    alerta.className = "alert alert-danger alert-dismissible fade show alert-temporaria";
    alerta.role = "alert";
    alerta.innerHTML = `
        ${msg}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    `;

    container.appendChild(alerta);

    setTimeout(() => alerta.remove(), 5000);
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlTemplate = "{{ route('admin.adminSetor.status', ['id' => 'ID_SETOR']) }}";
    
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const url = urlTemplate.replace('ID_SETOR', id);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
            .then(res => res.json())
            .then(data => {
                console.log("data",data);
                if (data.success) {
                    if (data.status) {
                        this.classList.remove('btn-secondary');
                        this.classList.add('btn-success');
                        this.textContent = 'Ativo';
                    } else {
                        this.classList.remove('btn-success');
                        this.classList.add('btn-secondary');
                        this.textContent = 'Inativo';
                    }
                }
                if (!data.success) {
                    mostrarAlertaErro(data.message);
                    return;
                }
            });
        });
    });
});
</script>
<!---------------- Final - Scripts -------------------->