<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    public function Home(){
        $user = Auth::user();
        Loja::where('id', $user->id_loja)->get();
        

    }
}
