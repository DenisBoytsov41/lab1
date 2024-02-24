<form id="registrationForm" method="POST" action="php/process.php">
    <div class="form-group">
        <label for="firstName">Имя:</label>
        <input type="text" autocomplete="off" class="form-control" id="firstName" name="firstName" required minlength="2" maxlength="15">
    </div>
    <div class="form-group">
        <label for="lastName">Фамилия:</label>
        <input type="text" autocomplete="off" class="form-control" id="lastName" name="lastName" required minlength="2" maxlength="15">
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" autocomplete="off" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="username">Логин:</label>
        <input type="text" autocomplete="off" class="form-control" id="username" name="username" required minlength="6">
    </div>
    <div class="form-group">
        <label for="password">Пароль:</label>
        <input type="password" autocomplete="off" class="form-control" id="password" name="password" required minlength="8">
    </div>
    <div class="form-group">
        <label for="confirmPassword">Подтверждение пароля:</label>
        <input type="password" autocomplete="off" class="form-control" id="confirmPassword" name="confirmPassword" required>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" autocomplete="off" class="form-check-input" id="agreeTerms" name="agreeTerms" required>
        <label class="form-check-label" for="agreeTerms">Принимаю правила...</label>
    </div>
    <div class="form-group">
        <label for="age">Мне 18 лет:</label>
        <select class="form-control" autocomplete="off" id="age" name="age" required>
            <option value="yes">Да</option>
            <option value="no">Нет</option>
        </select>
    </div>
    <div class="form-group">
        <label>Пол:</label><br>
        <div class="form-check form-check-inline">
            <input class="form-check-input" autocomplete="off" type="radio" name="gender" id="male" value="male" required>
            <label class="form-check-label" for="male">Мужской</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" autocomplete="off" type="radio" name="gender" id="female" value="female" required>
            <label class="form-check-label" for="female">Женский</label>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
    </div>
</form>

