<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

$servername = "lab1";
$username = "root";
$password = "";
$dbname = "lab1";

function executeQuery($sql) {
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: /index.php");
    exit();
}

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

if (isset($_POST['theme'])) {
    $theme = $_POST['theme'];
    if (isset($_SESSION['Login'])) {
        $login = $_SESSION['Login'];
        $sql_check = "SELECT * FROM user_themes WHERE username='$login'";
        $result_check = executeQuery($sql_check);
        if ($result_check->num_rows > 0) {
            executeQuery("UPDATE user_themes SET theme='$theme' WHERE username='$login'");
        } else {
            executeQuery("INSERT INTO user_themes (username, theme) VALUES ('$login', '$theme')");
        }
    }
    $_SESSION['theme'] = $theme;
}

function getCurrentTheme() {
    global $guestMode;
    if (isset($_SESSION['Login']) && !$guestMode && isset($_COOKIE['user'])) {
        $login = $_SESSION['Login'];
        $sql = "SELECT theme FROM user_themes WHERE username='$login'";
        $result = executeQuery($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['theme'];
        }
    }
    return 'light';
}

$guestMode = true;

if (isset($_GET['token']) && isset($_SESSION['jwt']) && isset($_COOKIE['user'])) {
    $token = $_GET['token'];
    $sessionToken = $_SESSION['jwt'];
    if ($token === $sessionToken) {
        $secret_key = "5A8xGvK2TQnS7z";

        try {
            $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
            $_SESSION['jwt'] = $token;
            $_SESSION['username'] = $decoded->Name;
            $_SESSION['Login'] = $decoded->Login;
            $guestMode = false;
        } catch (Exception $e) {
            unset($_SESSION['jwt']);
            unset($_SESSION['username']);
            unset($_SESSION['Login']);
            unset( $_SESSION['theme']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/personal_cabinet.css">
    <title>Личный кабинет</title>
</head>
<body class="<?= getCurrentTheme(); ?>">
<div>
    <link rel="stylesheet" type="text/css" href="/css/secondstyle.css">
    <h1>Личный кабинет</h1>
    <p>Добро пожаловать,
        <?php if (!$guestMode && isset($_SESSION['username']) && isset($_COOKIE['user'])): ?>
            <?= $_SESSION['username']; ?>
        <?php else: ?>
            Гость
        <?php endif; ?>
        !</p>
    <?php if (!$guestMode && isset($_SESSION['Login']) && isset($_COOKIE['user'])): ?>
        <form method="post">
            <label>Выберите тему:</label>
            <button id="theme-switch-btn" type="submit" name="theme" value="<?= getCurrentTheme() === 'light' ? 'dark' : 'light'; ?>">
                <?= getCurrentTheme() === 'light' ? 'Темная тема' : 'Светлая тема'; ?>
            </button>
        </form>
    <?php endif; ?>
    <?php if (!$guestMode && isset($_SESSION['Login']) && isset($_COOKIE['user'])): ?>
        <form method="post" action="mysqli_fetch_array.php">
            <button class="btn" type="submit">Вывод данных из базы на страницу</button>
        </form>
        <form method="post" action="total_records.php">
            <button class="btn" type="submit">Общее количество записей в таблице</button>
        </form>
        <form method="post" action="count_records_last_month.php">
            <button class="btn" type="submit">Подсчет количества записей за последний месяц</button>
        </form>
        <form method="post" action="last_added_record.php">
            <button class="btn" type="submit">Какая запись была сделана последней</button>
        </form>
        <form method="post" action="display_results.php">
            <button class="btn" type="submit">Размещение данных на странице</button>
        </form>
        <form method="get" action="search_results.php">
            <label for="usersearch">Поиск по ключевому слову:</label>
            <input type="text" id="usersearch" name="usersearch" placeholder="Введите ключевое слово">
            <button class="btn" type="submit">Искать</button>
        </form>
        <form method="get" action="search_results_2.php">
            <label for="usersearch">Реализация поиска по фразе:</label>
            <input type="text" id="usersearch" name="usersearch" placeholder="Введите фразу поиска">
            <button class="btn" type="submit">Искать</button>
        </form>
    <?php endif; ?>
    <form method="post" class="logout-form">
        <button type="submit" class="logout" name="logout">Выйти</button>
    </form>
    <div id="dataContainer">
        <?php if ($guestMode): ?>
            <?php echo ''; ?>
        <?php endif; ?>
    </div>
</div>
<script src="/js/theme.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let logoutRequested = false;
        let pageRefreshed = false;
        let themeChanged = false; // Флаг для отслеживания смены темы

        /*window.onbeforeunload = function(event) {
            if (!logoutRequested && !pageRefreshed && !themeChanged) {
                return "Вы уверены, что хотите покинуть эту страницу?";
            }
        };*/

        // Добавляем обработчик клика по кнопке смены темы
        document.getElementById('theme-switch-btn').addEventListener('click', function() {
            themeChanged = true;
        });

        window.addEventListener('popstate', function(event) {
            console.log('Нажата кнопка "Назад"');
            if (!logoutRequested && !pageRefreshed && !themeChanged) {
                sendLogoutRequestIfNotRequested();
            }
        });

        history.pushState(null, null, window.location.href);

        function onPageRefresh() {
            console.log('Страница обновлена');
            pageRefreshed = true;
        }

        function onFocus() {
            console.log('Окно активировано');
            if (!logoutRequested && !pageRefreshed && !themeChanged) {
                sendLogoutRequestIfNotRequested();
            }
        }

        function onBlur() {
            console.log('Окно деактивировано');
        }

        function sendLogoutRequestIfNotRequested() {
            if (!logoutRequested && !pageRefreshed && !themeChanged) {
                sendLogoutRequest();
            }
        }

        function sendLogoutRequest() {
            fetch('/php/logout.php', {
                method: 'POST'
            })
                .then(response => {
                    if (response.ok) {
                        console.log('Успешно отправлен запрос на logout.php');
                        logoutRequested = true;
                        history.back();
                    } else {
                        console.error('Ошибка при отправке запроса на logout.php:', response.status);
                    }
                })
                .catch(error => {
                    console.error('Ошибка при отправке запроса на logout.php:', error);
                });
        }

        window.addEventListener('focus', onFocus);
        window.addEventListener('blur', onBlur);
        window.addEventListener('beforeunload', onPageRefresh);
    });





</script>
<script>
    const jwtToken = "<?php echo (!$guestMode && (isset($_GET['token']) && isset($_SESSION['jwt']) && $_SESSION['jwt'] !== '')) ? $_SESSION['jwt'] : ''; ?>";
    console.log(jwtToken);
    if (jwtToken) {
        fetch('protected_resource.php', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${jwtToken}`
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ошибка HTTP: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                const dataContainer = document.getElementById('dataContainer');
                console.log(dataContainer);
                if (dataContainer) {
                    dataContainer.textContent = JSON.stringify(data);
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });
    } else {
        console.log('Токен отсутствует');
        const dataContainer = document.getElementById('dataContainer');
        if (dataContainer) {
            dataContainer.textContent = 'Доступ запрещен для гостей';
        }
    }

</script>

<script>
    const jwtToken2 = "<?php echo isset($_GET['token']) && isset($_SESSION['jwt']) && $_SESSION['jwt'] !== '' ? $_SESSION['jwt'] : ''; ?>";
    const guestMode = <?php echo $guestMode ? 'true' : 'false'; ?>;
    let scriptExecuted = sessionStorage.getItem('scriptExecuted');
    console.log(jwtToken2);

    if (!scriptExecuted && !jwtToken2 && guestMode && window.location.pathname.includes('/php/personal_cabinet.php')) {
        window.location.href = '/php/personal_cabinet.php';
        sessionStorage.setItem('scriptExecuted', true);
        console.log('fsf');
    }

    window.addEventListener('beforeunload', function() {
        sessionStorage.removeItem('scriptExecuted');
    });



</script>

</body>
</html>
