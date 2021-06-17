<?php
class DB{
    private static $connection=null;
    public static function get(){
        if(self::$connection === null){
            self::$connection = new PDO('sqlite:' . __DIR__ . '/datos.db');
            self::$connection->exec('PRAGMA foreign_keys = ON;');
            self::$connection->exec('PRAGMA encoding="UTF-8";');
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$connection;
    }
    public static function execute_sql($sql,$parms=null){
        try {
            $db = self::get();
            $ints= $db->prepare ( $sql );
            if ($ints->execute($parms)) {
                return $ints;
            }
        }
        catch (PDOException $e) {
            // ¡Esto debería estar en presentation !
            echo '<h3>Error en al DB: ' . $e->getMessage() . '</h3>';
        }
        return false;
    }
    public static function setFetchNamed($inst){
        $inst->setFetchMode(PDO::FETCH_NAMED);
        $res=$inst->fetchAll();
        return $res;
    }
    public static function user_exists($usuario,$pass, &$res){
        $param = [$usuario, md5($pass)];
        $query = 'SELECT * FROM usuarios WHERE cuenta=? and clave=?';
        $inst = self::execute_sql($query, $param);
        $res=self::setFetchNamed($inst);
        return count($res) == 1;
    }
    
    // Obtiene todas las especialidades
    public static function getEspecialidades($especialidades) {
        $query = 'SELECT DISTINCT especialidad FROM especialistas';
        $inst = self::execute_sql($query);
        $especialidades = self::setFetchNamed($inst);
        $aux = array();
        foreach($especialidades as $esp) {
            array_push($aux, $esp['especialidad']);
        }
        return $aux;
    }
    
    public static function getAllEspecialists(){
        $query="select * from usuarios where tipo='2';";
        $inst=self::execute_sql($query);
        return self::setFetchNamed($inst);
    }
    
    // Buscar especialistas del paciente
    public static function getEspecialists($userID) {
        $param = [$userID];
        $query = 'SELECT idespecialista FROM pacientesespecialistas WHERE idpaciente=?';
        $inst = self::execute_sql($query, $param);
        $ids = self::setFetchNamed($inst);
        return $ids;
    }
    
    // Busca especialista por ID
    public static function findEspecialistaByID($especialistID) {
        $param = [$especialistID];
        $query = 'SELECT * FROM especialistas 
        JOIN usuarios ON especialistas.idespecialista = usuarios.id 
        WHERE idespecialista=?';
        $inst = self::execute_sql($query, $param);
        $res=self::setFetchNamed($inst);
        return $res;
    }
    
    // Busca especialista por nombre
    public static function findEspecialistaByAccount($account) {
        $param = [$account];
        $query = 'SELECT * FROM usuarios
        WHERE cuenta=? AND
        tipo=2';
        $inst = self::execute_sql($query, $param);
        $res=self::setFetchNamed($inst);
        return $res;
    }
    
    // Obtiene los especialista de cada especialidad
    public static function findEspecialistaByEsp($especialidad) {
        $query = 'SELECT usuarios.id, usuarios.nombre FROM especialistas JOIN usuarios ON
        especialistas.idespecialista = usuarios.id 
        WHERE especialidad=?';
        $inst = self::execute_sql($query, [$especialidad]);
        $res=self::setFetchNamed($inst);
        return $res;
    }
    
    // Busca el paciente por cuenta
    public static function findPacienteByAccount($account) {
        $param = [$account];
        $query = 'SELECT * FROM usuarios
        WHERE cuenta=? AND
        tipo=4';
        $inst = self::execute_sql($query, $param);
        $res=self::setFetchNamed($inst);
        return $res;
    }

    // Cambia el especialista de un usuario
    public static function changeEspecialista($userID, $oldID, $newID) {
        $param = [$newID, $oldID, $userID];
        $query = 'UPDATE pacientesespecialistas SET idespecialista=? 
        WHERE idespecialista=? AND idpaciente=?';
        $inst = self::execute_sql($query, $param);
        return $inst;
    }
    
    // Añade un nuevo especialista
    public static function addEspecialista($userID, $newID) {
        $param = [htmlentities($userID), htmlentities($newID)];
        $query = 'INSERT INTO pacientesespecialistas (idpaciente, idespecialista)
        VALUES (?, ?)';
        $inst = self::execute_sql($query, $param);
        return $inst;
    }
    
    // Elimina un especialista
    public static function eliminarEspecialista($id) {
        $query = "DELETE FROM pacientesespecialistas WHERE idespecialista=?";
        $inst = self::execute_sql($query, array($id));
        return $inst;
    }
    
    // Elimina una asginación de un especialista
    public static function eliminarEspecialistaFromPaciente($idesp,$idpac) {
        $query = "DELETE FROM pacientesespecialistas WHERE idespecialista=:idesp AND idpaciente=:idpac";
        $inst = self::execute_sql($query, array($idesp,$idpac));
        return $inst;
    }
    
    // Obtiene los especialistas que un paciente no tiene asignados.
    public static function getEspecialistasNoAsignados($userID){
        $qry="SELECT usuarios.*
        from usuarios
        where tipo='2' and id not in(
            SELECT usuarios.id
            FROM pacientesespecialistas JOIN usuarios ON pacientesespecialistas.idespecialista = usuarios.id
            WHERE pacientesespecialistas.idpaciente=:userID
        );";
        $inst = self::execute_sql($qry,array($userID));
        $res=self::setFetchNamed($inst);
        return $res;
    }
    // Obtiene la (o las) especialidad de un especialista.
    public static function getEspecialidadesFromEspecialist($userID){
        $qry="SELECT especialidad FROM especialistas WHERE idespecialista=:userID;";
        $inst = self::execute_sql($qry,array($userID));
        return self::setFetchNamed($inst);
    }
    
    public static function isEspecialidadAssignedToEspecialist($userID,$especialidad){
        $qry="SELECT especialidad FROM especialistas WHERE idespecialista=:userID AND especialidad=:especialidad;";
        $inst = self::execute_sql($qry,array($userID,$especialidad));
        $res = self::setFetchNamed($inst);
        return count($res) >= 1;
    }
    // Obtiene especialistas no asignados por una especialidad.
    public static function getEspecialistasNoAsignadosByEsp($userID, $especialidad){
        $qry="SELECT usuarios.id,usuarios.nombre,especialistas.especialidad
        FROM usuarios 
        JOIN especialistas
        ON usuarios.id = especialistas.idespecialista
        WHERE tipo='2' 
        AND especialidad=:especialidad 
        AND usuarios.id NOT IN(
            SELECT usuarios.id
            FROM pacientesespecialistas JOIN usuarios ON pacientesespecialistas.idespecialista = usuarios.id
            WHERE pacientesespecialistas.idpaciente=:userID
        );";
        $inst = self::execute_sql($qry,array($especialidad,$userID));
        $res=self::setFetchNamed($inst);
        return $res;
    }
    
    // Añade un registro al historial
    public static function addToHistorialPaciente($registro) {
        $query = 'INSERT INTO historial (idpaciente, fechahora, idcreador, tipo, asunto, descripcion) 
            VALUES(:idpaciente, :fechahora, :idcreador, :tipo, :asunto, :descripcion)';
        $inst = self::execute_sql($query, $registro);
        return $inst;
    }

    public static function addEspecialidad($idEspecialista,$newEspecialidad){
        $query="INSERT INTO especialistas(idespecialista,especialidad)
                VALUES (:userId,:newEspecialidad);";
        return self::execute_sql($query,array(htmlentities($idEspecialista),htmlentities($newEspecialidad)));
    }
    
    public static function deleteEspecialidad($idespecialista,$especialidad){
        $query = "DELETE FROM especialistas
                WHERE idespecialista=:idespecialista AND especialidad=:especialidad;";
        return self::execute_sql($query,array(htmlentities($idespecialista),htmlentities($especialidad)));
    }
    
    public static function getPacientes(){
        $query="SELECT nombre,id
            FROM usuarios WHERE tipo=4;";
        $inst = self::execute_sql($query);
        return self::setFetchNamed($inst);
        
    }
    
    public static function addPruebaToHistorial($auxID,$time,$id,$asunto,$descripcion){
        $query="INSERT INTO historial (idpaciente, fechahora, idcreador, tipo, asunto, descripcion)
            VALUES (:id,:time,:auxID,:tipo,:asunto,:descripcion);";
        if ($pdostate = self::execute_sql($query,array(htmlentities($id),htmlentities($time),htmlentities($auxID),'5',htmlentities($asunto),htmlentities($descripcion)))){
            return $pdostate -> rowCount() == 1;
        } else {
            return false;
        }
    }
    
    public static function getHistorialFromPaciente($idPaciente){
        $query="SELECT historial.*
            FROM historial JOIN usuarios ON historial.idpaciente = usuarios.id
            WHERE historial.idpaciente=:idPaciente;";
        $inst = self::execute_sql($query,array($idPaciente));
        return self::setFetchNamed($inst);
        
    }
    
    
    public static function getHistorialOrderedFromPaciente($idPaciente){
        $query="SELECT historial.*
        FROM historial JOIN usuarios ON historial.idpaciente = usuarios.id
        WHERE historial.idpaciente=?
        ORDER BY historial.fechahora DESC;";
        $inst=self::execute_sql($query,array($idPaciente));
        return self::setFetchNamed($inst);
    }
    
    public static function getMisPacientes($idEspecialista,$flagOrdered = false, $nombre = null){
        if ($flagOrdered){
            $query="SELECT usuarios.*
            FROM pacientesespecialistas JOIN usuarios ON pacientesespecialistas.idpaciente = usuarios.id
            WHERE pacientesespecialistas.idespecialista=:idEspecialista AND nombre like :nombre";
            $inst = self::execute_sql($query,array($idEspecialista,$nombre.'%'));
        } else {
            $query="SELECT usuarios.*
            FROM pacientesespecialistas JOIN usuarios ON pacientesespecialistas.idpaciente = usuarios.id
            WHERE pacientesespecialistas.idespecialista=:idEspecialista";
            $inst = self::execute_sql($query,array($idEspecialista));
        }
        
        return self::setFetchNamed($inst);
    }
    
    public static function updateEspecialidadToEspecialista($idEspecialista,$newEspecialidad,$especialidadAntigua){
        $update="UPDATE especialistas
            SET especialidad=:newEspecialidad
            WHERE idespecialista=:idEspecialista
            AND especialidad=:especialidadAntigua;";
        
        $inst = self::execute_sql($update,array($newEspecialidad,$idEspecialista,$especialidadAntigua));
        return $inst -> rowCount() == 1;
    }
    
    public static function getEspecialidadesNoAsignadas($pacientID){
        $query="SELECT DISTINCT especialidad
        FROM especialistas 
        WHERE idespecialista not in (
            SELECT idespecialista FROM pacientesespecialistas
            WHERE idpaciente = :pacientID)";
        $inst = self::execute_sql($query,array($pacientID));
        return DB::setFetchNamed($inst);
    }
}
