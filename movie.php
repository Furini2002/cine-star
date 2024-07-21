<?php
require_once "templates/header.php";
require_once "models/User.php";
require_once "dao/UserDAO.php";
require_once "dao/MovieDAO.php";
require_once "dao/ReviewDAO.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

$movie;
$movieDao = new MovieDAO($conn, $BASE_URL);
$reviewDao = new ReviewDAO($conn, $BASE_URL);

try {
    if (empty($id)) {
        throw new Exception("Filme não foi encontrado");
    }

    $movie = $movieDao->findById($id);

    if (!$movie) {
        throw new Exception("Filme não foi encontrado");
    }

    if (empty($movie->image)) {
        $movie->image = "movie_cover.png";
    }

    $userOwnsMovie = false;
    $alreadyReviewed = false;

    if (!empty($userData)) {
        if ($userData->id === $movie->users_id) {
            $userOwnsMovie = true;
        }
        $alreadyReviewed = $reviewDao->hasAlreadyReviewed($id, $userData->id);
    }

    $movieReviews = $reviewDao->getMoviesReview($id);
} catch (Exception $e) {
    $message->setMessage($e->getMessage(), "error", "index.php");
}
?>

<div id="main-container" class="container-fluid">
    <div class="row">
        <div class="offset-md-1 col-md-6 movie-container">
            <h1 class="page-title"><?= htmlspecialchars($movie->title) ?></h1>
            <p class="movie-details">
                <span>Duração: <?= htmlspecialchars($movie->length) ?></span>
                <span class="pipe"></span>
                <span><?= htmlspecialchars($movie->category) ?></span>
                <span class="pipe"></span>
                <span><i class="fas fa-star"></i><?= htmlspecialchars($movie->rating) ?></span>
            </p>
            <iframe width="560" height="315" src="<?= htmlspecialchars($movie->trailer) ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            <p><?= htmlspecialchars($movie->description) ?></p>
        </div>
        <div class="col-md-4">
            <div class="movie-image-container" style="background-image: url('<?= $BASE_URL ?>img/movies/<?= htmlspecialchars($movie->image) ?>')"></div>
        </div>

        <div class="offset-md-1 col-md-10" id="reviews-container">
            <h3 class="review-title">Avaliações:</h3>
            <?php if (!empty($userData) && !$userOwnsMovie && !$alreadyReviewed): ?>
                <div class="col-md-12" id="review-form-container">
                    <h4>Envie sua avaliação:</h4>
                    <p class="page-description">Preencha o formulário com a nota e comentário sobre o filme</p>
                    <form action="<?= $BASE_URL ?>review_process.php" method="POST" id="review-form">
                        <input type="hidden" name="type" value="create">
                        <input type="hidden" name="movies_id" value="<?= htmlspecialchars($movie->id) ?>">
                        <div class="form-group">
                            <label for="rating">Nota do filme:</label>
                            <select class="form-control" name="rating" id="rating">
                                <option value="">Selecione</option>
                                <option value="10">10</option>
                                <option value="9">9</option>
                                <option value="8">8</option>
                                <option value="7">7</option>
                                <option value="6">6</option>
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                                <option value="0">0</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="review">Seu comentário:</label>
                            <textarea name="review" id="review" rows="3" class="form-control" placeholder="O que você achou do filme?"></textarea>
                        </div>
                        <input type="submit" class="btn card-btn" value="Enviar comentário">
                    </form>
                </div>
            <?php endif; ?>
            <?php foreach ($movieReviews as $review): ?>
                <?php require "templates/user_review.php" ?>
            <?php endforeach; ?>
            <?php if (count($movieReviews) === 0): ?>
                <p class="empty-list">Não há comentários para este filme ainda ...</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once "templates/footer.php";
?>