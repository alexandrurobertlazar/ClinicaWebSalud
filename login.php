<?php
include_once 'presentation.class.php';
include_once 'data_access.class.php';
include_once 'business.class.php';
$formulario=<<<FORM
<div class="login-container">
    <form method=post id="form" action="">
        <h3>¿Quién eres?</h3>
        <div class="container">
            <input type="text" placeholder="Usuario" name="cuenta"/>
        </div>
        <div class="container">
            <input type="password" placeholder="Contraseña" name="clave"/>
        </div>
        <input type="submit" value="Iniciar sesión">
    </form>
</div>
FORM;
$identCorrecta=false;
$prevLogIn=false;
$formEmpty=true;
if (isset($_POST['cuenta'])&& isset($_POST['clave'])){
    $formEmpty=false;
    if (User::login($_POST['cuenta'], $_POST['clave'])){
        $identCorrecta=true;
    }
} else{
    if ($user = User::getLoggedUser()){
        $prevLogIn=true;
    }
}

View::start('Inicio de sesión');
View::navigation();
if (!$formEmpty){
    if ($identCorrecta){
        User::redirect("./index.php");
    } else {
        User::redirect("./login.php");
    }
} else {
    if ($prevLogIn){
        echo "Error: Tiene que cerrar sesión para iniciar una nueva sesión.<br>";
        echo "<a href='logout.php'>Cerrar sesión</a>";
    } else {
        echo $formulario;
    }
}

View::end();