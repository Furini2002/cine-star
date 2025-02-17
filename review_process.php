<?php

    require_once "models/Movie.php";
    require_once "models/Message.php";
    require_once "models/Review.php";
    require_once "dao/MovieDAO.php";
    require_once "dao/UserDAO.php";
    require_once "dao/ReviewDAO.php";
    require_once "globals.php";
    require_once "db.php";

    $message = new Message($BASE_URL);
    $userDao = new UserDao($conn, $BASE_URL);   
    $movieDao = new MovieDAO($conn, $BASE_URL); 
    $reviewDao = new ReviewDAO($conn, $BASE_URL); 

    //recebendo o tipo do formulario
    $type = filter_input(INPUT_POST,"type");

    //resgatando os dados so usuário
    $userData = $userDao->verifyToken();

    //print_r($type); exit;

    if($type === "create"){

     //recebendo os dados do POST
     $rating = filter_input(INPUT_POST,"rating");
     $review = filter_input(INPUT_POST,"review");
     $movies_id = filter_input(INPUT_POST,"movies_id");
     $users_id = $userData->id;

     $reviewObject = new Review();

     $movieData = $movieDao->findById($movies_id);

     //validando se o filme existe
     if($movieData){

        //verificar dados minimos
        if(!empty($rating) && !empty($review) && !empty($movies_id)) {

            $reviewObject->rating = $rating;
            $reviewObject->review = $review;
            $reviewObject->movies_id = $movies_id;
            $reviewObject->users_id = $users_id;

            $reviewDao->create($reviewObject);

        } else {            
            $message->setMessage("Você precisa inserir uma nota e um comentário", "error", "back");
        }

     } else {
        $message->setMessage("Informações inválidas1!", "error", "index.php");
     }
        
    }else{
        $message->setMessage("Informações inválidas2!", "error", "index.php");
    }
