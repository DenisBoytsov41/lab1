<?php
$servername = "lab1";
$username = "root";
$password = "";
$dbname = "lab1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$date_array = getdate();
$begin_date = date("Y-m-d", mktime(0, 0, 0, $date_array['mon'], 1, $date_array['year']));
$end_date = date("Y-m-d", mktime(0, 0, 0, $date_array['mon'] + 1, 0, $date_array['year']));

$query = "SELECT COUNT(id) AS record_count FROM users WHERE created >= '$begin_date' AND created <= '$end_date'";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $record_count = $row['record_count'];
    echo "Количество записей за последний месяц: $record_count";
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
