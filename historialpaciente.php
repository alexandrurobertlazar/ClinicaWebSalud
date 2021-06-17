<?php
include_once "presentation.class.php";
View::start("Historial paciente");
View::navigation();
echo "<div class='fondo-gradiente-verde'>";
    if ($user = User::getLoggedUser()){
        if ($user['tipo'] == 2){
            $idPaciente=$_GET['id'];
            $datos=DB::getHistorialFromPaciente($_GET['id']);
            echo "<div class='contenedor-tabla'>";
            echo "<form method='POST' action='add_to_historial_paciente.php?id={$idPaciente}'>
                    <input type='submit' value='Añadir registro'/>
                 </form>";
            echo "<table>";
            echo "<tr>";
            echo "<th>Fecha y hora</th>";
            echo "<th>Tipo</th>";
            echo "<th>Asunto</th>";
            echo "<th>Descripción</th>";
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
            echo "</div>"; //div de tabla
        } else {
            echo "<span class='error'>Error: No tiene permisos para acceder a esta página.</span>";
        }
    } else {
        echo "<span class='error'>No ha iniciado sesión.</span>";
    }
echo "</div>"; //div de fondo
View::footer();
View::end();
// Página que muestra historial de paciente seleccionado en mispacientes.php
?>