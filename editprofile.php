<?php
require_once "templates/header.php";
require_once "models/User.php";
require_once "dao/UserDao.php";

$userDao = new UserDao($conn, $BASE_URL);
$userData = $userDao->verifyToken(true);

if (!$userData) {
    $message->setMessage("Você precisa estar logado para acessar esta página!", "error", "index.php");
}

if (empty($userData->image)) {
    $userData->image = "user.png";
}
?>

<div id="main-container" class="container-fluid">
    <div class="col-md-6 offset-md-3">
        <div class="profile-image-container image-profile" style="background-image: url('<?= htmlspecialchars($BASE_URL) ?>img/users/<?= htmlspecialchars($userData->image) ?>'); margin: 0 auto;"></div>
        <h1 class="text-center"><?= htmlspecialchars($userData->name) ?> <?= htmlspecialchars($userData->lastname) ?></h1>
        <p class="page-description text-center">Altere seus dados no formulário abaixo:</p>
        <form action="<?= htmlspecialchars($BASE_URL) ?>user_process.php" method="POST" enctype="multipart/form-data" id="edit-profile-form">
            <input type="hidden" name="type" value="update">
            <div class="form-group">
                <label for="name">Nome:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome" value="<?= htmlspecialchars($userData->name) ?>">
            </div>
            <div class="form-group">
                <label for="lastname">Sobrenome:</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Digite seu sobrenome" value="<?= htmlspecialchars($userData->lastname) ?>">
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" value="<?= htmlspecialchars($userData->email) ?>" disabled>
            </div>
            <div class="form-group">
                <label for="bio">Biografia:</label>
                <textarea name="bio" id="bio" class="form-control" rows="5" placeholder="Fale um pouco sobre você"><?= htmlspecialchars($userData->bio) ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">Imagem de Perfil:</label>
                <input type="file" class="form-control-file" id="image" name="image">
            </div>
            <input type="submit" class="btn card-btn btn-block" value="Atualizar perfil">
        </form>
        <div class="text-center mt-4">
            <a href="<?= htmlspecialchars($BASE_URL) ?>changepassword.php" class="btn card-btn">Alterar Senha</a>
        </div>
    </div>
</div>

<?php
require_once("templates/footer.php");
?>
