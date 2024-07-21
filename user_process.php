<?php

require_once "models/User.php";
require_once "models/Message.php";
require_once "dao/UserDAO.php";
require_once "globals.php";
require_once "db.php";

$message = new Message($BASE_URL);
$userDao = new UserDao($conn, $BASE_URL);

$type = filter_input(INPUT_POST, "type");

try {
    if ($type === "update") {
        handleUpdate($userDao, $message);
    } elseif ($type === "changepassword") {
        handleChangePassword($userDao, $message);
    } else {
        throw new Exception("Informações inválidas!");
    }
} catch (Exception $e) {
    $message->setMessage($e->getMessage(), "error", "back");
}

function handleUpdate($userDao, $message) {
    $userData = $userDao->verifyToken();

    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $bio = filter_input(INPUT_POST, "bio", FILTER_SANITIZE_STRING);

    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->bio = $bio;

    if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
        handleImageUpload($userData, $message);
    }

    $userDao->update($userData);
}

function handleImageUpload($userData, $message) {
    $image = $_FILES["image"];
    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
    $jpgArray = ["image/jpeg", "image/jpg"];

    if (in_array($image["type"], $imageTypes)) {
        $imageFile = in_array($image["type"], $jpgArray) ? @imagecreatefromjpeg($image["tmp_name"]) : @imagecreatefrompng($image["tmp_name"]);

        if ($imageFile === false) {
            throw new Exception("Erro ao criar imagem a partir do arquivo temporário.");
        }

        $user = new User();
        $imageName = $user->imageGenerateName();

        if (!@imagejpeg($imageFile, "./img/users/" . $imageName, 100)) {
            throw new Exception("Erro ao salvar a imagem.");
        }

        $userData->image = $imageName;
    } else {
        throw new Exception("Tipo inválido de imagem, insira png ou jpeg!");
    }
}

function handleChangePassword($userDao, $message) {
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword", FILTER_SANITIZE_STRING);
    $userData = $userDao->verifyToken();
    $id = $userData->id;

    if ($password === $confirmpassword) {
        $user = new User();
        $finalpassword = $user->generatePassword($password);

        $user->password = $finalpassword;
        $user->id = $id;

        $userDao->changePassword($user);
    } else {
        throw new Exception("As senhas não são iguais!");
    }
}
?>
