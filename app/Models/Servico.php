<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $fillable = [
        'id_user',
        'servico',
        'descricao',
        'dias_garantia',
        'preco',
        'preco_pago'
    ];
}
