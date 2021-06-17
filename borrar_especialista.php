<?php
include_once "data_access.class.php";

$res = new stdClass();
$res->deleted=false; //Formato objeto con propiedad deleted (por defecto a false)
$res->message=''; //Mensaje en caso de error
try{
    $datoscrudos = file_get_contents("php://input"); //Leemos los datos
    $datos = json_decode($datoscrudos);
    if(DB::eliminarEspecialistaFromPaciente($datos -> idesp, $datos -> idpac)){
        $res->deleted=true; //Devolvemos que ha sido borrado
    } else {
        $res->message='No se ha encontrado el registro a borrar';
    }
    
}catch(Exception $e){
   //En caso de error se envia la información de error al navegador
   $res->message="Se ha producido una excepción en el servidor: ".$e->getMessage(); 
}
header('Content-type: application/json');
echo json_encode($res);
?>