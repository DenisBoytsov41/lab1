<?php
$servername = "lab1";
$username = "root";
$password = "";
$dbname = "lab1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$query = "SELECT * FROM users ORDER BY created DESC LIMIT 0,1";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    echo "Последняя добавленная запись:<br>";
    echo "ID: " . $row['id'] . "<br>";
    echo "Имя: " . $row['name'] . "<br>";
    echo "Дата создания: " . $row['created'] . "<br>";
} else {
    echo "Ошибка выполнения запроса: " . $conn->error;
}

$conn->close();
?>
<script>
    setTimeout(function() {
        window.history.back();
    }, 3000);
</script>
