<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return 'Servidor Laravel no ar! 🚀';
});


require __DIR__.'/auth.php';
