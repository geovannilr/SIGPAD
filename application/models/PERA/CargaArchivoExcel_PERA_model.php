<?php
class CargaArchivoExcel_PERA_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function InsertarLineaPERA($datos){
        $due = $datos['id_due'];
        $nombre = $datos['nombre'];
        $apellido = $datos['apellido'];        
        $cum = $datos['cum'];
        $materia = $datos['materia'];
        $nota = $datos['nota'];
        $email = $datos['email'];
        $query = $this->db->query("CALL Crear_Linea_PERA('".$due."','".$nombre."','".$apellido."','".$cum."','".$materia."','".$nota."','".$email."');");
        
        mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys
        
        return $query->row_array();
    }
}
  ?>