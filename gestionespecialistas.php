<?php
include_once "business.class.php";
include_once "presentation.class.php";
View::start("Gestión especialistas");
View::navigation();
echo "<div class='fondo-gradiente-verde'>";
if ($user = User::getLoggedUser()){
    if ($user['tipo'] == 1){
        $datos=DB::getAllEspecialists();
        echo "<div class='contenedor-tabla'>
            <table>
            <tr>
            <th>Acción a realizar</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Especialidades</th>
            </tr>";
        foreach($datos as $registro){
            echo "<tr>";
            echo "<td><a href='modificarespecialidad.php?id={$registro['id']}'>Cambiar sus especialidades<br>";
            echo "<td>{$registro['nombre']}</td>";
            echo "<td>{$registro['email']}</td>";
            echo "<td>";
            // Obtenemos especialidades
            $especialidades = DB::getEspecialidadesFromEspecialist($registro['id']);
            foreach($especialidades as $rgst){
                echo "{$rgst['especialidad']}<br>";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<span class='error'>Error: No es administrador.</span>";
    }
} else {
    echo "<span class='error'>No ha iniciado sesión.</span>";
}
echo "</div>";
View::footer();
View::end();
?>