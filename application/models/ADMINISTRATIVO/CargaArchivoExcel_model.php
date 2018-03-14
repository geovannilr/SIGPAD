<?php
class CargaArchivoExcel_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function InsertarEstudiantePSS($datos){
        $due = $datos['id_due'];
        $nombre = $datos['nombre'];
        $apellido = $datos['apellido'];        
        $email = $datos['email'];        
        $query = $this->db->query("CALL Crear_Estudiante_PSS('".$due."','".$nombre."','".$apellido."','".$email."');");
        
        mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys
        
        return $query->row_array();
    }

    function InsertarEstudiantePDG($datos){
        $due = $datos['id_due'];
        $nombre = $datos['nombre'];
        $apellido = $datos['apellido'];        
        $email = $datos['email'];        
        $query = $this->db->query("CALL Crear_Estudiante_PDG('".$due."','".$nombre."','".$apellido."','".$email."');");
        
        mysqli_next_result( $this->db->conn_id ); //prepara para multiples querys
        
        return $query->row_array();
    }
}
  ?>