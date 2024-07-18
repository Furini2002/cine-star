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

    //VERIFICA O TIPO DE FORMULARIO
    $type = filter_input(INPUT_POST, "type");  
    
    // resgat dados do usuario
    $userData = $userDao->verifyToken();

    if($type === "create"){        

        //receber os dados do input
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $trailer = filter_input(INPUT_POST, "trailer");
        $category = filter_input(INPUT_POST, "category");
        $length = filter_input(INPUT_POST, "length");
    
        //criar o objeto de usuario
        $movie = new Movie();

        //Validação minima de dados
        if(!empty($title) && !empty($description) && !empty($category)){

            $movie->title = $title;
            $movie->description = $description;
            $movie->trailer = $trailer;
            $movie->category = $category;
            $movie->length = $length;
            $movie->users_id = $userData->id;

            //upload de imagem do filme
            if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

                $image = $_FILES["image"];
                $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                $jpgArray = ["image/jpeg", "image/jpg"];

                //checando tipo da imagem
                if(in_array($image["type"], $imageTypes)){

                    // chaca se imagem é jpg
                    if(in_array($image["type"], $jpgArray)){
                        $imageFile = @imagecreatefromjpeg($image["tmp_name"]);
                    }else{
                        $imageFile = @imagecreatefrompng($image["tmp_name"]);
                    } 

                    //checa se conseguiu criar a imagem por meio do arquivo temporario
                    if($imageFile === false) {
                        $message->setMessage("Erro ao criar imagem a partir do arquivo temporário.", "error", "back");
                    }
            
                    $imagename = $movie->imageGenerateName();

                    //verifica se conseguiu gerar o nome
                    if(!$imagename) {
                        $message->setMessage("Erro ao gerar nome da imagem", "error", "back");
                    }

                    //verifica se conseguiu salvar no caminho especificado
                    $savePath = "./img/movies/" . $imagename;
                    if(!@imagejpeg($imageFile, $savePath, 100)) {
                        $message->setMessage("Erro ao salvar no caminho especificado", "error", "back");
                    }

                    $movie->image = $imagename;

                }else {
                    $message->setMessage("Tipo inválido de imagem, insira png ou jpeg!", "error", "back");
                }                
            }
            $movieDao->create($movie);
        } else {
            $message->setMessage("Você precisa adicionar ao menos: título, descrição e categoria", "error", "back");
        }

    } else if($type === "delete"){
        //recebe os dados do formulario
        $id = filter_input(INPUT_POST, "id");

        $movie = $movieDao->findById($id);

        if($movie){

            //verificar se o filme é do usuario
            if($movie->users_id === $userData->id){

                $movieDao->destroy($movie->id);
                
            }else{
                $message->setMessage("Informações inválidas!", "error", "back");
            }

        }else{
            $message->setMessage("Informações inválidas!", "error", "back");
        }

    } else if($type === "update"){

        //receber os dados do input
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $trailer = filter_input(INPUT_POST, "trailer");
        $category = filter_input(INPUT_POST, "category");
        $length = filter_input(INPUT_POST, "length");
        $id = filter_input(INPUT_POST, "id");

        $movieData = $movieDao->findById($id);
        
        //verifica se encontrou o filme
        if($movieData){

            //verificar se o filme é do usuario
            if($movieData->users_id === $userData->id){

                //validação minima de dados
                if(!empty($title) && !empty($description) && !empty($category)){

                // edição de filme
                $movieData->title = $title;
                $movieData->description = $description;
                $movieData->trailer = $trailer;
                $movieData->category = $category;
                $movieData->length = $length;


                //upload de imagem do filme                
                if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {                      

                    $image = $_FILES["image"];
                    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                    $jpgArray = ["image/jpeg", "image/jpg"];

                    //checando tipo da imagem
                    if(in_array($image["type"], $imageTypes)){

                        // chaca se imagem é jpg
                        if(in_array($image["type"], $jpgArray)){
                            $imageFile = @imagecreatefromjpeg($image["tmp_name"]);
                        }else{
                            $imageFile = @imagecreatefrompng($image["tmp_name"]);
                        } 

                        //checa se conseguiu criar a imagem por meio do arquivo temporario
                        if($imageFile === false) {
                            $message->setMessage("Erro ao criar imagem a partir do arquivo temporário.", "error", "back");
                        }
                        
                        //gerando o nome da imagem
                        $movie = new Movie();

                        $imageName = $movie->imageGenerateName();

                        imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

                        $movieData->image = $imageName;                        
                       
                    }else {
                        $message->setMessage("Tipo inválido de imagem, insira png ou jpeg!", "error", "back"); exit;
                    }                
                }               

                $movieDao->update($movieData);
                
                } else{
                $message->setMessage("Você precisa adicionar ao menos: título, descrição e categoria", "error", "back");
            }
            
        } else{
            $message->setMessage("Informações inválidas!", "error", "back");
        }

    }else{
    $message->setMessage("Informações inválidas!", "error", "back");
    }
        

} else{
    $message->setMessage("Informações inválidas!", "error", "back");
} 
