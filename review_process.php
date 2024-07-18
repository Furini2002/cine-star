<?php

    require_once "models/Movie.php";
    require_once "models/Message.php";
    require_once "dao/MovieDAO.php";
    require_once "dao/UserDAO.php";
    require_once "globals.php";
    require_once "db.php";

    $message = new Message($BASE_URL);
    $userDao = new UserDao($conn, $BASE_URL);   
    $movieDao = new MovieDAO($conn, $BASE_URL); 

    //recebendo o tipo do formulario
    $type = filter_input(INPUT_POST,"type");

    //resgatando os dados so usuário
    $userData = $userDao->verifyToken();

    if($type === "create"){
        
    }
