<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

if(isset($_SESSION['jwt'])) {
    $jwt = $_SESSION['jwt'];
    $secret_key = "5A8xGvK2TQnS7z";

    try {
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        $login = $decoded->Login;
        $name = $decoded->Name;
        $email = $decoded->Email;

        echo json_encode(array("message" => "Доступ разрешен для пользователя $name, Логин: $login, Почта: $email"));
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array("error" => $e->getMessage()));
    }
} else {
    echo json_encode(array("error" => "Доступ запрещен для гостей"));
}
?>
