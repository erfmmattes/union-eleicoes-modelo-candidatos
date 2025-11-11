@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Eleitores')

@section('content')
<div class="container">
    <div class="container-fluid">

        <!-- Cabeçalho -->
        <div class="mb-5">
            <h1 class="h2 fw-bolder text-dark">Gerenciamento de Eleitores</h1>
            <p class="text-muted mt-1">
                Cadastre, edite, ative ou inative eleitores do sistema.
            </p>
        </div>

        <!-- Filtro / Busca -->
        <div class="card shadow-lg border-0 rounded-3 stat-card mb-4 px-4 py-3">
            <form action="{{ route('admin.adminEleitores.index') }}" method="GET" class="row g-2 align-items-center form-o">
                <div class="col-md-5">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control tam-in" placeholder="Pesquisar por nome, e-mail ou CPF/CNPJ">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select tam-in">
                        <option value="">Todos</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Ativos</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex">
                    <button type="submit" title="Buscar" class="btn botao-geral me-2 w-100">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($todasPermissoes['eleitores']['criar'] === true)
                        <a href="{{ route('admin.adminEleitores.create') }}" title="Cadastrar Eleitor" class="btn botao-geral w-100 me-2">
                            <i class="fas fa-plus"></i>
                        </a>
                    @endif
                    @if($todasPermissoes['eleitores']['importar_eleitores'] === true)
                        <a href="{{ route('admin.adminEleitores.importar') }}" title="Importar Eleitores" class="btn botao-geral w-100 me-2">
                            <i class="fa-solid fa-file-import"></i>
                        </a>
                    @endif
                    @if($todasPermissoes['eleitores']['enviar_senha'] === true)
                        <a href="{{ route('admin.adminEleitores.metodosDeEnviarSenha') }}" title="Todos os Métodos de Enviar Senha para os Eleitores" class="btn botao-geral w-100">
                            <i class="fa-solid fa-envelope"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabela de Eleitores -->
        <div class="card shadow-lg border-0 rounded-3 stat-card">
            <div class="card-body p-3 p-md-5">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                <!-- Mensagens de Erro -->
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
                                <th>E-mail</th>
                                <th>CPF/CNPJ</th>
                                <th>Celular</th>
                                @if($todasPermissoes['eleitores']['editar'] === true)
                                    <th>Status</th>
                                @endif
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($eleitores as $eleitor)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $eleitor->id }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($eleitor->nome, 40) }}</td>
                                    <td>{{ $eleitor->email }}</td>
                                    <td>{{ formatarCpfCnpj($eleitor->cpf_cnpj) }}</td>
                                    <td>{{ formatarTelefone($eleitor->celular) }}</td>
                                    @if($todasPermissoes['eleitores']['editar'] === true)
                                        <td>
                                            <button class="btn btn-sm toggle-votou-btn {{ $eleitor->status ? 'btn-success' : 'btn-secondary' }}" 
                                                    data-id="{{ $eleitor->id }}">
                                                {{ $eleitor->status ? 'Ativo' : 'Inativo' }}
                                            </button>
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center flex-column flex-md-row">
                                            @if($todasPermissoes['eleitores']['enviar_senha'] === true)
                                            <a href="{{ route('admin.adminEleitores.individualEnviarSenha', $eleitor->id) }}"
                                                title="Enviar senha por E-mail e SMS"
                                                class="btn btn-sm btn-outline-success mb-2 mb-md-0 me-md-2">
                                                    <i class="fas fa-paper-plane"></i>
                                            </a>
                                            @endif

                                            @if($todasPermissoes['eleitores']['ver'] === true)
                                                <a href="{{ route('admin.adminEleitores.show', $eleitor->id) }}" title="Ver" class="btn btn-sm btn-outline-primary mb-2 mb-md-0 me-md-2">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            @if($todasPermissoes['eleitores']['editar'] === true)
                                                <a href="{{ route('admin.adminEleitores.edit', $eleitor->id) }}" title="Editar" class="btn btn-sm btn-outline-primary mb-2 mb-md-0 me-md-2">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            @if($todasPermissoes['eleitores']['deletar'] === true)
                                                <button type="button" class="btn btn-sm btn-outline-danger" title="Excluir"
                                                        data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $eleitor->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
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

<!-- Modais de Confirmação -->
@foreach ($eleitores as $eleitor)
    <div class="modal fade" id="confirmDeleteModal{{ $eleitor->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel{{ $eleitor->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title m-auto" id="confirmDeleteModalLabel{{ $eleitor->id }}">
                        Confirmar Exclusão
                    </h5>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="fs-6">Deseja realmente excluir o eleitor <strong>{{ $eleitor->nome }}</strong>?</p>
                    <p class="text-muted small">Esta ação não poderá ser desfeita.</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn bot-cancelar px-4" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('admin.adminEleitores.destroy', $eleitor->id) }}" method="POST">
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
<!---------------- Final - Estilos CSS -------------------->
<!---------------- Início - Scripts JavaScript -------------------->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Alerta temporário
    const alerts = document.querySelectorAll('.alert-temporaria');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Toggle de status
    const urlTemplate = "{{ route('admin.adminEleitores.status', ['id' => 'ID_ELEITOR']) }}";

    document.querySelectorAll('.toggle-votou-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const url = urlTemplate.replace('ID_ELEITOR', id);

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
            })
            .catch(err => console.error('Erro:', err));
        });
    });
});
</script>
<!---------------- Final - Scripts JavaScript -------------------->