<?php
//namespace Vistas;
class Vista{
    
    private $cabecera;
    private $cuerpo;
    private $pie;
    private $pagina;
    
    public function setCuerpo($cuerpo){
        $this->cuerpo = DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cuerpo'.$cuerpo;
    }
    
    public function __construct($usuario,$cuerpo,$pie){
        if($usuario->getRol() == ROL_INV){
            $this->cabecera = DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cabeceraSinUsuario';
        }else {
            $this->cabecera = DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cabeceraConUsuario';            
        }
        $this->cuerpo = DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cuerpo'.$cuerpo;
        $this->pie = DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/pie'.$pie;
    }
    
    public function armarHtml() {
        $this->pagina = file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/plantilla.php');
        $this->pagina = str_replace('{cabecera}',file_get_contents($this->cabecera), $this->pagina);
        $this->pagina = str_replace('{cuerpo}', file_get_contents($this->cuerpo), $this->pagina);
        $this->pagina = str_replace('{pie}', file_get_contents($this->pie), $this->pagina);
    }

    public function crearListaDeSecciones($vecSecciones){
        foreach ($vecSecciones as $seccion){
            if($seccion['nombreSeccion']=='Apuntes'){
                $metodo= 'IrAApuntes/default/0';
                $listaSecciones .= '<li class="nav-item li0 enlace"><img src="/Vistas/imagenes/item7.png" width="40" ';
                $listaSecciones .= 'height="40"/><a href="/Seccion/'.$metodo.'/'.$seccion["nombreSeccion"];
                $listaSecciones .= '/'.$seccion["idSeccion"].'/1">'. $seccion["nombreSeccion"].'<br/><h6 class="h6">';
                $listaSecciones .= $seccion['descripcion'].'</h6></a></li>';
            }elseif($seccion['nombreSeccion']=='Cursos por cátedras'){
                $metodo= 'CursosCatedras/default';
                $listaSecciones .= '<li class="nav-item li0 enlace"><img src="/Vistas/imagenes/item7.png" width="40" ';
                $listaSecciones .= 'height="40"/><a href="/Seccion/'.$metodo.'">'. $seccion["nombreSeccion"].'<br/><h6 class="h6">';
                $listaSecciones .= $seccion['descripcion'].'</h6></a></li>';
            }elseif($seccion['nombreSeccion']=='Opiniones de cátedras y profesores'){
                $metodo= 'IrOpiniones/default';
                $listaSecciones .= '<li class="nav-item li0 enlace"><img src="/Vistas/imagenes/item7.png" width="40" ';
                $listaSecciones .= 'height="40"/><a href="/Seccion/'.$metodo;
                $listaSecciones .= '/1">'. $seccion["nombreSeccion"].'<br/><h6 class="h6">';
                $listaSecciones .= $seccion['descripcion'].'</h6></a></li>';
            }else{
                $metodo='IrSeccion';
                $listaSecciones .= '<li class="nav-item li0 enlace"><img src="/Vistas/imagenes/item7.png" width="40" ';
                $listaSecciones .= 'height="40"/><a href="/Seccion/'.$metodo.'/'.$seccion["nombreSeccion"];
                $listaSecciones .= '/'.$seccion["idSeccion"].'/1">'. $seccion["nombreSeccion"].'<br/><h6 class="h6">';
                $listaSecciones .= $seccion['descripcion'].'</h6></a></li>';
            }
        }
        return $listaSecciones;
    }

    public function crearAreaComentaje($dirImg){
        return '
            <div class="media">
                <div class="media-left">
                    <img src="/Vistas/imagenesUsers/'.$dirImg.'" class="mr-3 icono2 media-object" >
                </div>
                <div class="media-body">
                    <form class="form-horizontal" id="crearComentario" {action} method="POST">
				        <div class="form-group row" id="formularioRegistro">
					        <div id="comentarioActual">
							    <textarea name="unComentario" id="area1"></textarea>
						    </div>
					    </div>
					    <button class="btn btn-secondary btn-sm enlace" type="submit" id="CrearComentario">comentar</button>
                        <h5 id="aviso"></h5>
                    </form>
                </div>
            </div>
            
            <img src="/Vistas/imagenes/separador.png" class="separador">';
    }
    public function crearTituloTemaCurso($mate,$catedra,$sede,$hora,$cod,$dias){
        return '<h2>Materia: '.$mate.'<br/>cátedra: '.$catedra.'<br/>sede: '.$sede.'<br/>horario: '
                .$hora.' dias: &nbsp;'.$dias.'<br/>curso: '.$cod.'</h2>';
    }

