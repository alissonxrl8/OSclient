<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Garantia extends Model
{
    protected $fillable = [
        'data_garantia',
        'id_cliente',
        'id_user',
        'id_orcamento'
    ];
}
