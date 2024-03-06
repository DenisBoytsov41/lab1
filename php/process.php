<?php

$servername = "lab1";
$username = "root";
$password = "";
$dbname = "lab1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$errors = [];

$firstName = isset($_POST['firstName']) ? validateInput($_POST['firstName']) : '';
$lastName = isset($_POST['lastName']) ? validateInput($_POST['lastName']) : '';
$email = isset($_POST['email']) ? validateInput($_POST['email']) : '';
$username = isset($_POST['username']) ? validateInput($_POST['username']) : '';
$password = isset($_POST['password']) ? validateInput($_POST['password']) : '';
$confirmPassword = isset($_POST['confirmPassword']) ? validateInput($_POST['confirmPassword']) : '';
$agreeTerms = isset($_POST['agreeTerms']) ? true : false;
$age = isset($_POST['age']) ? $_POST['age'] : '';
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';

$nameRegex = '/^[а-яА-Яa-zA-Z]+(?:[-\s][а-яА-Яa-zA-Z]+)?$/u';
$spaceAndDashRegex = '/[-\s]+/';


error_log("firstName: " . $firstName);
error_log("lastName: " . $lastName);
error_log("email: " . $email);
error_log("username: " . $username);
error_log("password: " . $password);
error_log("confirmPassword: " . $confirmPassword);
error_log("agreeTerms: " . ($agreeTerms ? 'true' : 'false'));
error_log("age: " . $age);
error_log("gender: " . $gender);


// Проверка имени
if (!preg_match('/^[а-яА-Яa-zA-Z]{2,15}$/u', $firstName) || !preg_match($nameRegex, $lastName)) {
    $errors[] = "Недопустимое имя или фамилия";
}

// Проверка email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Недопустимый email";
}

// Проверка логина
if (strlen($username) < 6) {
    $errors[] = "Логин должен содержать не менее 6 символов";
}

// Проверка пароля
if (strlen($password) < 8 || !preg_match('/[А-ЯA-Z]/', $password) || !preg_match('/[а-яa-z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[\W_]/', $password)) {
    $errors[] = "Пароль должен содержать не менее 8 символов и включать в себя хотя бы одну заглавную букву, одну строчную букву, одну цифру и один символ";
}

// Проверка подтверждения пароля
if ($password !== $confirmPassword) {
    $errors[] = "Пароли не совпадают";
}

// Проверка согласия с условиями
if (!$agreeTerms) {
    $errors[] = "Вы должны принять правила";
}

if (!empty($errors)) {
    $errorMessages = array();

    foreach ($errors as $error) {
        $errorMessages[] = $error;
    }
    echo json_encode(array('errors' => implode('; ', $errorMessages)));
} else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = $conn->prepare("INSERT INTO data (Name, Surname, Email, Login, Password, Gender) VALUES (?, ?, ?, ?, ?, ?)");
    $sql->bind_param("ssssss", $firstName, $lastName, $email, $username, $hashedPassword, $gender);

    try {
        if ($sql->execute()) {
            echo json_encode(array('success' => 'Регистрация успешно завершена'));
        } else {
            throw new Exception("Ошибка при выполнении запроса");
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            echo json_encode(array('errors' => 'Пользователь с таким логином уже существует'));
        } else {
            echo json_encode(array('errors' => 'Ошибка при выполнении запроса: ' . $e->getMessage()));
        }
    }
}

$conn->close();

function validateInput($data) {

    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
