<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    protected $table = 'setores';

    protected $fillable = [
        'nome',
        'status',
    ];

    /** Relação com as etapas do candidato */
    public function etapas()
    {
        return $this->hasMany(EtapaCandidato::class, 'setores_id', 'id');
    }
}