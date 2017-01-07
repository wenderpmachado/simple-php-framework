<?php

$header = jwtEncode([
    'alg' => 'HS256',
    'typ' => 'JWT'
]);

$payload = jwtEncode([
    'sub' => '1234567890',
    'name' => 'John Doe',
    'admin' => true
]);

function jwtEncode($array){
    return base64_encode(json_encode($array));
}