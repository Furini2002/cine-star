<?php
require_once "templates/header.php";

if (isset($userDao)) {
    $userDao->destroyToken();
}

header("Location: index.php");
exit();
?>
