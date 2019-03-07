<?php
include_once DIR_RAIZ.DIR_APP.'/Tema.php';

class Seccion
{
    private $nombre;
    private $id;
    private $temas=array();

    public function getNombre() {
        return $this->nombre;
    }
    public function getId() {
        return $this->id;
    }
    public function getTemas() {
        return $this->temas;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function setTemas($temas) {
        $this->temas = $temas;
    }
    
    public function cantTemas() {
        return count($this->temas);
    }

    public function autoCompletarse() {
        $tema=null;
        $temas = Modelo::buscarTemasSeccion($this->getId());
        if(!empty($temas)){
            foreach ($temas as $tema){
                array_unshift($this->temas,new Tema($tema));
                //$this->temas[]=new Tema($tema);
            }
        }
    }
}

?>