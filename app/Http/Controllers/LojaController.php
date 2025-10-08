<?php

namespace App\Http\Controllers;

use App\Models\Loja;
use Illuminate\Http\Request;

class LojaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
     $lojas = Loja::all();
     return response()->json([
        'status'=>'true',
        $lojas
     ]);   
    }


    public function store(Request $request)
    {
        $validados = $request->validate([
            'nome'=>'required|string',
            'cnpj'=>'required|string',
            'logo'=>'required|string'
        ]);

        $loja = Loja::create($validados);

        return response()->json([

            'status'=>'true',
            $loja
        ]);
        
    }

   
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
