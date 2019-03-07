<?php
//namespace LogicaAplicacion;

class Tema{
    private $autor;
    private $titulo;
    private $idTema;
    private $comentarioInicial;
    private $fecha;
    private $palabra1;
    private $palabra2;
    private $palabra3;

    public function getIdTema(){
        return $this->idTema;
    }
    
    public function getTitulo(){
        return $this->titulo;
    }
    
    public function getAutor(){
        return $this->autor;
    }
    
    public function getComentarioInicial(){
        return $this->comentarioInicial;
    }
    
    public function getPalabra1(){
        return $this->palabra1;
    }
    
    public function getPalabra2(){
        return $this->palabra2;
    }
    
    public function getPalabra3(){
        return $this->palabra3;
    }
    
    public function getFecha(){
        return $this->fecha;
    }
    
    public function __construct($vecData){
        $this->autor = $vecData["idUsuario"];
        $this->idTema = $vecData["idTema"];
        $this->fecha = $vecData["fechaCreacion"];
        $this->comentarioInicial = $vecData["comentarioInicial"];
        $this->titulo = $vecData["titulo"];
        $this->palabra1 = $vecData["palabraClave1"];
        $this->palabra2 = $vecData["palabraClave2"];
        $this->palabra3 = $vecData["palabraClave3"];
        
    }
    public function crearTema($idSeccion){
        Modelo::crearTema($this->autor,$this->titulo,$this->comentarioInicial,$this->palabra1,$this->palabra2,$this->palabra3,$idSeccion);
    }
}

