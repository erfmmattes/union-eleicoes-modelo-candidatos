<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatorioLogEleitor extends Model
{
    use HasFactory;

    protected $table = 'relatorio_logs_eleitores';

    protected $fillable = [
        'eleitor_id',
        'eleitor_nome',
        'acao',
        'mensagem',
        'ip',
        'pagina',
    ];
}