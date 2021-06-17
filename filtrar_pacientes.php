<?php
include_once 'data_access.class.php';
$res = new stdClass();
$res->datos=[]; //Formato objeto con propiedad deleted (por defecto a false)
$res->message=''; //Mensaje en caso de error
try{
    $datoscrudos = file_get_contents("php://input"); //Leemos los datos
    $datos = json_decode($datoscrudos);
    $res -> datos = DB::getMisPacientes($datos -> userid, true, $datos -> nombre);
    
}catch(Exception $e){
   //En caso de error se envia la información de error al navegador
   $res->message="Se ha producido una excepción en el servidor: ".$e->getMessage(); 
}
header('Content-type: application/json');
echo json_encode($res);
?>