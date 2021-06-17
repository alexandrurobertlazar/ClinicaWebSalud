<?php
// TODO: seleccion especialidad con nombre especialista, enlaces a acciones
include_once 'presentation.class.php';
include_once 'data_access.class.php';
View::start('Cambiar especialidad');
View::navigation();
echo "<div class='fondo-gradiente-verde centro'>";
if ($user = User::getLoggedUser()){
    if ($user['tipo'] == 1){
        $id = $_GET['id'];
        // Obtenemos especialidades
        $res = DB::findEspecialistaByID($id);
        $nombre = $res[0]['nombre'];
        $especialidades = DB::getEspecialidadesFromEspecialist($id);
        echo "<div class='contenedor-tabla centro'>
            <div><h2 class='color-gris-oscuro'> Especialista seleccionado: $nombre <h2></div>
            
            <a href='anadirespecialidad.php?id=$id'>Añadir especialidad</a>
            
            <table>
            <tr>
            <th>Especialidad</th>
            <th>Acción</th>
            </tr>";
            
        foreach($especialidades as $rgst){
            echo "<tr>
                <td>{$rgst['especialidad']}</td>
                <td><a href='borrarespecialidad.php?id=$id&especialidad={$rgst['especialidad']}'>Borrar especialidad</a><br>
                <a href='cambiarespecialidad.php?id=$id&especialidad={$rgst['especialidad']}'>Cambiar especialidad</a>
                </td>
                </tr>";
        }
        echo "</table></div>"; //div de los contenedores
    }
}
echo "</div>"; //div de fondo gradiente
View::footer();
View::end();
?>