    public function crearLiTema($idSeccion,$idTema,$titulo,$fechaCreacion){
        $fec = $this->normalizarDate($fechaCreacion);
        return '<li class="nav-item li0 enlace "><img src="/Vistas/imagenes/item7.png" width="40" height="40"/>
                <a href="/Seccion/IrTema/'.$idSeccion.'/'.$idTema.'/1" >'.htmlentities($titulo).'</br>
                <h7 style="font-size: 1.0ex !important;">Creado el&nbsp; '.$fec['dia'].'/'.$fec['mes'].'/'.$fec['anio'].' a las '.$fec['hora'].':'.$fec['minutos'].'</h7></a></li>';
    }

    public function crearBotonIniciarTema(){
        return '<a  href="/Seccion/IniciarTema/{idSeccion}/{nombreSeccion}" class="btn btn-secondary enlace" 
                id="botonIniciar">Iniciar un tema</a>';
    }

    public function renderizar(){
        eval('?>'.$this->pagina);
    }

    public function modificarCuerpo($clave,$valor) {
        $this->pagina = str_replace($clave,$valor, $this->pagina);
    }

    public function listaCursosBuscados($vecCursos){
        if(!empty($vecCursos)){
            $busqueda .='<ul class="navbar-nav " id="listaCursos"><h3>Resultados</h3>';
            foreach ($vecCursos as $curso) {
                $busqueda .='<a href="/Seccion/Curso/'.$curso['idCurso'].'/1" class="enlace">
                <div class="d-flex w-100 justify-content-between"><h5 class="mb-1">Materia: '.$curso['nombreMateria'].'</h5><small>Horario: '
                .$curso['horario'].' &nbsp;dias:&nbsp;'.$curso[dias].'</small></div><p class="mb-1">Catedra: '.$curso['nombreCatedra'].'<br/>Sede: '.$curso['sede']
                .'<br/>Código de curso:<small> '.$curso['codigo'].'</small></p> </a>
                <img class="card-img-top " src="/Vistas/imagenes/item12.png" height="10" alt="Card image cap">';
            }
            $busqueda .= '</ul> <a class="btn btn-sm enlace" href="/Seccion/CursosCatedras/default">Limpiar busqueda</a>';
        }else{
            $busqueda .='<h3>No se encontraron resultados para '.$materia.'&#8226;'.$catedra.'&#8226;'.$codigo.'&#8226;'.$horario.'&#8226;'.$sede.'</h3>
            <a class="btn btn-sm enlace" href="/Seccion/CursosCatedras/default">Limpiar busqueda</a>';
        }
        return $busqueda;
    }
    public function crearListaOpinionesDeCurso($lista,$materia,$catedra){
        $msj = 'Ya existen foros de opiniones sobre<br/>Materia: '.$materia.'&nbsp;Catedra: '.$catedra;
        $msj .= '<br/><p>fijarse si los nombres de catedras son muy similares</p>';
        $msj .='<img class="card-img-top " src="/Vistas/imagenes/item12.png" height="10" alt="Card image cap">
                <div id="" class="list-group">';
        $msj .= $this->listaDeOpinionesCurso($lista);
        $msj .= '</div>';
        return $msj;
    }
    public function crearListaOpinionesDeCurso2($lista,$materia,$catedra){
        if(!empty($lista)){
            $listaHtml ='<ul class="navbar-nav " id="listaOpiniones"><h3>Resultados:</h3>';
            $listaHtml .= $this->listaDeOpinionesCurso($lista);
            $listaHtml .= '</ul><a class="btn btn-sm enlace" href="/Seccion/irOpiniones/default/1">Limpiar busqueda</a>';
        }else{
            $listaHtml ='<h3>No se encontraron resultados para '.$materia.'&#8226;'.$catedra.'&#8226;'.$profesor.'</h3>
                        <a class="btn btn-sm enlace" href="/Seccion/irOpiniones/default/1">Limpiar busqueda</a>';
        }
        return $listaHtml;
    }
    public function crearListaOpinionesDeCurso3($listaOp){
        if(!empty($listaOp)){
            $opiniones='<div id="ultimosComentarios" class="list-group">';
            foreach ($listaOp as $opinion) {
                $opiniones .= ' <a  class="enlace" href="/Seccion/irHiloOpinion/'.$opinion['idCatedra'].'/1 ">
                                <h5 class="esquinaIzq2">Materia:&nbsp '.$opinion['materia'].'</h5>
                                <div class="contenido2">
                                    catedra: '.$opinion['catedra'].'<br/>
                                    profesores: '.$opinion['profesores'].'<br/>
                                    <div class="px-2">comentario: '.$opinion['contenido'].'</div>
                                </div>
                                </a>
                                <img class="card-img-top separador" src="/Vistas/imagenes/item12.png" height="35" alt="Card image cap">'; 
            }
            $opiniones .= '</div>';
        }else{
            $opiniones='';
        }
        return $opiniones;
    }
    public function listaDeOpinionesCurso($lista){
        foreach ($lista as $opinion) {
            $msj .='<a href="/Seccion/IrHiloOpinion/'.$opinion['idCatedra'].'/1" class="enlace">
                    <div class="d-flex w-100 justify-content-between"><h4 class="mb-1">Materia: '.$opinion['materia'].'</h4></div><p class="mb-1">
                    Catedra: '.$opinion['catedra'].'<br/>Profesores: '.$opinion['profesores'].'</p> </a>
                    <img class="card-img-top " src="/Vistas/imagenes/item12.png" height="10" alt="Card image cap">';
        }
        return $msj;
    }
    public function agregarHoraYMins(){
        $hor=null;
        $min=null;
        for ($i=7; $i <24 ; $i++) { $hor.='<option>'.str_pad((int)$i,2,"0",STR_PAD_LEFT).'</option>';}
        for ($i=0; $i <12 ; $i++) { $min .='<option>'.str_pad((int)($i*5),2,"0",STR_PAD_LEFT).'</option>';}
        $this->modificarCuerpo('{hora}',$hor);
        $this->modificarCuerpo('{minuto}',$min);   
    }

