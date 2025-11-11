<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ajuda extends Model
{
    use HasFactory;

    protected $table = 'ajudas';

    protected $fillable = [
        'titulo',
        'descricao',
        'ativo',
        'sequencia',
    ];
}