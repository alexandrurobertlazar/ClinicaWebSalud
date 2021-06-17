<?php
include_once 'presentation.class.php';
include_once "business.class.php";
include_once "data_access.class.php";
View::start('Borrar especialista');
View::navigation();
echo "<div class='contenedor-tabla fondo-gradiente-verde'>";
if ($user = User::getLoggedUser()){
    if ($user['tipo'] == 1){
        $idespecialista = $_GET['id'];
        $especialidad = $_GET['especialidad'];
        if (DB::deleteEspecialidad($idespecialista,$especialidad)){
            User::redirect("./gestionespecialistas.php");
        } else {
            echo "<h2>Error: No se ha podido efectuar el borrado.</h2>";
        }
    } else {
        echo "<h2>Error: No tiene permisos para usar esta página</h2>";
    }
} else {
    echo "<h2>Error: No ha iniciado sesión.</h2>";
}
echo "</div>";
View::footer();
View::end();
?>