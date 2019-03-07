<?php
require_once DIR_RAIZ.DIR_CONTROLADOR.'/ControladorPorDefecto.php';

class ControladorAdministrar extends ControladorPorDefecto
{
    public function __construct(){
        parent::__construct();
        if (!$this->getUsuario()->esAdmin()) {
            return $this->msjAtencion('Tenes que ser administrador para acceder.');
        }
    }
    
    public function metodoIniciarAdmin(){
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cuerpoAdmin'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/admin.js');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->setearFormuListarUsers();
        $this->setearFormuListarTemas();
        return $this->getVista();
    }
    
    public function metodoListarUsuarios() {
        if(!$this->chequearPass()) return $this->msjAtencion("error en la contraseña ingresada");       
        $vecUsuarios=Modelo::listarUsuarios();
        $listado=null;
        if(!empty($vecUsuarios)){
            $listado = '<div class="table-responsive panel1 tabla2"><table class="table" id="tablaLista"><tr><td>idUsuario</td><td>';
            $listado .= 'apodo</td><td>mail</td><td>fecha de ingreso</td><td>estado de cuenta</td><td>rol</td></tr>';
            foreach ($vecUsuarios as $user) {
                $listado .= '<tr><td>'.$user['idUsuario'].'</td><td>'.$user['apodo'].'</td><td>'.$user['mail'].'</td><td>';
                $listado .= $user['fechaIngreso'].'</td><td>'.$user['estadoCuenta'].'</td><td>'.$user['rol'].'</td></tr>';
                
            }
            $listado .='</table></div>';
        }
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cuerpoAdmin'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/admin.js');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->setearFormuListarTemas();
        $this->getVista()->modificarCuerpo('{listado}',$listado);
        return $this->getVista();
    }
    
    public function metodoBorrarUsuario() {
        if(!$this->chequearPass()) return $this->msjAtencion("error en la contraseña ingresada");
        $idUsuario=trim($_POST['unIdUsuario']);
        if (!filter_input(INPUT_POST,'unIdUsuario',FILTER_VALIDATE_INT)) {
            return $this->msjAtencion('error en el id de usuario ingresado');
        }
        $resultado=Modelo::borrarUsuario($idUsuario);
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cuerpoAdmin'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/admin.js');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->setearFormuListarUsers();
        $this->setearFormuListarTemas();
        return $this->getVista();
    }
    
    public function metodoSuspenderUsuario () {
        if(!$this->chequearPass()) return $this->msjAtencion("error en la contraseña ingresada");
        $idUsuario=trim($_POST['unIdUsuario']);
        if (!filter_input(INPUT_POST,'unIdUsuario',FILTER_VALIDATE_INT)) {
            return $this->msjAtencion('error en el id de usuario ingresado');
        }
        Modelo::deshabilitarUsuario($idUsuario);
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cuerpoAdmin'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/admin.js');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->setearFormuListarUsers();
        $this->setearFormuListarTemas();
        return $this->getVista();
    }
    
    public function metodoHabilitarUsuario () {
        if(!$this->chequearPass()) return $this->msjAtencion("error en la contraseña ingresada");;
        $idUsuario=trim($_POST['unIdUsuario']);
        if (!filter_input(INPUT_POST,'unIdUsuario',FILTER_VALIDATE_INT)) {
            return $this->msjAtencion('error en el id de usuario ingresado');
        }
        Modelo::habilitarUsuario($idUsuario);
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cuerpoAdmin'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/admin.js');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->setearFormuListarUsers();
        $this->setearFormuListarTemas();
        return $this->getVista();
    }
    
    public function metodoCrearSeccion () {
        if(!$this->chequearPass()) return $this->msjAtencion("error en la contraseña ingresada");
        $titulo=trim($_POST['unTituloSeccion']);
        $descripcion=trim($_POST['unaDescripcionSeccion']);
        
        if(!isset($titulo)||empty($titulo)||strlen($titulo)>TAM_TIT_SEC_MAX||!is_string($titulo)){
            return $this->msjAtencion('error en el titulo de seccion');
        }
        if(!isset($descripcion)||empty($descripcion)||strlen($descripcion)>TAM_DESC_SEC_MAX||!is_string($descripcion)){
            return $this->msjAtencion('error en la descripcion de seccion');
        }
        Modelo::crearSeccion($titulo,$descripcion);
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cuerpoAdmin'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/admin.js');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->setearFormuListarUsers();
        $this->setearFormuListarTemas();
        return $this->getVista();
    }
    
    public function metodoEliminarSeccion () {
        if(!$this->chequearPass()) return $this->msjAtencion("error en la contraseña ingresada");
        Modelo::eliminarSeccion($_POST['unTituloSeccion']);
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cuerpoAdmin'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/admin.js');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->setearFormuListarUsers();
        $this->setearFormuListarTemas();
        return $this->getVista();
    }
    
    public function metodoListarTemas() {
        if(!$this->chequearPass()) return $this->msjAtencion("error en la contraseña ingresada");
        $vecTemas=Modelo::listarTemas();
        $listado=null;
        if(!empty($vecTemas)){
            $listado='<div class="table-responsive  panel1 tabla2">
                        <table class="table" id="tablaLista">
                            <tr><td>idTema</td><td>titulo</td><td>idSeccion</td><td>idUsuario creador</td><td>fecha</td></tr>';
            foreach ($vecTemas as $tema) {
                $listado .= '<tr><td>'.$tema['idTema'].'</td><td>'.htmlentities($tema['titulo']).'</td><td>'.$tema['idSeccion'];
                $listado .= '</td><td>'.$tema['idUsuario'].'</td><td>'.$tema['fechaCreacion'].'</td></tr>';
                
            }
            $listado .='</table></div>';
        }else{
            $listado='<h3>No hay temas?</h3>';
        }        
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cuerpoAdmin'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/admin.js');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->getVista()->modificarCuerpo('{listaTemas}',$listado);
        $this->setearFormuListarUSers();
        return $this->getVista();
    }
    
    public function metodoEliminarTema() {
        if(!$this->chequearPass()) return $this->msjAtencion("error en la contraseña ingresada");
        $idTema=trim($_POST['unIdTema']);
        if (!filter_input(INPUT_POST,'unIdTema',FILTER_VALIDATE_INT)) {
            return $this->msjAtencion('error en el id del tema ingresado');
        }
        Modelo::eliminarTema($idTema);
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cuerpoAdmin'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/admin.js');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->setearFormuListarUsers();
        $this->setearFormuListarTemas();
        return $this->getVista();
    }
    
    public function metodoEliminarCurso() {
        if(!$this->chequearPass()) return $this->msjAtencion("error en la contraseña ingresada");
        $codCurso=trim($_POST['unCodCurso']);
        if (empty($codCurso)|| is_null($codCurso)|| $codCurso>TAM_MAX_CODCURSO) {
            return $this->msjAtencion('error en el codigo del curso ingresado');
        }
        Modelo::eliminarCurso($codCurso);
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cuerpoAdmin'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/admin.js');
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->setearFormuListarUsers();
        $this->setearFormuListarTemas();
        return $this->getVista();
    }
    
    public function metodoEliminarApunte($idApunte) {
        if(!$this->chequearPass()) return $this->msjAtencion("error en la contraseña ingresada");
        if (!is_numeric($idApunte))return $this->msjAtencion('error en el id del apunte ingresado');
        if($this->getUsuario()->getRol()=='ADMI'){
            if($_POST['confirmado']=='si'){
                Modelo::eliminarApunte($idApunte);
            }
        }
        header('Location:http://'.DOMINIO.'/Seccion/irAApuntes/default/0/Apuntes/10/1');  
    }
    
    public function metodoEliminarComentario($idSec,$idComent,$idTema,$pag){
        if(!$this->chequearPass())  return $this->msjAtencion("error en la contraseña ingresada");
        if (!is_numeric($idComent))     return $this->msjAtencion('error en el id del apunte ingresado');
        if($this->getUsuario()->getRol()=='ADMI'){
            if($_POST['confirmado']=='si'){
                Modelo::eliminarComentario($idComent);
            }
        }
        header('Location:http://'.DOMINIO.'/Seccion/irTema/'.$idSec.'/'.$idTema.'/'.$pag);
        exit();
    }

    public function metodoEliminarComentCatedra($idComent,$idCatedra,$pag){
        if(!$this->chequearPass())  return $this->msjAtencion("error en la contraseña ingresada");
        if (!is_numeric($idComent)) return $this->msjAtencion('error en el id del apunte ingresado');
        if($this->getUsuario()->getRol()=='ADMI'){
            if($_POST['confirmado']=='si'){
                Modelo::eliminarComentCatedra($idComent);
            }
        }
        header('Location:http://'.DOMINIO.'/Seccion/irHiloOpinion/'.$idCatedra.'/'.$pag);
    }
    
    public function metodoEliminarComentCurso($idComent,$idCurso,$pag){/*TODO*/
        if(!$this->chequearPass())  return $this->msjAtencion("error en la contraseña ingresada");
        if (!is_numeric($idComent)) return $this->msjAtencion('error en el id del comentario');
        if($this->getUsuario()->getRol()=='ADMI'){
            if($_POST['confirmado']=='si'){
                Modelo::eliminarComentCurso($idComent);
            }
        }
        header('Location:http://'.DOMINIO.'/Seccion/Curso/'.$idCurso.'/'.$pag);
    }

    public function setearFormuListarUsers() {
        $this->getVista()->modificarCuerpo('{listado}','<form class="form-horizontal" id="listadoUsuarios" action="/Administrar
                                                /ListarUsuarios" method="POST" enctype="multipart/form-data">
							<div class="form-group row " id="formularioAdmin">
								<label class="col-sm-3 col-form-label" for="unaPassword1" >Contraseña de Admin.:</label>
								<div class="col-sm-4">
									<input type="password" class="form-control" name="unaPassword1" id="password1" required>
									<small id="avisoPass1" class="form-text text-muted">entre 6 y 8 caracteres</small>
								</div>
							</div>
							<input type="submit" id="ListarUsuers" value="Listar" class="btn btn-sm enlace">
						</form>');
    }
    
    public function setearFormuListarTemas() {
        $this->getVista()->modificarCuerpo('{listaTemas}','<form class="form-horizontal" id="listadoTemas" action="/Administrar
                                               /ListarTemas" method="POST" enctype="multipart/form-data">
							<h5>Listar temas ordenados por su titulo</h5>
							<div class="form-group row " id="formularioAdmin">
								<label class="col-sm-3 col-form-label" for="unaPassword1" >Contraseña de Admin.:</label>
								<div class="col-sm-4">
									<input type="password" class="form-control" name="unaPassword1" id="password1" required>
									<small id="" class="form-text text-muted">entre 6 y 8 caracteres</small>
								</div>
							</div>
							<input type="submit" id="ListarTemas" value="Listar" class="btn btn-sm enlace">
						</form>');
    }
    
    public function chequearPass() {
        $pass1=trim($_POST['unaPassword1']);
        if(!isset($pass1)||empty($pass1)||strlen($pass1)>TAM_PASS_MAX||!is_string($pass1)||strlen($pass1)<TAM_PASS_MIN){
            return false;
        }
        $vecUser=Modelo::buscarUsuarioPorApodo($_SESSION['apodo']);
        if(! password_verify($pass1,$vecUser['contrasenia']) ){
            return false;
        }
        return true;
    }
}

?>