<?php
require_once DIR_RAIZ.DIR_APP.'/Modelo.php';
require_once DIR_RAIZ.DIR_VISTA.'/Vista.php';
require_once DIR_RAIZ.DIR_APP.'/Usuario.php';
    
class ControladorPorDefecto {

    private $vista;
    private $usuario;
    
    public function __construct(){
        session_start();
        (isset($_SESSION['idUsuario']))?
            $this->usuario = new Usuario($_SESSION['rol'],$_SESSION['idUsuario'],$_SESSION['apodo'],null,$_SESSION['img'],
                                         $_SESSION['mail'],$_SESSION['face'],$_SESSION['redSoc1'])
            :$this->usuario = new Usuario(ROL_INV,null,null,null,null,null,null,null);
        $this->vista=new Vista($this->getUsuario(),'Base','Base');
    }
    
    public function metodoPorDefecto(){
        $this->getVista()->armarHtml();
        $this->setearPanelDerecho();
        $this->setearPanelIzquierdo();
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/indexjs.js');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        return $this->getVista();
    }
    
    public function setearPanelDerecho() {
        $this->getVista()->modificarCuerpo('{sectorDerecha}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/sectorDerecho'));
        $this->getVista()->modificarCuerpo('{colDer}','5');
        $this->getVista()->modificarCuerpo('{tituloDer}','Ultimos comentarios');
        $this->getVista()->modificarCuerpo('{panelDer}','<div class="list-group" id="ultimosComentarios"></div>');
        $this->getVista()->modificarCuerpo('{pieDer}','actualizacion de ultimos comentarios');
    }
    
    public function setearPanelIzquierdo() {
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/sectorIzquierdo'));
        $listaSecciones=null;
        $secciones=Modelo::buscarSecciones();
        $listaSecciones = $this->getVista()->crearListaDeSecciones($secciones);
        $this->getVista()->modificarCuerpo('{colIzq}','7');
        $this->getVista()->modificarCuerpo('{panelIzq}','<ul class="navbar-nav " id="listaSecciones">{secciones}</ul>');
        $this->getVista()->modificarCuerpo('{secciones}',$listaSecciones);
        $this->getVista()->modificarCuerpo('{tituloIzq}','Secciones ');
        $this->getVista()->modificarCuerpo('{pieIzq}','secciones del sitio');
        $this->getVista()->modificarCuerpo('{listaSecciones}',$listaSecciones);
        
    }
    
    public function msjAtencion($msj){
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/sectorIzquierdo'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{colIzq}','12');
        $this->getVista()->modificarCuerpo('{tituloIzq}','Atencion');
        $this->getVista()->modificarCuerpo('{panelIzq}','<h2>'.$msj.'</h2>');
        $this->getVista()->modificarCuerpo('{pieIzq}','ocurrio un error');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/indexjs.js');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        return $this->getVista();
    }
    
    public function metodoFinSesion() {
        $this->cerrarSesion();
        return $this->metodoPorDefecto();
    }
    
    public function cerrarSesion() {
        session_unset();
        session_destroy();
        unset($this->vista);
        unset($this->usuario);
        $this->setUsuario( new Usuario(ROL_INV,null,null,null,null,null,null,null));
        $this->setVista(new Vista($this->getUsuario(),'Base','Base'));
    }
    
    public function getVista() {
        return $this->vista;
    }
    
    public function getUsuario () {
        return $this->usuario;
    }
    
    public function setVista($vista) {
        $this->vista = $vista;
    }
    
    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

}
?>