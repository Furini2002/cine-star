<?php
    require_once "templates/header.php";

    //verifica se o usuario esta atenticado
    require_once "models/User.php";
    require_once "dao/UserDao.php";
    require_once "dao/MovieDao.php";

    $user = new User();
    $userDao = new UserDao($conn, $BASE_URL);
    $movieDao = new MovieDAO($conn, $BASE_URL);

    //recreber id do usuario
    $id = filter_input(INPUT_GET,"id");

    if(empty($id)){

        if($userData->id){
            $id = $userData->id;
        } else {
            $message->setMessage("Usuário não  encontrado", "error", "index.php");
        }
    } else {

        $userData = $userDao->findById($id);

        //se não encontarr o usuário
        if(!$userData){
            $message->setMessage("Usuário não  encontrado", "error", "index.php");
        }        
    }

    $fullname = $user->getFullName($userData);

    if($userData->image == ""){
        $userData->image = "user.png";
    }

    // filmes que o usuario adicionou
    $userMovies = $movieDao->getMoviesByUserId($id);
?>

<div class="main-container" id="main-container">
    <div class="col-md-8 offset-md-2">
        <div class="row profile-container">
            <div class="col-md-12 about-container">
                <h1 class="page-title"><?= $fullname ?></h1>
                <div id="profile-image-container" class="profile-image" style="background-image: url('<?= $BASE_URL ?>img/users/<?= $userData->image ?>')"></div>
                <h3 class="about-tile">Sobre:</h3>
                <?php if(!empty($userData->bio)): ?>
                    <p class="profile-description"><?= $userData->bio?></p>
                <?php else: ?>
                    <p class="profile-description">O usuário ainda não escreveu nada aqui ...</p>
                <?php endif; ?>


            </div>
            <div class="col-md-12 added-movies-container">
                <h3>Filmes que o usuário enviou:</h3>
                <div class="movies-container">
                    <?php foreach($userMovies as $movie): ?>
                        <?php require "templates/movie_card.php"; ?>
                    <?php endforeach; ?>
                    <?php if(count($userMovies) === 0): ?>
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