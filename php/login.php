<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use \Firebase\JWT\JWT;

require_once "recaptchalib.php";

$secret = "6LeUYYgpAAAAANCdLoYzoZr3xse00GJaKvQxaiTt";

$loginUsername = isset($_POST['loginUsername']) ? validateInput($_POST['loginUsername']) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "lab1";
    $username = "root";
    $password = "";
    $dbname = "lab1";
    $captcha = $_POST['g-recaptcha-response'];
    $url = 'https://www.google.com/recaptcha/api/siteverify';

    // Отправляем POST запрос с использованием cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'secret' => $secret,
        'response' => $_POST['g-recaptcha-response'],
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    // Проверяем успешность выполнения запроса
    if (!$response) {
        echo json_encode(array('errors' => 'Ошибка при проверке CAPTCHA.'));
        exit();
    }

    // Декодируем JSON ответ
    $result2 = json_decode($response);

    if ($result2 && $result2->success) {
        // Все в порядке, продолжаем аутентификацию и выдачу токена
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            http_response_code(500);
            echo json_encode(array('errors' => 'Ошибка подключения к базе данных.'));
            exit();
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

                setcookie('user', $row['Login'], time() + (86400 * 30), "/");
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
    } else {
        echo json_encode(array('errors' => 'Пожалуйста, подтвердите, что вы не робот!'));
        exit();
    }
} else {
    echo json_encode(array('errors' => 'Неверный метод запроса.'));
    exit();
}

function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
