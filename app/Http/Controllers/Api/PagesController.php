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
        $loja = Loja::where('id', $user->id_loja)->get();

        return response()->json([
            'status'=>200,
            'message'=>'ok',
            'loja'=>$loja,
            'user'=>$user
        ]);

    }
}
