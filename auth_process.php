<?php

    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");
    require_once("globals.php");
    require_once("db.php");

    $message = new Message($BASE_URL);

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

            

        } else{
            //ENVIAR MENSAGEM DE ERRO DE DADOS FALTANTES
            $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
        }

    }else if($type === "login"){

    }
