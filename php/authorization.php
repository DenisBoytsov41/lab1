<form id="logForm" method="POST" action="php/login.php">
    <div class="form-group">
        <label for="loginUsername">Логин:</label>
        <input type="text" autocomplete="off" class="form-control" id="loginUsername" name="loginUsername" required>
    </div>
    <div class="form-group">
        <label for="loginPassword">Пароль:</label>
        <div class="input-group">
            <input type="password" autocomplete="off" class="form-control" id="loginPassword" name="loginPassword" required minlength="8">
            <div class="input-group-append">
            <span class="input-group-text" id="togglePassword">
                <i class="fa fa-eye" aria-hidden="true"></i>
            </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Войти</button>
    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $("#togglePassword").click(function(){
            const passwordField = $("#loginPassword");
            const fieldType = passwordField.attr('type');
            passwordField.attr('type', fieldType === 'password' ? 'text' : 'password');
        });

        $("#toggleConfirmPassword").click(function(){
            const confirmPasswordField = $("#confirmPassword");
            const fieldType = confirmPasswordField.attr('type');
            confirmPasswordField.attr('type', fieldType === 'password' ? 'text' : 'password');
        });
    });
</script>