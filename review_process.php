<?php
require_once "templates/header.php";
require_once "models/User.php";
require_once "dao/UserDao.php";
require_once "dao/MovieDao.php";

$user = new User();
$userDao = new UserDao($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (empty($id) && isset($userData->id)) {
    $id = $userData->id;
}

try {
    if (empty($id)) {
        throw new Exception("Usuário não encontrado");
    }

    $userData = $userDao->findById($id);

    if (!$userData) {
        throw new Exception("Usuário não encontrado");
    }

    $fullname = $user->getFullName($userData);

    if (empty($userData->image)) {
        $userData->image = "user.png";
    }

    $userMovies = $movieDao->getMoviesByUserId($id);
} catch (Exception $e) {
    $message->setMessage($e->getMessage(), "error", "index.php");
}
?>

<div class="main-container" id="main-container">
    <div class="col-md-8 offset-md-2">
        <div class="row profile-container">
            <div class="col-md-12 about-container">
                <h1 class="page-title"><?= htmlspecialchars($fullname) ?></h1>
                <div id="profile-image-container" class="profile-image" style="background-image: url('<?= htmlspecialchars($BASE_URL) ?>img/users/<?= htmlspecialchars($userData->image) ?>')"></div>
                <h3 class="about-title">Sobre:</h3>
                <?php if (!empty($userData->bio)): ?>
                    <p class="profile-description"><?= htmlspecialchars($userData->bio) ?></p>
                <?php else: ?>
                    <p class="profile-description">O usuário ainda não escreveu nada aqui ...</p>
                <?php endif; ?>
            </div>
            <div class="col-md-12 added-movies-container">
                <h3>Filmes que o usuário enviou:</h3>
                <div class="movies-container">
                    <?php foreach ($userMovies as $movie): ?>
                        <?php require "templates/movie_card.php"; ?>
                    <?php endforeach; ?>
                    <?php if (count($userMovies) === 0): ?>
                        <p class="empty-list">O usuário ainda não adicionou filmes.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once "templates/footer.php";
?>
