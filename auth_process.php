<?php

require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("globals.php");
require_once("db.php");

$message = new Message($BASE_URL);
$userDao = new UserDao($conn, $BASE_URL);

// Verifica o tipo de formulário
$type = filter_input(INPUT_POST, "type");

try {
    if ($type === "register") {
        handleRegister($userDao, $message);
    } elseif ($type === "login") {
        handleLogin($userDao, $message);
    } else {
        $message->setMessage("Informações inválidas!", "error", "back");
    }
} catch (Exception $e) {
    $message->setMessage("Ocorreu um erro: " . $e->getMessage(), "error", "back");
}

function handleRegister($userDao, $message) {
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    if ($name && $lastname && $email && $password) {
        if ($password === $confirmpassword) {
            if ($userDao->findByEmail($email) === false) {
                $user = new User();
                $userToken = $user->generateToken();
                $finalpassword = $user->generatePassword($password);

                $user->name = $name;
                $user->lastname = $lastname;
                $user->email = $email;
                $user->password = $finalpassword;
                $user->token = $userToken;

                $userDao->create($user, true);
                $message->setMessage("Usuário registrado com sucesso!", "success", "editprofile.php");
            } else {
                $message->setMessage("Usuário já cadastrado, tente outro e-mail.", "error", "back");
            }
        } else {
            $message->setMessage("As senhas não são iguais.", "error", "back");
        }
    } else {
        $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
    }
}

function handleLogin($userDao, $message) {
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");

    if ($userDao->authenticateUser($email, $password)) {
        $message->setMessage("Seja bem vindo!", "success", "editprofile.php");
    } else {
        $message->setMessage("Usuário e/ou senha incorretos.", "error", "back");
    }
}
?>
