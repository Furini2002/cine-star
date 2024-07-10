<?php
    require_once "templates/header.php";
?>

    <div id="main-container" class="container-fluid">
       <dic class="col-md-12">
        <div class="row" id="auth-row">
            <div class="col-md-4" id="login-container">
                <h2>Entrar</h2>
                <form action="" method="POST">
                <input type="hidden" noem="type" value="login">
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Digite aqui seu e-mail">
                    </div>
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Digite aqui seu senha">
                    </div>
                    <input type="submit" class="btn card-btn" value="Entar">
                </form>
            </div>
            <div class="col-md-4" id="register-container">
                <h2>Criar Conta</h2>
                <form action="<?= $BASE_URL ?>auth_process.php" method="POST">
                    <input type="hidden" name="type" value="register">
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Digite aqui seu e-mail">
                    </div>
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Digite aqui seu nome">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Sobrenome:</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Digite aqui seu sobrenome">
                    </div>
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Digite aqui seu senha">
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword">Confirmação de senha:</label>
                        <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirme sua senha">
                    </div>
                    <input type="submit" class="btn card-btn" value="Registrar">
                </form>
            </div>
        </div>
       </dic>
    </div>

<?php
    require_once "templates/footer.php";
?>