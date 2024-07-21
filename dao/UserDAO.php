<?php

require_once("models/User.php");
require_once("models/Message.php");

class UserDao implements UserDaoInterface {

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url) {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }

    public function buildUser($data) {
        $user = new User();

        $user->id = $data["id"];
        $user->name = $data["name"];
        $user->lastname = $data["lastname"];
        $user->email = $data["email"];
        $user->password = $data["password"];
        $user->image = $data["image"];
        $user->bio = $data["bio"];
        $user->token = $data["token"];

        return $user;
    }

    public function create(User $user, $authUser = false) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO users (name, lastname, email, password, token) VALUES (:name, :lastname, :email, :password, :token)");

            $stmt->bindParam(":name", $user->name);
            $stmt->bindParam(":lastname", $user->lastname);
            $stmt->bindParam(":email", $user->email);
            $stmt->bindParam(":password", $user->password);
            $stmt->bindParam(":token", $user->token);

            $stmt->execute();

            // Autenticar usuário, caso o auth seja true
            if ($authUser) {
                $this->setTokenToSession($user->token);
            }
        } catch (Exception $e) {
            // Tratamento de exceções
            $this->message->setMessage("Erro ao criar usuário: " . $e->getMessage(), "error", "back");
        }
    }

    public function update(User $user, $redirect = true) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET name = :name, lastname = :lastname, email = :email, image = :image, bio = :bio, token = :token WHERE id = :id");

            $stmt->bindParam(":name", $user->name);
            $stmt->bindParam(":lastname", $user->lastname);
            $stmt->bindParam(":email", $user->email);
            $stmt->bindParam(":image", $user->image);
            $stmt->bindParam(":bio", $user->bio);
            $stmt->bindParam(":token", $user->token);
            $stmt->bindParam(":id", $user->id);

            $stmt->execute();

            if ($redirect) {
                $this->message->setMessage("Dados atualizados com sucesso!", "success", "editprofile.php");
            }
        } catch (Exception $e) {
            // Tratamento de exceções
            $this->message->setMessage("Erro ao atualizar usuário: " . $e->getMessage(), "error", "back");
        }
    }

    public function findByToken($token) {
        if ($token != "") {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE token = :token");
            $stmt->bindParam(":token", $token);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                return $this->buildUser($data);
            }
        }
        return false;
    }

    public function verifyToken($protect = false) {
        if (!empty($_SESSION["token"])) {
            $token = $_SESSION["token"];
            $user = $this->findByToken($token);

            if ($user) {
                return $user;
            } else {
                if ($protect) {
                    $this->message->setMessage("Faça a autenticação para acessar esta página!", "error", "index.php");
                }
            }
        } else if ($protect) {
            $this->message->setMessage("Faça a autenticação para acessar esta página!", "error", "index.php");
        }
    }

    public function setTokenToSession($token, $redirect = true) {
        // Salvar token na sessão
        $_SESSION["token"] = $token;

        if ($redirect) {
            $this->message->setMessage("Seja bem-vindo!", "success", "editprofile.php");
        }
    }

    public function authenticateUser($email, $password) {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            // Gerar o token e inserir na sessão
            $token = $user->generateToken();
            $this->setTokenToSession($token, false);

            // Atualizar o token no usuário
            $user->token = $token;
            $this->update($user, false);

            return true;
        }
        return false;
    }

    public function findByEmail($email) {
        if ($email != "") {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                return $this->buildUser($data);
            }
        }
        return false;
    }

    public function findById($id) {
        if ($id != "") {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                return $this->buildUser($data);
            }
        }
        return false;
    }

    public function changePassword(User $user) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET password = :password WHERE id = :id");

            $stmt->bindParam(":password", $user->password);
            $stmt->bindParam(":id", $user->id);

            $stmt->execute();

            // Redireciona e apresenta a mensagem de sucesso
            $this->message->setMessage("Senha alterada com sucesso!", "success", "editprofile.php");
        } catch (Exception $e) {
            // Tratamento de exceções
            $this->message->setMessage("Erro ao alterar a senha: " . $e->getMessage(), "error", "back");
        }
    }

    public function destroyToken() {
        // Remove o token da sessão
        unset($_SESSION["token"]);

        // Redirecionar e apresentar a mensagem
        $this->message->setMessage("Você fez logout com sucesso!", "success", "index.php");
    }
}
?>
