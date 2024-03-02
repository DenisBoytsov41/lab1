<?php
$servername = "lab1";
$username = "root";
$password = "";
$dbname = "lab1";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) AS total FROM users";


$result = $conn->query($sql);


if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "Общее количество записей в таблице: " . $row["total"];
} else {
    echo "Нет данных";
}

$conn->close();
?>
<script>
    setTimeout(function() {
        window.history.back();
    }, 3000);
</script>