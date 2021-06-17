<?php
include_once "presentation.class.php";
include_once "data_access.class.php";
View::start("Cambio especialista");
View::navigation();
echo "<div class='fondo-gradiente-verde'>";
echo "<div class='contenedor-tabla centro color-gris-oscuro'>";
echo '<h2>Cambiar de especialista</h2>';
// Proveniente de misespecialistas.php
// TODO: Especialidad nueva formato texto en form.
function search() {
    if(isset($_GET['esp'])) {
        echo "<h3>{$_GET['esp']}</h3>";
        
        $especialidad = $_GET['esp'];
        $oldID=$_GET['oldID'];
        $res = DB::findEspecialistaByID($oldID);
        $_POST['oldId'] = $oldID;
        echo '<form method="POST" action=' . '"cambiarespecialista.php?esp=' . $especialidad . '&oldID=' . $oldID . '">';
        /*  Según lo dicho en el foro, podemos escoger cualquier especialista.
            Sin embargo, vemos más sentido escoger un especialista de la misma
            especialidad. Para ello, se usaría la siguiente línea:
        DB::findEspecialistaByEsp($_GET['esp'], $especialistas); */
        // Solo ofrecemos cambio a especialistas que no están asignados.
        $especialistas = DB::getEspecialistasNoAsignados($_SESSION['user']['id']);
        $hayEspecialistas=false;
        foreach($especialistas as $esp) {
            echo '<input type="radio" name="radio" value="' . $esp['id'] . '">' . $esp['nombre'];
            $hayEspecialistas=true;
        }
        if (!$hayEspecialistas){
            echo 'Error: No hay más especialistas disponibles.';
            echo '</form><a href="./misespecialistas.php">Volver atrás</a>';
            
        } else {
            echo '<br><input type="submit" name="submit" value="Cambiar especialista">';
            echo '</form>';
        }
        
    }
}

if ($user = User::getLoggedUser()){
    if ($user['tipo'] == 4){
        if(isset($_POST['submit'])) {
            if(isset($_POST['radio'])) {
                $newID = $_POST['radio'];
                $oldID = $_GET['oldID'];
                $userID = $_SESSION['user']['id'];
                $res = DB::changeEspecialista($userID, $oldID, $newID);
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
    } else {
        echo "<span class='error'>Error: No es un paciente.</span>";
    }
} else {
    echo "<span class='error'>No ha iniciado sesión.</span>";
}

echo "</div></div>";
View::footer();
View::end();
?>