<?php
include_once "presentation.class.php";
include_once "data_access.class.php";
include_once "business.class.php";
View::start("Mi historial clínico");
View::navigation();
echo "<div class='fondo-gradiente-verde'>";
if ($user = User::getLoggedUser()){
    if ($user['tipo'] == 4){
        $userid=$user['id'];
        $datos = DB::getHistorialOrderedFromPaciente($userid);
        echo "<div class='contenedor-tabla'>";
        echo "<table>
                <tr>
                <th>Fecha y hora</th>
                <th>Tipo</th>
                <th>Asunto</th>
                <th>Descripción</th>
                </tr>";
        foreach($datos as $registro){
            echo "<tr>";
            echo "<td>";
            echo date('d-m-Y H:i:s',$registro['fechahora']);
            echo "</td>";
            echo "<td>";
            switch ($registro['tipo']){
                case 1:
                    echo "Consulta";
                    break;
                case 2:
                    echo "Diagnóstico";
                    break;
                case 3:
                    echo "Tratamiento";
                    break;
                case 4:
                    echo "Seguimiento";
                    break;
                case 5:
                    echo "Resultado Prueba";
                    break;
            }
            echo "</td>";
            echo "<td>{$registro['asunto']}</td>";
            echo "<td>{$registro['descripcion']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<span class='error'>Error: No tiene permisos para usar esta página</span>";
    }
}
echo "</div>";
View::footer();
View::end();
?>