<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cтраница сайта</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/mainstyle.css">
    <link rel="stylesheet" type="text/css" href="css/cockie.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.osano.com/cookieconsent/OsanoCookieConsent.js"></script>
</head>
<body>

<div class="container">
    <h2>Приветствуем на нашем сайте</h2>
    <button id="loginBtn" class="btn">Авторизация</button>
    <button id="registerBtn" class="btn">Регистрация</button>
    <button id="themeToggle" class="btn btn-secondary">Темная тема</button>
    <div id="loginForm" class="form-container">
        <h3>Форма авторизации</h3>
        <div id="loginErrors"></div>
        <?php
        require "php/authorization.php";
        ?>
    </div>

    <div id="registerForm" class="form-container">
        <h3>Форма регистрации</h3>
        <div id="registrationErrors"></div>
        <?php
        require "php/registration.php"
        ?>
    </div>
</div>

<div style="display: none" id="cookieBanner" class="cookie-banner">
    <p>Мы используем куки (cookies), чтобы обеспечить лучшее пользовательское взаимодействие на нашем сайте. Продолжая использовать сайт, вы соглашаетесь с нашей политикой использования куки.</p>
    <button id="acceptCookiesBtn" class="accept-cookies-btn">Принять</button>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="js/ThemeType.js"></script>
<script src="js/login.js"></script>
<script src="js/register.js"></script>

<script>
    //localStorage.clear();
    document.addEventListener('DOMContentLoaded', function() {
        var cookieBanner = document.getElementById('cookieBanner');
        var acceptCookiesBtn = document.getElementById('acceptCookiesBtn');
        var cookiesAccepted = localStorage.getItem('cookiesAccepted');
        console.log(cookiesAccepted);
        if (cookiesAccepted === false || cookiesAccepted===null) {
            cookieBanner.style.display = 'block';
            console.log('csds');
        }

        acceptCookiesBtn.addEventListener('click', function() {
            cookieBanner.style.display = 'none';
            localStorage.setItem('cookiesAccepted', 'true');
        });
    });

    $(document).ready(function() {
        $('#registrationForm').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: 'php/process.php',
                data: formData,
                success: function(data) {
                    console.log(formData);
                    console.log(data);

                    var responseData = JSON.parse(data);
                    console.log(responseData);
                    console.log(responseData.errors);
                    if (responseData.errors) {
                        $('#registrationErrors').html('<div class="alert alert-danger">' + responseData.errors + '</div>');
                    } else {
                        $('#registrationErrors').html('<div class="alert alert-success">' + responseData.success + '</div>');
                        $('#registrationForm').trigger("reset");
                    }
                }
            });
        });
    });

    $(document).ready(function() {
        $('#logForm').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: 'php/login.php',
                data: formData,
                success: function(data) {
                    console.log(data);

                    if (typeof data === 'string' && data.trim().startsWith('{') && data.trim().endsWith('}')) {
                        var responseData = JSON.parse(data);
                        console.log(responseData);

                        if (responseData.errors) {
                            $('#loginErrors').html('<div class="alert alert-danger">' + responseData.errors + '</div>');
                        } else if (responseData.success && responseData.token) {
                            $('#loginErrors').html('<div class="alert alert-success">' + responseData.success + '</div>');
                            window.location.href = 'php/personal_cabinet.php?token=' + responseData.token;
                        } else {

                            $('#loginErrors').html('<div class="alert alert-danger">Некорректный формат данных</div>');
                        }
                    } else {

                        $('#loginErrors').html('<div class="alert alert-danger">' + data + '</div>');
                    }
                }
            });
        });
    });
</script>

</body>
</html>
