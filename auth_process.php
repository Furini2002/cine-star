<?php

    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");
    require_once("globals.php");
    require_once("db.php");

    $message = new Message($BASE_URL);
    $userDao = new UserDao($conn, $BASE_URL);

    //VERIFICA O TIPO DE FORMULARIO
    $type = filter_input(INPUT_POST, "type");    

        //VERIFICAÇÃO DO TIPO DE FORMULARIO
    if($type === "register"){

        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

        
        //VERIFICAÇÃO DE DADOS MINIMOS
        if($name && $lastname && $password){

            // VERIFICAR SE AS SENHAS BATEM
            if($password === $confirmpassword){

                //Verifica se o e-amil já esta cadastrado
                if($userDao->findByEmail($email) === false){

                    $user = new User();

                    //criação de token e senha
                    $userToken = $user->generateToken();
                    $finalPassword = $user->generatePassword($password);

                    $user->name = $name;
                    $user->lastname = $lastname;
                    $user->email = $email;
                    $user->password = $finalpassword;
                    $user->token = $userToken;

                    $auth = true;

                    $userDao-> create($user, $auth);

                }else {
                    $message->setMessage("Usuário já cadastrado, tente outro e-mail.", "error", "back");
                }

            }else{
                //Mensagem de erro caso as senhas não forem iguais
                $message->setMessage("As senhas não são iguais.", "error", "back");
            }

            

        } else{
            //ENVIAR MENSAGEM DE ERRO DE DADOS FALTANTES
            $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
        }

    }else if($type === "login"){

    }
