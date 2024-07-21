<?php
require_once "templates/header.php";
require_once "models/User.php";
require_once "dao/UserDao.php";

$userDao = new UserDao($conn, $BASE_URL);
$userData = $userDao->verifyToken(true);

if (!$userData) {
    $message->setMessage("Você precisa estar logado para acessar esta página!", "error", "index.php");
}
?>

<div id="main-container" class="container-fluid">
    <div class="col-md-6 offset-md-3">
        <h1 class="text-center">Alterar Senha</h1>
        <p class="page-description text-center">Digite sua nova senha abaixo:</p>
        <form action="<?= htmlspecialchars($BASE_URL) ?>user_process.php" method="POST" id="change-password-form">
            <input type="hidden" name="type" value="changepassword">
            <div class="form-group">
                <label for="password">Nova Senha:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua nova senha">
            </div>
            <div class="form-group">
                <label for="confirmpassword">Confirme a Nova Senha:</label>
                <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirme sua nova senha">
            </div>
            <input type="submit" class="btn card-btn btn-block" value="Alterar Senha">
        </form>
    </div>
</div>

<?php
require_once("templates/footer.php");
?>
