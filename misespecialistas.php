<?php
include_once "presentation.class.php";
include_once "data_access.class.php";
include_once "business.class.php";
View::start("Mis especialistas");
View::navigation();
echo "<div class='fondo-gradiente-verde'>";
echo "<div class='contenedor-tabla centro'>";

function showEspecialist($especialist) {
    $name = $especialist[0]['nombre'];
    $account = $especialist[0]['cuenta'];
    $email = $especialist[0]['email'];
    $especialidad = $especialist[0]['especialidad'];
    $id = $especialist[0]['idespecialista'];
    echo "<tr id=\"fila$id\">";
    echo "<td>{$name}</td>";
    echo "<td>{$email}</td>";
    echo "<td>{$especialidad}</td>";
    echo '<td><a href=' . '"cambiarespecialista.php?esp=' . $especialidad . '&oldID=' . $id . '">Cambiar ' . $especialidad . '</a></td>';
    echo "<td><button onClick='eliminarEspecialista({$id},{$_SESSION['user']['id']})'>Eliminar asginación</button></td>";
    echo '</tr>';
}

function search(&$count,$userID) {
    $ids = DB::getEspecialists($userID);
    echo '<table>';
    echo '<tr>';
    echo '<th>Nombre</th>';
    echo '<th>Email</th>';
    echo '<th>Especialidad</th>';
    echo '<th>Cambiar</th>';
    echo '<th>Eliminar</th>';
    echo '</tr>';
    $count = count($ids);
    foreach($ids as $id) {
        $res = DB::findEspecialistaByID($id['idespecialista']);
        showEspecialist($res);
    }
    echo '</table>';
    
}

if ($user = User::getLoggedUser()){
    if ($user['tipo'] == 4){
        $userID=$_SESSION['user']['id'];
        search($count,$userID);
        // Solo se ofrece añadir un especialista de cierta especialidad
        // si no están todos los especialistas de esa especialidad ya
        // asignados.
        $especialidades=DB::getEspecialidadesNoAsignadas($userID);
        foreach ($especialidades as $e){
            echo '<a class="ajustado" href=' . '"add_especialista_paciente.php?esp=' . $e['especialidad'] . '">Añadir ' . $e['especialidad'] . '</a>';
        }
    } else {
        echo "<span class='error'>Error: No tiene permisos para usar esta página</span>";
    }
}

echo "</div></div>";
View::footer();
View::end();
?>