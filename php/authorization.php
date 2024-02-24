<form id="logForm" method="POST" action="php/login.php">
    <div class="form-group">
        <label for="loginUsername">Логин:</label>
        <input type="text" autocomplete="off" class="form-control" id="loginUsername" name="loginUsername" required>
    </div>
    <div class="form-group">
        <label for="loginPassword">Пароль:</label>
        <input type="password" autocomplete="off" class="form-control" id="loginPassword" name="loginPassword" required>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Войти</button>
    </div>
</form>