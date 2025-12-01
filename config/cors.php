<?php

return [

    // 🔹 Rutas donde se aplicará CORS
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // 🔹 Métodos permitidos (todos mientras estás desarrollando)
    'allowed_methods' => ['*'],

    // 🔹 Dominios/orígenes permitidos
    'allowed_origins' => [
        '*',   // durante pruebas, permite TODO
        // Si quieres poner específico después:
        // 'http://localhost:8080',
        // 'http://192.168.0.130:8080',
    ],

    'allowed_origins_patterns' => [],

    // 🔹 Headers permitidos
    'allowed_headers' => ['*'],

    // 🔹 Headers expuestos
    'exposed_headers' => [],

    // 🔹 Tiempo cache del preflight
    'max_age' => 0,

    // 🔹 Si necesitas cookies cruzadas (por ahora NO)
    'supports_credentials' => false,

];
