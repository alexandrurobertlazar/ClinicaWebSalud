<?php
include_once 'presentation.class.php';
include_once 'data_access.class.php';
View::start('Clínica WebSalud');
View::navigation();
echo "<div class='contenedor-tabla fondo-gradiente-verde centro'>";
echo '<h2 class="color-gris-oscuro">Añadir especialistas</h2>';
function search() {
    if(isset($_GET['esp'])) {
        echo "<h3 class='color-gris-oscuro'>Seleccionar especialista</h3>";
        
        $especialidad = $_GET['esp'];
        echo '<form method="POST" action=' . '"add_especialista_paciente.php?esp=' . $especialidad . '">';
        $especialistas = DB::getEspecialistasNoAsignadosByEsp($_SESSION['user']['id'],$_GET['esp']);

        foreach($especialistas as $esp) {
            echo '<label class="color-gris-oscuro" for="especialista"><input type="radio" id="especialista" name="radio" value="' . $esp['id'] . '">' . $esp['nombre'] . '</label>';
        }
        echo '<div><input type="submit" name="submit" value="Añadir especialista"></div>';
        echo '</form>';
        
    }
}

if(isset($_POST['submit'])) {
    if(isset($_POST['radio'])) {
        $newID = $_POST['radio'];
        $userID = $_SESSION['user']['id'];
        $res = DB::addEspecialista($userID, $newID);
        
        if($res) {
            echo '<h2>Actualización completada</h2>';
        } else {
            echo '<h2>Error en la actualización</h2>';
        }
        
    } else {
        echo '<p>Por favor, seleccione un especialista para continuar</p>';
        search();
    }
    
} else {
    search();
}
echo "</div>";
View::footer();
View::end();