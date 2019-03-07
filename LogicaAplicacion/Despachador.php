<?php

class Despachador{
    
    private $pedido;
    private $controlador;
    
    public function getControlador() {
        return $this->controlador;
    }
    
    public function __construct($pedido){
        $this->pedido = $pedido;
        if($this->pedido->existeControlador()){
            require_once DIR_RAIZ.DIR_CONTROLADOR.'/'.$this->pedido->getNombreControlador().'.php';
            $controladorAInstanciar = $this->pedido->getNombreControlador();
            $this->controlador = new $controladorAInstanciar();
            if(!($this->pedido->existeMetodo())){
                $this->pedido->setMetodo('metodoPorDefecto');
                require_once DIR_RAIZ.DIR_CONTROLADOR.'/ControladorPorDefecto.php';
                $this->controlador = new ControladorPorDefecto();
            }
        }else{
            require_once DIR_RAIZ.DIR_CONTROLADOR.'/ControladorPorDefecto.php';
            $this->controlador = new ControladorPorDefecto();
            $this->pedido->setMetodo('metodoPorDefecto');
        }
    }
    
    public function responder() {
        return call_user_func_array(array($this->getControlador(),$this->pedido->getMetodo()), $this->pedido->getArgumentos());       
    }
}

?>
