<?php
include_once 'presentation.class.php';
include_once "business.class.php";
include_once "data_access.class.php";
View::start('Cambiar Especialidad');
View::navigation();
echo "<div class='contenedor-tabla fondo-gradiente-verde centro'>";
if ($user = User::getLoggedUser()){
    if ($user['tipo'] == 1){
        $idEspecialista = $_GET['id'];
        $res = DB::findEspecialistaByID($idEspecialista);
        $nombre = $res[0]['nombre'];
        echo "<form method='post'>
        <div><h3 class='color-gris-oscuro'>Inserte la especialidad que desea agregar al especialista $nombre:</h3></div>
        <input type='text' name='especialidad'>
        <input type='submit' value='Enviar'>";
        if (!empty($_POST['especialidad'])){
            $newEspecialidad=ucfirst(strtolower(trim($_POST['especialidad'])));
            if (DB::isEspecialidadAssignedToEspecialist($idEspecialista,$newEspecialidad)){
                echo "ERROR: Esta especialidad ya existe!";
            } else {
                if (DB::addEspecialidad($idEspecialista,$newEspecialidad)){
                    User::redirect("./gestionespecialistas.php");
                }
            }
        }
    } else {
        echo "<span class='error'>Error: No tiene permisos para usar esta página</span>";
    }
} else {
    echo "<span class='error'>Error: No ha iniciado sesión.</span>";
}
echo "</div>";
View::footer();
View::end();
?>