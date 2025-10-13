<?php

function db()
{

    $host = 'localhost';
    $db = 'perfumeria';
    $user = 'root';
    $pass = 'cheo01045';
    $charset = 'utf8mb4';

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // errores claros
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // devuelve arrays asociativos
        PDO::ATTR_EMULATE_PREPARES   => false,                  // evita inyecciones SQL
    ];

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(['exito' => false, 'mensaje' => 'Error al conectar a la base de datos']);
        exit;
    }

    return $pdo;
}
