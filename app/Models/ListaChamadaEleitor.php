<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaChamadaEleitor extends Model
{
    use HasFactory;

    protected $table = 'lista_chamada_eleitores';

    protected $fillable = [
        'eleitor_id',
        'ip',
    ];

    /**
     * 🔗 Relacionamento com Eleitor
     */
    public function eleitor()
    {
        return $this->belongsTo(Eleitor::class, 'eleitor_id');
    }
}