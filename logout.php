<?php
include_once 'business.class.php';
include_once "presentation.class.php";
$loggedOut=false;
if ($user = User::getLoggedUser()){
    User::logout();
    $loggedOut=true;
}
View::start("Cierre sesión");
View::navigation();
if ($loggedOut){
    User::redirect("./index.php");
}
?>