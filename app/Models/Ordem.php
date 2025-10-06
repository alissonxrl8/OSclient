<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordem extends Model
{
        protected $fillable = [
        'id_user',
        'id_cliente',
        'id_servico',
        'obs',
        'data',
        'preco',
        'modelo'
    ];
}
