<?php
include_once 'presentation.class.php';
include_once 'data_access.class.php';
View::start('Clínica WebSalud');
View::navigation();
echo '<div class="contenedor-head fondo-gradiente-verde">
	    <h1 class="centro"> Una clínica que no te tendrá esperando </h1>
	</div>
	<div class="fondo-gris-claro">
		<h2 class="centro"> ¿Qué podemos ofrecerte?</h2>
		<div class="contenedor-datos1">
			<div class="dato1">
				<h3> Expertos </h3>
				Un gran cuadro profesional con sanitarios experimentados en todos los campos.
			</div>
			<div class="dato1">
				<h3> Diagnósticos </h3>
				Las más modernas técnicas de diagnóstico.
			</div>
			<div class="dato1">
				<h3> Tecnología </h3>
				Una gestión y seguimientos de tratamientos apoyados en las tecnologías de la información.
			</div>
		</div>
	</div>';
View::footer();
View::end();
