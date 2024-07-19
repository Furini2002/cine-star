<?php
    //nome completo do usuario
    require_once "models/User.php";
    $userModel = new User();
    $fullname = $userModel->getFullName($review->user);

    //Verificar se o filme tem imagem
    if($review->user->image == ""){
        $review->user->image = "user.png";
    }
?>

<div class="col-md-12 review">
    <div class="row">
        <div class="col-md-1">
            <div class="profile-image-container review-image" style="background-image: url('<?= $BASE_URL?>img/users/<?= $review->user->image ?>')"></div>
        </div>
        <div class="col-md-9 author-details-container">
            <h4 class="author-name">
                <a href="#" ><?= $fullname ?></a>
            </h4>
            <p><i class="fas fa-star"></i><?= $review->rating ?></p>
        </div>  
        <div class="col-md-12">
            <p class="comment-title">Comentário:</p>
            <p><?= $review->review ?></p>
        </div>
    </div>
</div>