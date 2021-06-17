<?php
include_once "presentation.class.php";
include_once "data_access.class.php";
View::start("Historial paciente");
View::navigation();

echo "<div class='fondo-gradiente-verde'>";
echo "<div class='contenedor-tabla centro color-gris-oscuro'>";

function comprobarFormulario() {
    $registro = isset($_POST['registro']);
    $asunto = isset($_POST['asunto']);
    $descr = isset($_POST['descripcion']);
    $check = $registro && $asunto && $descr;
    $longitud = strlen($asunto) < 1 || strlen($asunto) > 32 || strlen($descr) < 1 || strlen($descr) > 32;
    if($check && !$longitud) {
        return true;
    } else {
        echo "<h2>Añadir un nuevo registro</h2>";
        return false;
    }
}

function formulario($id) {
    
    echo "<form method='post' onSubmit='return checkNewMedicalRecord()' action='add_to_historial_paciente.php?id={$id}' id='MedicalRecordForm'>";
    echo "<div class='ajustado'><label for='registro'> Elija el tipo de registro: </label>";

    echo "<select name='registro' id='registro'><br>";
    echo "<option value='1'>Consulta</option>";
    echo "<option value='2'>Diagnóstico</option>";
    echo "<option value='3'>Tratamiento</option>";
    echo "<option value='4'>Seguimiento</option>";
    echo "<option value='5'>Resultado Prueba</option>";
    echo "</select></div>";

    echo "<div><label for='asunto'>Asunto</label></div>";

    echo "<div><input type='text' id='asunto' name='asunto'></div>";

    echo "<div><label for='descripcion'>Descripción</label></div>";
    
    echo "<div><textarea name='descripcion' rows='4' cols='50'></textarea></div>";
    
    echo "<div class='ajustado'><input type='submit' value='Actualizar historial'></div>";
    echo "<p id='errorAddHistorial'></p>";
    echo "</form>";
    echo "</div></div>";

    echo '<div class="fondo-gris-claro">
    		<h2 class="centro"> Tenga en cuenta estas restricciones</h2>
    		<div class="contenedor-datos1">';
    echo '  <div class="dato1">
    			<h3> Descripción </h3>
    			Mínimo 12 y máximo 5000 caracteres.
		    </div>
		    <div class="dato1">
    			<h3> Asunto </h3>
    			Mínimo 1 carácter y máximo 32 caracteres.
		    </div>
	    </div>
	</div>';
}

if ($user = User::getLoggedUser()) {
    
    if ($user['tipo'] == 2) {
        $idPaciente = $_GET['id'];
        
        if(comprobarFormulario()) {
            $registro = $_POST['registro'];
            $asunto = $_POST['asunto'];
            $descr = $_POST['descripcion'];
            $fechahora = strtotime(date("d-m-Y H:i:s"));
                
            $idEspecialista = $user['id'];
                
            $params = array(htmlentities($idPaciente), htmlentities($fechahora), htmlentities($idEspecialista), htmlentities($registro), htmlentities($asunto), htmlentities($descr));
            if (DB::addToHistorialPaciente($params)) {
                echo "<h2>Registro añadido al historial</h2>";
            } else {
                echo "<h2>Error al añadir el registro</h2>";
            }
            
        } else {
            formulario($idPaciente);
        }
        
    }  else {
            echo "<span class='error'>Error: No tiene permisos para acceder a esta página.</span>";
    }
    
} else {
    echo "<span class='error'>No ha iniciado sesión.</span>";
}
            
View::footer();
View::end();
?>