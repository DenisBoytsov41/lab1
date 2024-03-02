<?php
$servername = "lab1";
$username = "root";
$password = "";
$dbname = "lab1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if (isset($_GET['usersearch'])) {
    $user_search = $_GET['usersearch'];

    if (!empty($user_search)) {
        $query_usersearch = "SELECT * FROM users WHERE name LIKE '%$user_search%'";
        $result_usersearch = $conn->query($query_usersearch);

        if ($result_usersearch->num_rows > 0) {
            while ($row = $result_usersearch->fetch_assoc()) {
                echo $row['name'] . "<br>";
            }
        } else {
            echo "По вашему запросу ничего не найдено.";
        }
    } else {
        echo "Пожалуйста, введите ключевое слово для поиска.";
    }
}

$conn->close();
?>
<script>
    setTimeout(function() {
        window.history.back();
    }, 3000);
</script>