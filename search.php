<?php
    require_once("templates/header.php");
    require_once("dao/MovieDAO.php");

    //Dao dos filmes
    $movieDao = new MovieDAO($conn, $BASE_URL);

    //resgata da busca do uusario
    $q = filter_input(INPUT_GET, "q");    

    $movies = $movieDao->findyByTitle($q);
?>

    <div id="main-container" class="container-fluid">
        <h2 class="section-title" id="search-title">Você está buscando por: <sapan id="search-result"><?= $q ?></sapan></h2>
        <p class="section-description">Resultados de busca retornados com base na sua pesquisa.</p>
        <div class="movies-container">
            <?php foreach($movies as $movie): ?>
                <?php require "templates/movie_card.php" ?>                            
            <?php endforeach; ?>   
            <?php if(count($movies) === 0): ?>  
                <p class="empty-list">Não há filma para esta busca, <a href="<?= $BASE_URL ?>" class="back-link">voltar.</a> </p>
            <?php endif; ?>
        </div>
    </div>

<?php
    require_once "templates/footer.php";
?>