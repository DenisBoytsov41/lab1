<?php
$servername = "lab1";
$username = "root";
$password = "";
$dbname = "lab1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$query_total_records = "SELECT COUNT(id) AS total_records FROM users";
$result_total_records = $conn->query($query_total_records);

if ($result_total_records) {
    $row_total_records = $result_total_records->fetch_assoc();
    $total_records = $row_total_records['total_records'];
} else {
    $total_records = "Ошибка выполнения запроса: " . $conn->error;
}

$date_array = getdate();
$begin_date = date("Y-m-d", mktime(0, 0, 0, $date_array['mon'], 1, $date_array['year']));
$end_date = date("Y-m-d", mktime(0, 0, 0, $date_array['mon'] + 1, 0, $date_array['year']));

$query_records_last_month = "SELECT COUNT(id) AS records_last_month FROM users WHERE created >= '$begin_date' AND created <= '$end_date'";
$result_records_last_month = $conn->query($query_records_last_month);

if ($result_records_last_month) {
    $row_records_last_month = $result_records_last_month->fetch_assoc();
    $records_last_month = $row_records_last_month['records_last_month'];
} else {
    $records_last_month = "Ошибка выполнения запроса: " . $conn->error;
}

$query_last_record = "SELECT * FROM users ORDER BY created DESC LIMIT 0,1";
$result_last_record = $conn->query($query_last_record);

if ($result_last_record) {
    $row_last_record = $result_last_record->fetch_assoc();
} else {
    $last_record = "Ошибка выполнения запроса: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты</title>
</head>
<body>
<h2>Результаты</h2>
<p>Сделано записей: <?php echo $total_records; ?></p>
<p>За последний месяц создано записей: <?php echo $records_last_month; ?></p>
<p>Моя последняя запись: <a href="display_results.php?name=<?php echo $row_last_record['name']; ?>"><?php echo $row_last_record['name']; ?></a></p>
</body>
</html>
<script>
    setTimeout(function() {
        window.history.back();
    }, 3000);
</script>
