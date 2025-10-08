<?php

use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\GarantiaController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\OrdemController;
use App\Http\Controllers\Api\PagesController;
use App\Http\Controllers\Api\ServicoController;
use App\Http\Controllers\Api\TesteController;
use App\Http\Controllers\Api\UsuariosController;
use App\Http\Controllers\LojaController;
use App\Models\Garantia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/', [LoginController::class, 'login'])->name('login.api');
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('usuarios', [UsuariosController::class, 'index'])->middleware('can:acesso_1');

    //acesso level 1
    Route::middleware(['auth', 'can:acesso_1'])->group(function(){
        Route::post('create', [UsuariosController::class, 'criarUser']);
    }); 


    Route::resource('servicos', ServicoController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('ordens', OrdemController::class);
    Route::resource('garantias', GarantiaController::class);
    Route::get('dados-garantia', [GarantiaController::class, 'dadosGarantia']);
    Route::get('baixar-garantia/{id}', [GarantiaController::class, 'baixarGarantia']);
    Route::get('baixar-ordem/{id}', [OrdemController::class, 'baixarOrdem']);
    Route::get('ordens-cliente/{id}', [OrdemController::class, 'ClienteOrdem']);
    Route::get('home', [PagesController::class, 'home']);

});





Route::resource('lojas', LojaController::class);



Route::post('test', [UsuariosController::class, 'criarUser']);
Route::get('teste', [UsuariosController::class, 'teste']);
