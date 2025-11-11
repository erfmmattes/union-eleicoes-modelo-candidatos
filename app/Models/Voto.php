<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voto extends Model
{
    use HasFactory;

    protected $table = 'votos';

    protected $fillable = [
        'eleitor_id',
        'voto',
        'votado_em',
        'etapa',
    ];

    protected $casts = [
        'votado_em' => 'datetime',
    ];

    /**
     * Relacionamento: um voto pertence a um candidato.
     */
    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }

    /**
     * Criptografar o voto automaticamente ao salvar.
     */
    public function setVotoAttribute($value)
    {
        $this->attributes['voto'] = encrypt($value);
    }

    /**
     * Descriptografar o voto ao acessar.
     */
    public function getVotoAttribute($value)
    {
        return decrypt($value);
    }
}