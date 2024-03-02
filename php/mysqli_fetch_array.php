<?php
$servername = "lab1";
$username = "root";
$password = "";
$dbname = "lab1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$sql = "SELECT Login FROM data";
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result)) {
    echo $row['Login'] . "<br>";
}

$conn->close();
?>
<script>
    setTimeout(function() {
        window.history.back();
    }, 3000);
</script>