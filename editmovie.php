<?php
require_once "templates/header.php";
require_once "models/User.php";
require_once "dao/UserDao.php";
require_once "dao/MovieDao.php";

$userDao = new UserDao($conn, $BASE_URL);
$userData = $userDao->verifyToken(true);

$movieDao = new MovieDAO($conn, $BASE_URL);
$id = filter_input(INPUT_GET, "id");

if (empty($id)) {
    $message->setMessage("Filme não foi encontrado", "error", "index.php");
} else {
    $movie = $movieDao->findById($id);
    if (!$movie) {
        $message->setMessage("Filme não foi encontrado", "error", "index.php");
    }
}

if (empty($movie->image)) {
    $movie->image = "movie_cover.png";
}
?>

<div id="main-container" class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 offset-md-1">
                <h1><?= htmlspecialchars($movie->title) ?></h1>
                <p class="page-description">Altere os dados do filme no formulário abaixo:</p>
                <form action="<?= htmlspecialchars($BASE_URL) ?>movie_process.php" method="POST" enctype="multipart/form-data" id="edit-movie-form">
                    <input type="hidden" name="type" value="update">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($movie->id) ?>">
                    <div class="form-group">
                        <label for="title">Título:</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Digite o título do seu filme" value="<?= htmlspecialchars($movie->title) ?>">
                    </div>
                    <div class="form-group">
                        <label for="image">Imagem:</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                    </div>
                    <div class="form-group">
                        <label for="length">Duração:</label>
                        <input type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do filme" value="<?= htmlspecialchars($movie->length) ?>">
                    </div>
                    <div class="form-group">
                        <label for="category">Categoria:</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">Selecione</option>
                            <option value="Ação" <?= $movie->category === "Ação" ? "selected" : "" ?>>Ação</option>
                            <option value="Drama" <?= $movie->category === "Drama" ? "selected" : "" ?>>Drama</option>
                            <option value="Comédia" <?= $movie->category === "Comédia" ? "selected" : "" ?>>Comédia</option>
                            <option value="Fantasia / Ficção" <?= $movie->category === "Fantasia / Ficção" ? "selected" : "" ?>>Fantasia / Ficção</option>
                            <option value="Romance" <?= $movie->category === "Romance" ? "selected" : "" ?>>Romance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="trailer">Trailer:</label>
                        <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer" value="<?= htmlspecialchars($movie->trailer) ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição:</label>
                        <textarea name="description" id="description" class="form-control" rows="5" placeholder="Descreva o filme ..."><?= htmlspecialchars($movie->description) ?></textarea>
                    </div>
                    <input type="submit" class="btn card-btn" value="Editar filme">
                </form>
            </div>
            <div class="col-md-3">
                <div class="movie-image-container" style="background-image: url('<?= htmlspecialchars($BASE_URL) ?>img/movies/<?= htmlspecialchars($movie->image) ?>')"></div>
            </div>
        </div>
    </div>
</div>

<?php
require_once "templates/footer.php";
?>
