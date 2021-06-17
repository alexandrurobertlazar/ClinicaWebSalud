<?php
include_once "presentation.class.php";
include_once "data_access.class.php";
include_once "business.class.php";

View::start("Añadir prueba");
View::navigation();

echo "<div class='fondo-gradiente-verde'>";
echo "<div class='contenedor-tabla centro color-gris-oscuro'>";

echo '<h2>Añadir prueba</h2>';

if($user = User::getLoggedUser()) {
    if ($user['tipo'] == 3){
        if(isset($_POST['id'])&& isset($_POST['descripcion']) && isset($_POST['asunto'])){
            $time=time();
            $auxID=$_POST['id'];
            $asunto=$_POST['asunto'];
            $descripcion=$_POST['descripcion'];
            $id = $user['id'];
            if(DB::addPruebaToHistorial($auxID,$time,$id,$asunto,$descripcion)){
                echo "Se ha añadido correctamente la prueba.";
            } else {
                echo "ERROR: No se ha podido añadir la prueba.";
            }
        } else {
            $datos=DB::getPacientes();
            echo "<table>
                    <tr>
                    <th>Seleccionar</th>
                    <th>Nombre del paciente</th>
                    </tr>";
            echo "<form method='post'>";
            foreach($datos as $registro){
                echo "<tr>";
                echo "<td><input type='radio' name='id' value={$registro['id']}></td>";
                echo "<td>{$registro['nombre']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<div class='ajustado izquierda'>";
            echo "Asunto:
            <input type='text' name='asunto'><br>
            Descripción:
            <input type='text' name='descripcion'><br>";
            echo "</div>";
            echo "<input type='submit' value='Añadir prueba'><br>";
            echo "</form>";
            
        }
    }
}
echo "</div></div>";
View::footer();
View::end();

?>