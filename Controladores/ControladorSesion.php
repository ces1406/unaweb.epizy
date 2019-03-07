<?php
require DIR_RAIZ.DIR_CONTROLADOR.'/ControladorPorDefecto.php';

class ControladorSesion extends ControladorPorDefecto
{
    public function metodoIniciarSesion(){
        if($this->getUsuario()->tieneSesion()){
            return $this->msjAtencion('ya tienes sesion iniciada!');
        }
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/sectorIzquierdo'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{colIzq}','12');
        $this->getVista()->modificarCuerpo('{tituloIzq}','Iniciar Sesión en WebUNA');
        $this->getVista()->modificarCuerpo('{panelIzq}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/formularioInicioSesion'));
        $this->getVista()->modificarCuerpo('{pieIzq}','iniciar la sesion de usuario');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/sesion.js');
        return $this->getVista();
    }

    public function metodoReconocerSesion(){
        sleep(1);//dormir para evitar ataques de robot/fuerza bruta?-->Cantidad de intentos?
        $apodo=trim($_POST['unApodo']);
        $pass=trim($_POST['unaPassword']);
        if(!isset($apodo)||empty($apodo)||strlen($apodo)>TAM_APODO_MAX||!is_string($apodo)||strlen($apodo)<TAM_APODO_MIN){
            return $this->msjAtencion('error en el apodo ingresado');
        }
        if(!isset($pass)||empty($pass)||strlen($pass)>TAM_PASS_MAX||!is_string($pass)||strlen($pass)<TAM_PASS_MIN){
            return $this->msjAtencion('error en la contraseña ingresada');
        }
        $apodo=htmlentities($apodo,ENT_QUOTES);
        $this->getUsuario()->setApodo($apodo);
        $this->getUsuario()->setPass($pass);
        if($this->getUsuario()->iniciarSesion()){
            if($this->getUsuario()->getEstadoCuenta()=='HABILIT'){
                $this->darSesion();
            }else{
                return $this->msjAtencion('Tu cuenta ha sido temporalmente suspendida comunicate con un administrador (
                    envia un mail a '.MAIL_ADM.')');
            }
        }else{
            return $this->msjAtencion('El usuario no existe o la contraseña es incorrecta');
        }
        unset($this->vista);
        unset($this->usuario);
        $this->setUsuario( new Usuario($_SESSION['rol'],$_SESSION['idUsuario'],$_SESSION['apodo'],null,$_SESSION['img'],$_SESSION['mail'],$_SESSION['face'],$_SESSION['redSoc1']));
        $this->setVista(new Vista($this->getUsuario(),'Base','Base'));
        return $this->metodoPorDefecto(); 
    }
    
    public function darSesion() {
        $_SESSION['idUsuario']=$this->getUsuario()->getIdUsuario();
        $_SESSION['apodo']=$this->getUsuario()->getApodo();
        $_SESSION['tiempoInicio']=time();
        $_SESSION['img']=$this->getUsuario()->getImg();
        $_SESSION['mail']=$this->getUsuario()->getMail();
        $_SESSION['rol']=$this->getUsuario()->getRol();
        $_SESSION['face']=$this->getUsuario()->getRedSoc1();
        $_SESSION['redSoc1']=$this->getUsuario()->getRedSoc2();
    }

    public function __construct(){
        parent::__construct();
    }
   
}

?>