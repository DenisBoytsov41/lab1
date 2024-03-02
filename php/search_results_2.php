<?php
session_start();

$servername = "lab1";
$username = "root";
$password = "";
$dbname = "lab1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if (isset($_GET['usersearch'])) {
    $search_query = "SELECT * FROM users";
    $where_clause = '';
    $user_search = $_GET['usersearch'];
    $search_words = explode(' ', $user_search);

    foreach ($search_words as $word) {
        if (!empty($word)) {
            $where_clause .= "name LIKE '%$word%' OR ";
        }
    }

    $where_clause = rtrim($where_clause, " OR ");

    if (!empty($where_clause)) {
        $search_query .= " WHERE $where_clause";
    }

    $result = $conn->query($search_query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>{$row['name']}</p>";
        }
    } else {
        echo "<p>По вашему запросу ничего не найдено.</p>";
    }
} else {
    echo "<p>Введите запрос в поле поиска.</p>";
}

$conn->close();
?>
<script>
    setTimeout(function() {
        window.history.back();
    }, 3000);
</script>