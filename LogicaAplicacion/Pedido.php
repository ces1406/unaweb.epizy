<?php
//namespace LogicaAplicacion;

class Pedido{

    private $nombreControlador;
    private $metodo;
    private $argumentos=array();
    
    public function getMetodo(){
        return $this->metodo;
    }
    
    public function setMetodo($metodo) {
        $this->metodo=$metodo;
    }
    
    public function getArgumentos(){
        return $this->argumentos;
    }
    
    public function getNombreControlador(){
        return $this->nombreControlador;
    }
    
    public function __construct(){
        $url = filter_input(INPUT_GET,'args',FILTER_SANITIZE_URL);
        $url = explode('/',$url);
        $this->argumentos = array_slice($url,2);
        
        (!isset($url[0])|| ($url[0] == "") || ($url[0] == null) )? $this->nombreControlador='ControladorPorDefecto'
            :$this->nombreControlador ='Controlador'.$url[0];
        ( !isset($url[1])|| ($url[1] == null)|| (empty($url[1])))? $this->metodo="metodoPorDefecto"
            :$this->metodo = 'metodo'.$url[1];
    }
    
    public function existeControlador() {
        return (is_readable(DIR_RAIZ.DIR_CONTROLADOR.'/'.$this->getNombreControlador().'.php'));        
    }
    
    public function existeMetodo(){
        return (is_callable(array($this->getNombreControlador(),$this->getMetodo())));
    }
}
?>