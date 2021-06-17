<?php
include_once 'business.class.php';
include_once 'data_access.class.php';
class View{
    public static function  start($title){
        $html = "<!DOCTYPE html>
    <html>
        <head>
            <meta charset=\"utf-8\">
            <link rel=\"stylesheet\" type=\"text/css\" href=\"estilos.css\">
            <script src=\"scripts.js\">
            <script src='http://code.jquery.com/jquery-1.11.2.js'></script>
            <title>$title</title>
        </head>
    <body>";
        User::session_start();
        echo $html;
    }

    public static function navigation(){
        echo '<nav class="fondo-gradiente-verde">';
        echo '<div class="nav-main">';
        //Primer item del contenedor nav-main (imagen) -> A la izquierda
        echo '<a class="nav-item nav-logo" href="index.php" "><img id="logo" src="logo.png" alt="Logo"></a>';
        //Todos los demás items (botones) -> A la derecha
        echo '<div class="nav-buttons">';
        if ($user = User::getLoggedUser()){
            echo "<div>Usuario: " . $user['nombre'] . "</div>";
            switch ($user['tipo']){
                case 1:
                    // Administrador
                    echo '<a class="nav-item" href="gestionespecialistas.php">Gestionar especialistas</a>';
                    break;
                case 2:
                    // Especialista
                    echo '<a class="nav-item" href="mispacientes.php">Mis pacientes</a>';
                    break;
                case 3:
                    // Auxiliar
                    echo '<a class="nav-item" href="anadirprueba.php">Añadir prueba</a>';
                    break;
                case 4:
                    // Paciente
                    echo '<a class="nav-item" href="mihistorial.php">Mi historial</a>';
                    echo '<a class="nav-item" href="misespecialistas.php">Mis especialistas</a>';
                    break;
            }
            echo"<a class='nav-item' href='logout.php'>Cerrar sesión</a>";
                
        } else {
            echo '<a class="nav-item" href="login.php">Iniciar sesión</a></li>';
        }
        echo '</div>
        </nav>';
    }
    
    public static function footer(){
        echo '<footer class="fondo-azul-oscuro centro">
		<div>
			Nos encontramos en la Calle Tomás Morales 23, Las Palmas de Gran Canaria.
		</div>';
		
		if (!($user = User::getLoggedUser())){
    		echo '<div>Por favor, inicie sesión para consultar sus datos.</div>';
		}
        echo '</footer>';
    }
    
    public static function end(){
        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
        echo '<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>';
        echo "<script src='scripts.js'></script>";
        echo '</body>
</html>';
    }
}
