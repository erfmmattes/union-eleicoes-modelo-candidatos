<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\ConfiguracoesService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class ConfiguracoesController extends Controller
{
    protected ConfiguracoesService $configuracoesService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        ConfiguracoesService $configuracoesService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->configuracoesService = $configuracoesService;
        $this->permissaoService = $permissaoService;
    }

    /**
     * Exibe o formulário de configurações
     */
    public function index()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['configuracoes']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $configuracao = $this->configuracoesService->obterPrimeira();
        return view('adminConfiguracoes.index', compact('configuracao'));
    }

    /**
     * Atualiza a configuração
     */
    public function update(Request $request, $id)
    {
        $request->merge([
            'suporte_0800' => $request->has('suporte_0800'),
            'suporte_whatsapp' => $request->has('suporte_whatsapp'),
            'menu_ajuda' => $request->has('menu_ajuda'),
            'menu_documentos' => $request->has('menu_documentos'),
            'menu_trocar_senha' => $request->has('menu_trocar_senha'),
            'menu_recuperar_senha' => $request->has('menu_recuperar_senha'),
            'autenticacao_de_2_etapas' => $request->has('autenticacao_de_2_etapas'),
            'trocar_de_senha_depois_login' => $request->has('trocar_de_senha_depois_login'),
            'dados_da_comissao' => $request->has('dados_da_comissao'),
        ]);

        $dados = $request->validate([
            'nome_eleicao' => 'required|string|max:250',
            'razao_social' => 'required|string|max:250',
            'cnpj' => 'required|string|max:250',
            'suporte_0800' => 'nullable|boolean',
            'suporte_whatsapp' => 'nullable|boolean',
            'menu_ajuda' => 'required|boolean',
            'menu_documentos' => 'required|boolean',
            'menu_trocar_senha' => 'required|boolean',
            'menu_recuperar_senha' => 'required|boolean',
            'autenticacao_de_2_etapas' => 'required|boolean',
            'trocar_de_senha_depois_login' => 'required|boolean',
            'dados_da_comissao' => 'required|boolean',
            'numero_suporte_0800' => 'nullable|string|max:20',
            'numero_suporte_whatsapp' => 'nullable|string|max:20',
            'data_hora_inicio_eleicao' => 'nullable|date',
            'data_hora_final_eleicao' => 'nullable|date',
            'remetente_do_email' => 'nullable|string|max:150',
            'assunto_do_email' => 'nullable|string|max:150',
            'mensagem_eleitor_email' => 'nullable|string|max:250',
            'mensagem_eleitor_sms' => 'nullable|string|max:170',
            'cor_principal' => 'nullable|string|max:170',
            'cor_hover' => 'nullable|string|max:170',
            'logotipo' => 'nullable|file|mimes:png',
            'termos' => 'nullable|string|max:5000',
            'nome_presidente' => 'nullable|string|max:150',
            'cpf_presidente' => 'nullable|string|max:150',
            'email_presidente' => 'nullable|string|max:150',
            'celular_presidente' => 'nullable|string|max:20',
            'nome_mebro_1' => 'nullable|string|max:150',
            'cpf_mebro_1' => 'nullable|string|max:150',
            'email_mebro_1' => 'nullable|string|max:150',
            'celular_mebro_1' => 'nullable|string|max:20',
            'nome_mebro_2' => 'nullable|string|max:150',
            'cpf_mebro_2' => 'nullable|string|max:150',
            'email_mebro_2' => 'nullable|string|max:150',
            'celular_mebro_2' =>'nullable|string|max:20',
        ]);

        $this->configuracoesService->atualizar((int) $id, $dados, $request->file('logotipo'));

        return redirect()->route('admin.adminConfiguracoes.index')
                        ->with('success', 'Configurações atualizadas com sucesso!');
    }

    /**
     * Verificar senha via AJAX
     */
    public function verificarSenha(Request $request)
    {
        $request->validate([
            'senha' => 'required|string',
        ]);

        $user = auth()->user();

        if ($this->configuracoesService->verificarSenha($user, $request->senha)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    /**
     * Reiniciar Eleição
     */
    public function reiniciarEleicao(Request $request)
    {
        $ok = $this->configuracoesService->reiniciar();
        if ($ok) {
            return redirect()->back()->with('success', 'Eleição reiniciada com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao reiniciar a eleição. Verifique os logs.');
    }
}