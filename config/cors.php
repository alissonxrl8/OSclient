<?php

return [

    'paths' => ['api/*'], // Só permitir CORS nas rotas da API

    'allowed_methods' => ['*'],

    // Coloque diretamente a origem do Ionic, sem env
    'allowed_origins' => ['*'], // libera qualquer origem,

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // false ajuda a testar local sem complicar com credenciais
    'supports_credentials' => false,

];
