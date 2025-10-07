<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordem extends Model
{
        protected $fillable = [
        'id_user',
        'id_cliente',
        'id_servico',
        'servico',
        'preco',
        'preco_pago',
        'descricao',
        'dias_garantia',
        'obs',
        'data',
        'preco',
        'modelo'
    ];
}
