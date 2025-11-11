<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioTelaPermissao extends Model
{
    use HasFactory;

    protected $table = 'usuario_tela_permissoes';

    /**
     * Campos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'usuario_id',
        'tela_slug',
        'criar',
        'importar_eleitores',
        'enviar_senha',
        'ver',
        'editar',
        'deletar',
    ];

    /**
     * Tipos de dados
     */
    protected $casts = [
        'criar' => 'boolean',
        'importar_eleitores' => 'boolean',
        'enviar_senha' => 'boolean',
        'ver' => 'boolean',
        'editar' => 'boolean',
        'deletar' => 'boolean',
    ];

    /**
     * Relacionamento com User
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Método helper para verificar permissão
     */
    public function pode(string $acao): bool
    {
        return $this->{$acao} ?? false;
    }

    public function permissoes()
    {
        return $this->hasMany(UsuarioTelaPermissao::class, 'usuario_id');
    }
}