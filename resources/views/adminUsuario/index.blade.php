@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Usuários')

@section('content')
<div class="container">
    <div class="container-fluid">

        <!-- Cabeçalho -->
        <div class="mb-5 d-flex justify-content-between">
            <div>
                <h1 class="h2 fw-bolder text-dark">Gerenciamento de Usuários</h1>
                <p class="text-muted mt-1">
                    Cadastre, edite ou exclua usuários que têm acesso ao sistema.
                </p>
            </div>
            <!-- Botão Novo Usuário -->
            <div class="mb-4 d-flex">
                <a href="{{ route('admin.adminUsuario.create') }}" title="Novo Usuário" class="btn botao-geral">
                    <i class="fa-solid fa-user-plus me-2"></i>
                </a>
            </div>
        </div>

        <!-- Tabela de Usuários -->
        <div class="card shadow-lg border-0 rounded-3 stat-card">
            <div class="card-body p-3 p-md-5">
                
                <!-- Mensagens de feedback -->
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
                                <th>E-mail</th>
                                <th>Tipo de Usuário</th>
                                <th>Ativou Conta</th>
                                <th>Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuarios as $usuario)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $usuario->id }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($usuario->name, 40) }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>
                                        @if($usuario->tipo_usuario === 'admin')
                                            <span class="badge bg-success">Administrador</span>
                                        @else
                                            <span class="badge bg-secondary text-dark">Convidado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $usuario->conta_ativa ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $usuario->conta_ativa ? 'Sim' : 'Não' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm toggle-status-btn {{ $usuario->status ? 'btn-success' : 'btn-secondary' }}" 
                                                data-id="{{ $usuario->id }}">
                                            {{ $usuario->status ? 'Ativo' : 'Inativo' }}
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center flex-column flex-md-row">
                                            <a href="{{ route('admin.adminUsuario.show', $usuario->id) }}" 
                                               class="btn btn-sm btn-outline-primary mb-2 mb-md-0 me-md-2" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.adminUsuario.edit', $usuario->id) }}" 
                                               class="btn btn-sm btn-outline-primary mb-2 mb-md-0 me-md-2" title="Editar">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $usuario->id }}" 
                                                    title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        Nenhum usuário encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginação --}}
                @if(method_exists($usuarios, 'links'))
                    <div class="mt-4 custom-pagination-wrapper">
                        {{ $usuarios->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modais de Confirmação -->
@foreach ($usuarios as $usuario)
    <div class="modal fade" id="confirmDeleteModal{{ $usuario->id }}" tabindex="-1" 
         aria-labelledby="confirmDeleteModalLabel{{ $usuario->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title m-auto" id="confirmDeleteModalLabel{{ $usuario->id }}">
                        Confirmar Exclusão
                    </h5>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="fs-6">Deseja realmente excluir o usuário <strong>{{ $usuario->name }}</strong>?</p>
                    <p class="text-muted small">Esta ação não poderá ser desfeita.</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn bot-cancelar px-4" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('admin.adminUsuario.destroy', $usuario->id) }}" method="POST">
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
.alert-temporaria {
    transition: opacity 0.5s ease;
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

    // Toggle de status do usuário
    const urlTemplate = "{{ route('admin.adminUsuario.status', ['id' => 'ID_USUARIO']) }}";

    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const url = urlTemplate.replace('ID_USUARIO', id);

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
                } else {
                    console.error('Erro ao atualizar status:', data.message);
                }
            })
            .catch(err => console.error('Erro:', err));
        });
    });
});
</script>
<!---------------- Final - Scripts JavaScript -------------------->