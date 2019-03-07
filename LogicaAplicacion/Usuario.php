<?php
require_once DIR_RAIZ.DIR_APP.'/Modelo.php' ;

class Usuario{

    private $rol;
    private $estadoCuenta;
    private $idUsuario;
    private $apodo;
    private $password;
    private $imgAvatar;
    private $mail;
    private $redSocial1;
    private $redSocial2;
    private $redSocial3;
    private $redSocial4;
    
    public function __construct($rol,$idUsuario,$apodo,$pass,$img,$mail,$face,$redSoc1){
        $this->apodo=$apodo;
        $this->idUsuario=$idUsuario;
        $this->rol=$rol;
        $this->password = $pass;
        $this->imgAvatar=$img;
        $this->mail=$mail;
        $this->redSocial1=$face;
        $this->redSocial2=$redSoc1;
    }
    
    public function iniciarSesion() {
        $vecUser=Modelo::buscarUsuarioPorApodo($this->apodo);
        if($vecUser==NULL || empty($vecUser)){
            return FALSE;
        }
        if( password_verify($this->password,$vecUser['contrasenia']) ){
            $this->imgAvatar=$vecUser['dirImg'];
            $this->rol=$vecUser['rol'];
            $this->mail=$vecUser['mail'];
            $this->idUsuario=$vecUser['idUsuario'];
            $this->redSocial1=$vecUser['redSocial1'];
            $this->redSocial2=$vecUser['redSocial2'];
            $this->redSocial3=$vecUser['redSocial3'];
            $this->redSocial4=$vecUser['redSocial4'];
            $this->estadoCuenta=$vecUser['estadoCuenta'];
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    public function tieneSesion(){
        if ($this->getRol()==ROL_INV) {
            return false;
        }else{
            return true;
        }
    }
    
    public function esAdmin(){
        if($this->getRol()==ROL_ADMIN){
            return true;
        }else{
            return false;
        }
    }
        
    public function passOk($pass){
        $vecUser=Modelo::buscarUsuarioPorApodo($this->apodo);
        if($vecUser==NULL || empty($vecUser)){
            return FALSE;
        }
        if( password_verify($pass,$vecUser['contrasenia']) ){
            if($vecUser['estadoCuenta']=='HABILIT'){
                return TRUE;
            }
        }
        return FALSE;
    }
    
    public function getImg() {
        return $this->imgAvatar;
    }
    
    public function  getRol(){
        return $this->rol;
    }
    
    public function getApodo() {
        return $this->apodo;
    }
    
    public function getIdUsuario() {
        return $this->idUsuario;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function getMail() {
        return $this->mail;
    }
    public function getRedSoc1() {
        return $this->redSocial1;
    }
    public function getRedSoc2() {
        return $this->redSocial2;
    }
    
    public function setIdUsuario($id) {
        $this->idUsuario = $id;
    }
    
    public function setRol($param) {
        $this->rol = $param;
    }
    
    public function setApodo($param){
        $this->apodo=$param;
    }
    
    public function setPass($param) {
        $this->password=$param;
    }
    
    public function setMail($param) {
        $this->mail=$param;
    }
    public function getEstadoCuenta(){
        return $this->estadoCuenta;
    }
}
?>