    public function crearMenuBorrarApunte($idApunte){
        return '<div class="badge badge-primary text-wrap" style="background-color: rgba(21, 24, 29, 0.9);">
                    <form class="form-inline formuBorrar" id="" action="" method="POST" enctype="multipart/form-data">
                        <button type="submit" id="BorrarCurso" value="Borrar" class="btn btn-sm enlace" style="font-size: 1.6ex;">eliminar apunte</button>
                        <input type="hidden" id="idDeApunte" name="idApunte" value="'.$idApunte.'" >
                    </form>
                </div>';
    }
    public function crearListaApuntesBuscados($apunte,$borrado){
        return '<div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">Titulo: '.$apunte['titulo'].'</h5>
                    <small>subido el '.$apunte['fechaSubida']."&nbsp; (id:.".$apunte['idApunte'].')<br/>por:'.$apunte['apodo'].'</small>
                </div>
                <p class="mb-1">Autor/es: '.$apunte['autores'].'<br/>Materia: '.$apunte['materia'].' <br/>Catedra:&nbsp;'.$apunte['catedra'].'
                <br/>Dirección para descargarlo:<small> '.$apunte['dirurl'].'</small></p>
                <div class="col-mb-1" id="">
                    <a class="btn btn-sm enlace " href="'.$apunte['dirurl'].'" >ver el apunte</a>
                                      '.$borrado.'
	             </div><img src="/Vistas/imagenes/separador.png" class="separador"><br/>';
    }

    public function crearMenuBorrarMsj($idComentario,$idTema,$pagina,$idSec){
        return '<div class="badge badge-primary text-wrap float-right" style="background-color: rgba(21, 24, 29, 0.9);">
                    <form class="form-inline formuBorrar" id="" action="" method="POST" enctype="multipart/form-data">
                        <button type="submit" id="BorrarCurso" value="Borrar" class="btn btn-sm enlace" style="font-size: 1.6ex;">borrar comentario</button>
                        <input type="hidden" id="idDeComent" name="idComentario" value="'.$idComentario.'">
                        <input type="hidden" id="idDeTema" name="idTema" value="'.$idTema.'">
                        <input type="hidden" id="idDePag" name="pagina" value="'.$pagina.'">
                        <input type="hidden" id="idDeSec" name="seccion" value="'.$idSec.'">
                    </form>
                </div>'; 
    }

    public function crearListaComentarios($apodo,$dirImg,$face,$redSoc2,$vecFecha,$contenido,$borrado){
        $fecha = $this->normalizarDate($vecFecha);
        return '<div class="media">
                    <div class="media-left">
                        <h3 class="media-heading">'.htmlentities($apodo).' </h3>
                        <img src="/Vistas/imagenesUsers/'.$dirImg.'" class="icono2 media-object" >
                            '.$face.$redSoc2.'
                    </div>
                    <div class="media-body "><h6 class="text-right"> '.$fecha[dia].'/'.$fecha[mes].'/'.$fecha[anio].'<br/>'.$fecha[hora].':'.$fecha[minutos].'</h6>
                        <div class="contenedor1">                                
                            <div class="comentario1">'.$contenido.'
                            </div>'.$borrado.' 
                        </div>        
                    </div>
                </div><img src="/Vistas/imagenes/separador.png" class="separador"><br/>';
    }

    public function normalizarDate($vecFecha){
        $hora=str_pad((int) $vecFecha['hour'],2,"0",STR_PAD_LEFT);
        $min=str_pad((int) $vecFecha['minute'],2,"0",STR_PAD_LEFT);
        $mes=str_pad((int) $vecFecha['month'],2,"0",STR_PAD_LEFT);
        $dia=str_pad((int) $vecFecha['day'],2,"0",STR_PAD_LEFT);
        return $fecha=array('anio'=>$vecFecha['year'],
                            'mes'=>$mes,
                            'dia'=>$dia,
                            'hora'=>$hora,
                            'minutos'=>$min,
                            );
    }
    
}

?>