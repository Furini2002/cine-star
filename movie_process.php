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

$type = filter_input(INPUT_POST, "type");
$userData = $userDao->verifyToken();

try {
    if ($type === "create") {
        handleCreate($movieDao, $message, $userData);
    } elseif ($type === "delete") {
        handleDelete($movieDao, $message, $userData);
    } elseif ($type === "update") {
        handleUpdate($movieDao, $message, $userData);
    } else {
        $message->setMessage("Informações inválidas!", "error", "back");
    }
} catch (Exception $e) {
    $message->setMessage("Ocorreu um erro: " . $e->getMessage(), "error", "back");
}

function handleCreate($movieDao, $message, $userData) {
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");

    if (!empty($title) && !empty($description) && !empty($category)) {
        $movie = new Movie();
        $movie->title = $title;
        $movie->description = $description;
        $movie->trailer = $trailer;
        $movie->category = $category;
        $movie->length = $length;
        $movie->users_id = $userData->id;

        handleImageUpload($movie, $message);

        $movieDao->create($movie);
    } else {
        $message->setMessage("Você precisa adicionar ao menos: título, descrição e categoria", "error", "back");
    }
}

function handleDelete($movieDao, $message, $userData) {
    $id = filter_input(INPUT_POST, "id");
    $movie = $movieDao->findById($id);

    if ($movie && $movie->users_id === $userData->id) {
        $movieDao->destroy($movie->id);
    } else {
        $message->setMessage("Informações inválidas!", "error", "back");
    }
}

function handleUpdate($movieDao, $message, $userData) {
    $id = filter_input(INPUT_POST, "id");
    $movieData = $movieDao->findById($id);

    if ($movieData && $movieData->users_id === $userData->id) {
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $trailer = filter_input(INPUT_POST, "trailer");
        $category = filter_input(INPUT_POST, "category");
        $length = filter_input(INPUT_POST, "length");

        if (!empty($title) && !empty($description) && !empty($category)) {
            $movieData->title = $title;
            $movieData->description = $description;
            $movieData->trailer = $trailer;
            $movieData->category = $category;
            $movieData->length = $length;

            handleImageUpload($movieData, $message);

            $movieDao->update($movieData);
        } else {
            $message->setMessage("Você precisa adicionar ao menos: título, descrição e categoria", "error", "back");
        }
    } else {
        $message->setMessage("Informações inválidas!", "error", "back");
    }
}

function handleImageUpload($movie, $message) {
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
        $image = $_FILES["image"];
        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
        $jpgArray = ["image/jpeg", "image/jpg"];

        if (in_array($image["type"], $imageTypes)) {
            $imageFile = in_array($image["type"], $jpgArray) ? @imagecreatefromjpeg($image["tmp_name"]) : @imagecreatefrompng($image["tmp_name"]);

            if ($imageFile === false) {
                $message->setMessage("Erro ao criar imagem a partir do arquivo temporário.", "error", "back");
            }

            $imageName = $movie->imageGenerateName();
            $savePath = "./img/movies/" . $imageName;

            if (!@imagejpeg($imageFile, $savePath, 100)) {
                $message->setMessage("Erro ao salvar no caminho especificado", "error", "back");
            }

            $movie->image = $imageName;
        } else {
            $message->setMessage("Tipo inválido de imagem, insira png ou jpeg!", "error", "back");
        }
    }
}
?>
