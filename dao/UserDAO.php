<?php

require_once("models/User.php");

class UserDao implements UserDaoInterface {

    private $conn;
    private $url;

    public function __construct(PDO $conn, $url){
        $this->conn = $conn;
        $this->url = $url;
    }

    /*FUNÇÕES DA INTERFACE*/
    public function buildUser($data){
        $user = new User;

        $user->id = $data["id"];
        $user->name = $data["name"];
        $user->lastname= $data["lastname"];
        $user->email = $data["email"];
        $user->password = $data["password"];
        $user->image = $data["image"];
        $user->bio = $data["bio"];
        $user->token = $data["token"];

        return $user;
    }
    public function create(User $user, $autherUser = false){

    }
    public function update(user $user){

    }
    public function findByToken($token){

    }
    public function verifyToken($protect = false){

    }
    public function setTokenToSession($token, $redirect = true){

    }
    public function authenticateUser($email, $password){

    }
    public function findByEmail($email){

    }
    public function findById($id){

    }
    public function changePassword(User $user){
        
    }

}
?>