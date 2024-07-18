<?php
    require_once "templates/header.php";

    require_once "models/User.php";
    require_once "dao/UserDAO.php";
    require_once "dao/MovieDAO.php";

    //pegar o id do filme
    $id = filter_input(INPUT_GET, "id");

    $movie;

    $movieDao = new MovieDAO($conn, $BASE_URL);

    if(empty($id)) {

        $message->setMessage("Filme não foi encontrado", "error", "index.php");

    } else{
        $movie = $movieDao->findById($id);

        //verifica se o filme existe
        if(!$movie){
            $message->setMessage("Filme não foi encontrado", "error", "index.php");
        }
    }

    //Verificar se o filme tem imagem
    if($movie->image == ""){
        $movie->image = "movie_cover.png";
    }

    //verificar se o filme é do usuario
    $userOwnsMovie = false;

    if(!empty($userData)){
         if($userData->id === $movie->users_id){
            $userOwnsMovie = true;
         }
    }

    //resgatar as reviws so filme

    ?>

    <div id="main-container" class="container-fluid">
        <div class="row">
            <div class="offset-md-1 col-md-6 movie-container">
                <h1 class="page-title"><?= $movie->title?></h1>
                <p class="movie-details">
                    <span>Duração: <?= $movie->length?></span>
                    <span class="pipe"></span>
                    <span><?= $movie->category?></span>
                    <span class="pipe"></span>
                    <span><i class="fas fa-star"></i>9</span>
                </p>
                <iframe width="560" height="315" src="<?= $movie->trailer ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                <p><?= $movie->description ?></p>                
            </div>
            <div class="col-md-4">
                <div class="movie-image-container" style="background-image: url('<?= $BASE_URL?>img/movies/<?= $movie->image ?>')"></div>                                    
            </div>

            <div class="offset-md-1 col-md-10" id="reviews-container">
                <h3 class="review-title">Avaliações:</h3>
                <!--verifica se habilita a reviw para o usuario-->
                <?php if(!empty($userData) && !$userOwnsMovie && !$alredyReviewed): ?>
                <div class="col-md-12" id="review-form-container">
                    <h4>Envie sua avaliação:</h4>
                    <p class="page-descrioption">Preencha o formulario com anota e comentário sobre o filme</p>
                    <form action="<?= $BASE_URL?>review_process.php" method="POST" id="review-form">
                        <input type="hidden" name="type" value="cerate">
                        <input type="hidden" name="movies_id" value="<?= $movie->id ?>">
                        <div class="fomr-group">
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
                            <label for="review" class="review">Seu comentário:</label>
                            <textarea name="review" id="review" rows="3" class="form-control" palceholder="O que vpcê achou do filme?"></textarea>
                        </div>
                        <input type="submit" class="btn card-btn" value="Enviar comentário">
                    </form>
                </div>
                <?php endif; ?>
                <!--comentarios-->
                <div class="col-md-12 review">
                    <div class="row">
                        <div class="col-md-1">
                            <div class="profile-image-container review-image" style="background-image: url('<?= $BASE_URL?>img/users/user.png')"></div>
                        </div>
                        <div class="col-md-9 author-details-container">
                            <h4 class="author-name">
                                <a href="#" >Teste teste</a>
                            </h4>
                            <p><i class="fas fa-star"></i>9</p>
                        </div>  
                        <div class="col-md-12">
                            <p class="comment-title">Comentário:</p>
                            <p>Este é o comentario do usuário</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 review">
                    <div class="row">
                        <div class="col-md-1">
                            <div class="profile-image-container review-image" style="background-image: url('<?= $BASE_URL?>img/users/user.png')"></div>
                        </div>
                        <div class="col-md-9 author-details-container">
                            <h4 class="author-name">
                                <a href="#">Teste teste</a>
                            </h4>
                            <p><i class="fas fa-star"></i>9</p>
                        </div>  
                        <div class="col-md-12">
                            <p class="comment-title">Comentário:</p>
                            <p>Este é o comentario do usuário</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    require_once "templates/footer.php";
    ?>


