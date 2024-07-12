<?php

require_once "models/User.php";
require_once "models/Message.php";
require_once "dao/UserDAO.php";
require_once "globals.php";
require_once "db.php";

$message = new Message($BASE_URL);
$userDao = new UserDao($conn, $BASE_URL);

//VERIFICA O TIPO DE FORMULARIO
$type = filter_input(INPUT_POST, "type");    

//atualizar usuario
if($type === "update"){
    // resgat dados do usuario
    $userData = $userDao->verifyToken();

    // recebe os dados do post
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $bio = filter_input(INPUT_POST, "bio");

    //criar o objeto de usuario
    $user = new User();
    
    //preencher os dados do usuario
    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->bio = $bio;

    //upload de imagem    
    if(isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])){        
        $image = $_FILES["image"];
        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
        $jpgArray = ["image/jpeg", "image/jpg"];

        //chegar de tipo de imagem
        if(in_array($image["type"], $imageTypes)){

            //checar se é jpeg
            if(in_array($image["type"], $jpgArray)){
                $imageFile = @imagecreatefromjpeg($image["tmp_name"]);                

                //image png
            }else {
                $imageFile = @imagecreatefrompng($image["tmp_name"]);
            }
            $imageName = $user->imageGenerateName();

            @imagejpeg($imageFile, "./img/users/" . $imageName, 100);
            $userData->image = $imageName;

        } else{
            $message->setMessage("Tipo inválido de imagem, insira png ou jpeg!", "error", "back");
        }


    }

    $userDao->update($userData);

    //atualizar senha do usuario
}else  if($type === "changepassword"){

}else{
    $message->setMessage("Informações inválidas!", "error", "back");
}