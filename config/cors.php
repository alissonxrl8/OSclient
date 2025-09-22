<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Permite que o frontend se comunique com a API sem bloquear por CORS.
    | Para produção, ajuste 'allowed_origins' para o domínio do frontend.
    |
    */

    'paths' => ['api/*'], // aplica CORS apenas nas rotas da API

    'allowed_methods' => ['*'], // libera todos os métodos (GET, POST, PUT, DELETE...)

    'allowed_origins' => ['*'], // libera qualquer origem (teste local/ionic)

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // libera todos os headers

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // false para facilitar teste local

];
