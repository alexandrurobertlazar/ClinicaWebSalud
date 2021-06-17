<?php
include_once "presentation.class.php";
include_once "business.class.php";
include_once "data_access.class.php";
View::start("Mis pacientes");
View::navigation();

echo "<div class='fondo-gradiente-verde'>";
if ($user = User::getLoggedUser()){
    if ($user['tipo'] == 2){
        $userid = $user['id'];
        if(isset($_POST['nombre'])) {
            $datos = DB::getMisPacientes($userid,true,$_POST['nombre']);
        } else {
            $datos = DB::getMisPacientes($userid);
        }
        echo "<div class='contenedor-tabla'>";
        echo "<form method='POST'>
                <input type='text' id='searchPacienteForm' onkeyup='searchPaciente({$user['id']})' placeholder='Nombre completo' name='nombre'/>
                <input type='submit' value='Buscar'/>
            </form>";
        echo "<table id='pacTable'>
                <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Acción a realizar</th>
                </tr>";
        foreach($datos as $registro){
            echo "<tr id='result'>";
            echo "<td>{$registro['nombre']}</td>";
            echo "<td>{$registro['email']}</td>";
            echo "<td><a href='historialpaciente.php?id={$registro['id']}'>Ver historial</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
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