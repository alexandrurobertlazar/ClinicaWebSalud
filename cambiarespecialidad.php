<?php
include_once 'presentation.class.php';
include_once "business.class.php";
include_once "data_access.class.php";
View::start('Cambiar especialidad');
View::navigation();
echo "<div class='contenedor-tabla fondo-gradiente-verde centro'>";
if ($user = User::getLoggedUser()){
    if ($user['tipo'] == 1){
        echo "<form method='post'>
        <div> <h3 class='color-gris-oscuro'>Inserte la especialidad que desea agregar al especialista:</h3></div>
            <input type='text' name='newEspecialidad'>
            <input type='submit' value='Actualizar especialidad'>
            </form>";
        $idEspecialista = $_GET['id'];
        $especialidadAntigua=$_GET['especialidad'];
        if (!empty($_POST['newEspecialidad'])){
            $newEspecialidad=trim(ucfirst(strtolower($_POST['newEspecialidad'])));
            if (DB::isEspecialidadAssignedToEspecialist($idEspecialista,$newEspecialidad)){
                echo "Error: Esta especialidad ya existe.";
            } else {
                if (DB::updateEspecialidadToEspecialista($idEspecialista,$newEspecialidad,$especialidadAntigua)){
                    User::redirect("./gestionespecialistas.php");
                } else {
                    echo "ERROR: No se ha podido actualizar la especialidad.";
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