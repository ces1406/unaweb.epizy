<?php
require_once DIR_RAIZ.DIR_CONTROLADOR.'/ControladorPorDefecto.php';

class ControladorBusqueda extends ControladorPorDefecto
{
    public function __construct(){
        parent::__construct();
    }
    
    public function metodoConsulta() {
        $listaTemas=null;        
        $palabra=trim($_GET['palabra']);
        if(!isset($palabra)||empty($palabra)||strlen($palabra)>35||!is_string($palabra)||strlen($palabra)<3){
            return $this->msjAtencion('error en la palabra ingresada');
        }
        $temas=Modelo::buscarTemas($palabra);
        $listaTemas='<h2>Temas relacionados a "'.htmlentities($palabra).'" :</h2>';
        if(empty($temas)){
            $listaTemas.='<h3>No se encontraron resultados</h3>';
        }
        foreach ($temas as $tema){
            $listaTemas .= '<li class="nav-item"><img src="/Vistas/imagenes/item2.png" width="40" ';
            $listaTemas .= 'height="40"/><a href="/Seccion/IrTema/'.$tema["idTema"];
            $listaTemas .= '"> '.htmlentities($tema["titulo"]).'</a> </li>';
        }
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/sectorIzquierdo'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{colIzq}','12');
        $this->getVista()->modificarCuerpo('{panelIzq}',$listaTemas);
        $this->getVista()->modificarCuerpo('{tituloIzq}','Busqueda en el sitio');
        $this->getVista()->modificarCuerpo('{pieIzq}','resultado de la búsqueda');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/indexjs.js');
        return $this->getVista();
    }
}

