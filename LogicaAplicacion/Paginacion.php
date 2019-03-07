<?php
class Paginacion
{
    private $botonera;//$pagina->$paginaActual $cantTemas->$totalItems $idSeccion->? "<li class="page-item"><a class=""page-link" href="/Seccion/irSeccion/.$nombreSeccion/$idSeccion/---->$href
    
    public function insertar($hrf,$pagina,$etiqBoton) {
         return $hrf.$pagina.'">'.$etiqBoton.'</a></li>';    
    }
    
    public function __construct($paginaActual,$totalItems,$href) {
        $itemPts='<li class="page-item"><a class="page-link" href="#" onclick="return false">...</a></li>';
        $hrefActive= str_replace('page-item','page-item active',$href);
        $paginacion=' <nav aria-label="paginacion"><ul class="pagination pagination-sm" id="botonesPaginacion">';
        //PRIMERO: SETEAR EL BOTON "ANTERIOR"        
        if($paginaActual==1){ // si se esta en la pagina "1" entonces el boton "anterior" no referencia a nadie
            $paginacion .= ' <li class="page-item"> <a class="page-link" href="#" onclick="return false" >anterior</a> </li>';
        }else{               // si se esta en otra pagina entonces el primer boton "anterior" referencia a "pagina-1" 
            $paginacion .= $this->insertar($href,($paginaActual-1),'anterior');
        }
        //SEGUNDO: COMPARO CANTIDAD TOTAL DE MENSAJES/TEMAS CON LA PAGINA 
        if(($paginaActual-1)*CANT_TEMAS<$totalItems){//------>la pagina pedida es coherente con la cantidad total de items
          //A- CANTIDAD DE ITEMS SON POCOS Y BASTA PARA CREAR LOS 7 BOTONES NUMERADOS 
            if (($totalItems-1)/CANT_TEMAS<CANT_BTN_PAGS) {
                for($i=1;$i<(intdiv($totalItems-1,CANT_TEMAS)+2);$i++){ //PREPARO LOS 7 (EN ESTE CASO) BOTONES CON NUMEROS (SIN ..)
                    if($i==$paginaActual){
                        $paginacion .='<li class="page-item active"><a class="page-link" href="#" onclick="return false">'.$i.'</a></li>';
                    }else{
                        $paginacion .= $this->insertar($href,$i,$i); 
                    }
                }
            }else{                                       
         //B- CANTIDAD DE ITEMS SON MUCHOS ENTONCES HAY QUE PONER EL BOTON ".."
                $mitad=intdiv(intdiv($totalItems-1,CANT_TEMAS),2);
                if($paginaActual==1){
                    for ($i = 1; $i < CANT_BTN_PAGS+1; $i++) {
                        if($i==1){
                            $paginacion .='<li class="page-item active"><a class="page-link" href="#" onclick="return false">'.$i.'</a></li>';
                        }elseif($i==2 || $i==CANT_BTN_PAGS-1){
                            $paginacion .=$itemPts;
                        }elseif($i==CANT_BTN_PAGS){
                            $paginacion .=$this->insertar($href,(intdiv($totalItems-1,CANT_TEMAS)+1),(intdiv($totalItems-1,CANT_TEMAS)+1));
                        }else{
                            $paginacion .=$this->insertar($href,$mitad,$mitad);
                            $mitad++;
                        }
                    }
                }elseif($paginaActual==intdiv($totalItems-1,CANT_TEMAS)+1){
                    for ($i = 1; $i < CANT_BTN_PAGS+1; $i++) {
                        if($i==1){
                            $paginacion .=$this->insertar($href,$i,$i);
                        }elseif($i==2 || $i==CANT_BTN_PAGS-1){
                            $paginacion .=$itemPts;
                        }elseif($i==CANT_BTN_PAGS){
                            $paginacion .=$this->insertar($hrefActive,(intdiv($totalItems-1,CANT_TEMAS)+1),(intdiv($totalItems-1,CANT_TEMAS)+1));
                        }else{
                            $paginacion .=$this->insertar($href,$mitad,$mitad);
                            $mitad++;
                        }
                    }
                }elseif($paginaActual==2){
                    for ($i = 1; $i < CANT_BTN_PAGS+1; $i++) {
                        if($i==2){
                            $paginacion .='<li class="page-item active"><a class="page-link" href="#" onclick="return false">'.$i.'</a></li>';
                        }elseif($i==CANT_BTN_PAGS-1){
                            $paginacion .=$itemPts;
                        }elseif($i==CANT_BTN_PAGS){
                            $paginacion .=$this->insertar($href,(intdiv($totalItems-1,CANT_TEMAS)+1),(intdiv($totalItems-1,CANT_TEMAS)+1));
                        }else{
                            $paginacion .=$this->insertar($href,$i,$i);
                        }
                    }
                }elseif($paginaActual==intdiv($totalItems-1,CANT_TEMAS)){
                    for ($i = 1; $i < CANT_BTN_PAGS+1; $i++) {
                        if($i==1){
                            $paginacion .=$this->insertar($href,$i,$i);
                        }elseif($i==CANT_BTN_PAGS-1){
                            $paginacion .='<li class="page-item active"><a class="page-link" href="#" onclick="return false">'.$paginaActual.'</a></li>';
                        }elseif($i==2){
                            $paginacion .=$itemPts;
                        }elseif($i==CANT_BTN_PAGS){
                            $paginacion .=$this->insertar($href,(intdiv($totalItems-1,CANT_TEMAS)+1),(intdiv($totalItems-1,CANT_TEMAS)+1));
                        }else{
                            $paginacion .=$this->insertar($href,(intdiv($totalItems-1,CANT_TEMAS)+1+($i-CANT_BTN_PAGS)),(intdiv($totalItems-1,CANT_TEMAS)+1+($i-CANT_BTN_PAGS)));
                        }
                    }
                }elseif($paginaActual==3){
                    for ($i = 1; $i < CANT_BTN_PAGS+1; $i++) {
                        if($i==3){
                            $paginacion .='<li class="page-item active"><a class="page-link" href="#" onclick="return false">'.$i.'</a></li>';
                        }elseif($i==CANT_BTN_PAGS-1){
                            $paginacion .=$itemPts;
                        }elseif($i==CANT_BTN_PAGS){
                            $paginacion .=$this->insertar($href,(intdiv($totalItems-1,CANT_TEMAS)+1),(intdiv($totalItems-1,CANT_TEMAS)+1));
                        }else{
                            $paginacion .=$this->insertar($href,$i,$i);
                        }
                    }
                }elseif($paginaActual==(intdiv($totalItems-1,CANT_TEMAS)-1)){
                    for ($i = 1; $i < CANT_BTN_PAGS+1; $i++) {
                        if($i==1){
                            $paginacion .=$this->insertar($href,$i,$i);
                        }elseif($i==CANT_BTN_PAGS-2){
                            $paginacion .='<li class="page-item active"><a class="page-link" href="#" onclick="return false">'.$paginaActual.'</a></li>';
                        }elseif($i==2){
                            $paginacion .=$itemPts;
                        }else{
                            $paginacion .=$this->insertar($href,(intdiv($totalItems-1,CANT_TEMAS)+1+($i-CANT_BTN_PAGS)),(intdiv($totalItems-1,CANT_TEMAS)+1+($i-CANT_BTN_PAGS)));
                        }
                    }
                }else{
                    for ($i = 1; $i < CANT_BTN_PAGS+1; $i++) {
                        if($i==1){
                            $paginacion .=$this->insertar($href,$i,$i);
                        }elseif($i==CANT_BTN_PAGS-1||$i==2){
                            $paginacion .=$itemPts;
                        }elseif($i==CANT_BTN_PAGS){
                            $paginacion .=$this->insertar($href,(intdiv($totalItems-1,CANT_TEMAS)+1),(intdiv($totalItems-1,CANT_TEMAS)+1));
                        }elseif($i==intdiv(CANT_BTN_PAGS,2)+1){ //EL BOTON DEL MEDIO
                            $paginacion .=$this->insertar($hrefActive,$paginaActual,$paginaActual);
                            $paginaActual =$paginaActual+2;
                        }else{
                            $paginacion .=$this->insertar($href,($paginaActual-1),($paginaActual-1));
                        }
                    }
                    $paginaActual = $paginaActual-2;
                }
           }
        }
        if($paginaActual==intdiv($totalItems-1,CANT_TEMAS)+1){
            $paginacion .= '<li class="page-item"><a class="page-link" href="#" onclick="return false">siguiente</a></li></ul></nav>';
        }else{
            $paginacion .=$this->insertar($href,($paginaActual+1),'siguiente').'</ul></nav>';
        }
        $this->botonera= $paginacion;
    }
    
    public function getBotonera(){
        return $this->botonera;
    }
}

?>