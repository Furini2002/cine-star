<?php
require_once "templates/header.php";
require_once "models/User.php";
require_once "dao/UserDAO.php";
require_once "dao/MovieDAO.php";

// Verifica se o usuário está autenticado
$userDao = new UserDao($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

$userData = $userDao->verifyToken(true);
$userMovies = $movieDao->getMoviesByUserId($userData->id);
?>

<div id="main-container" class="container-fluid">
    <h2 class="section-title">Dashboard</h2>
    <p class="section-description">Adicione ou atualize as informações dos filmes que você enviou</p>
    <div class="col-md-12" id="add-movie-container">
        <a href="<?= htmlspecialchars($BASE_URL) ?>newmovie.php" class="btn card-btn">
            <i class="fas fa-plus"></i>Adicionar Filme
        </a>
    </div>
    <div class="col-md-12" id="movies-dashboard">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Título</th>
                    <th scope="col">Nota</th>
                    <th scope="col" class="actions-column">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userMovies as $movie): ?>
                <tr>
                    <td scope="row"><?= htmlspecialchars($movie->id) ?></td>
                    <td><a href="<?= htmlspecialchars($BASE_URL) ?>movie.php?id=<?= htmlspecialchars($movie->id) ?>" class="table-movie-title"><?= htmlspecialchars($movie->title) ?></a></td>
                    <td><i class="fas fa-star"></i><?= htmlspecialchars($movie->rating) ?></td>
                    <td class="actions-column">
                        <a href="<?= htmlspecialchars($BASE_URL) ?>editmovie.php?id=<?= htmlspecialchars($movie->id) ?>" class="edit-btn">
                            <i class="far fa-edit"></i>Editar
                        </a>
                        <form action="<?= htmlspecialchars($BASE_URL) ?>movie_process.php" method="POST">
                            <input type="hidden" name="type" value="delete">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($movie->id) ?>">
                            <button class="delete-btn">
                                <i class="fas fa-times"></i>Deletar
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once "templates/footer.php";
?>
