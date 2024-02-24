<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use \Firebase\JWT\JWT;

$loginUsername = isset($_POST['loginUsername']) ? validateInput($_POST['loginUsername']) : '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "lab1";
    $username = "root";
    $password = "";
    $dbname = "lab1";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM data WHERE Login='$loginUsername'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($_POST['loginPassword'], $row['Password'])) {
            $payload = array(
                "Login" => $row['Login'],
                "Name" => $row['Name'],
                "Email" => $row['Email'],
            );
            $secret_key = "5A8xGvK2TQnS7z";
            $jwt = \Firebase\JWT\JWT::encode($payload, $secret_key, 'HS256');

            $_SESSION['jwt'] = $jwt;
            $_SESSION['username'] = $row['Name'];
            $_SESSION['Login'] = $row['Login'];

            echo json_encode(array('success' => 'Вы успешно вошли в систему.', 'token' => $jwt));
            exit();
        } else {
            echo json_encode(array('errors' => 'Неверный логин или пароль.'));
            exit();
        }
    } else {
        echo json_encode(array('errors' => 'Пользователь не найден.'));
        exit();
    }

    $conn->close();
}

function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
