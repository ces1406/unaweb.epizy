<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once DIR_RAIZ.DIR_CONTROLADOR.'/ControladorPorDefecto.php';
require_once DIR_RAIZ.'/vendor/autoload.php';

class ControladorRegistrarUsuario extends ControladorPorDefecto{

    public function __construct() {
        parent::__construct();
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/sectorIzquierdo'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{colIzq}','12');
        $this->getVista()->modificarCuerpo('{tituloIzq}','Registrandose en WebUNA');
        $this->getVista()->modificarCuerpo('{pieIzq}','registrate como usuario del sitio');
    }
    
    public function metodoIniciarRegistro(){
        if($this->getUsuario()->tieneSesion()){
            return $this->msjAtencion('Tienes sesion iniciada. Para registrarte en el sitio no debes estar logueado.');
        }
        $this->getVista()->modificarCuerpo('{panelIzq}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/formularioRegistrarse'));
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/registrarse.js');
        return $this->getVista();
    }
    
    public function metodoIniciarRecupPass(){
        $this->getVista()->modificarCuerpo('{panelIzq}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/formularioRecuperarPass'));
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/registrarse.js');
        return $this->getVista();
    }
    
    public function metodoRecuperarPass() {
        $apodo=trim($_POST['unApodo']);
        if(!isset($apodo)||empty($apodo)||strlen($apodo)>TAM_APODO_MAX||!is_string($apodo)||strlen($apodo)<TAM_APODO_MIN){
            return $this->msjAtencion('error en el apodo ingresado');
        }
        $vecUser=Modelo::buscarUsuarioPorApodo($_POST['unApodo']);
        if (empty($vecUser)) {
            return $this->msjAtencion("Tu apodo es inexistente, chequea que sea el  correcto");
        }
        $token=substr(uniqid(rand(),true),0,7);
        Modelo::actualizarPassUsuario($vecUser["idUsuario"],password_hash($token,PASSWORD_DEFAULT));
        $mensaje="Hola ".$apodo." te enviamos este mail debido al pedido que realizaste de recuperacion de contraseña en mundoUnaWeb";
        $mensaje .= ", por cuestiones de seguridad tu anterior contraseña fue borrada y la actual es: ".$token." si quieres modificarla";
        $mensaje .= "puedes hacerlo ingresando a tu perfil";        
        if (($resultado=$this->enviarMail($mensaje,$vecUser['mail'],$apodo))!='OK') {
            return $this->msjAtencion($resultado);
        }
        $msj='<h2>Ya enviamos un correo a la direccion de mail que indicaste con la contraseña para tu usuario<br/></h2>';
        $this->getVista()->modificarCuerpo('Registrandose en WebUNA','Recuperando la contraseña en WebUNA');
        $this->getVista()->modificarCuerpo('{panelIzq}',$msj);
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/registrarse.js');
        return $this->getVista();
    }
    
    public function metodoCrearUsuario() {
        // Sanitizando y validando
        $apodo = trim($_POST['unApodo']);
        $pass1 = trim($_POST['unaPassword1']);
        $pass2 = trim($_POST['unaPassword2']);
        $mail = trim($_POST['unMail']);
        $redSocial1 = trim($_POST['unaRedSocial1']);
        $redSocial2 = trim($_POST['unaRedSocial2']);
        if(!isset($apodo)||empty($apodo)||strlen($apodo)>TAM_APODO_MAX||!is_string($apodo)||strlen($apodo)<TAM_APODO_MIN){
            return $this->msjAtencion('error en el apodo ingresado');
        }
        if(!isset($pass1)||empty($pass1)||strlen($pass1)>TAM_PASS_MAX||!is_string($pass1)||strlen($pass1)<TAM_PASS_MIN){
            return $this->msjAtencion('error en la contraseña ingresada');
        }
        if(!isset($pass2)||empty($pass2)||strlen($pass2)>TAM_PASS_MAX||!is_string($pass2)||strlen($pass2)<TAM_PASS_MIN){
            return $this->msjAtencion('error en la contraseña ingresada');
        }
        if($pass1!=$pass2){
            return $this->msjAtencion('error, las contraseñas no coinciden');
        }
        if(!filter_input(INPUT_POST,'unMail',FILTER_VALIDATE_EMAIL)){
            return $this->msjAtencion('error en el mail que se indico');
        }
        // Chequear que ya no existen ese apodo y mail
        $mailUser=NULL;
        $mailUser=Modelo::buscarMail($mail);
        if( ($mailUser!=NULL)||!(empty($mailUser))){
           return $this->msjAtencion('error, el mail que indicaste ya existe (si olvidaste tu password ir a "iniciar sesion"
                                        y clickear en "olvide mi contraseña")');
        }elseif (Modelo::buscarUsuarioPorApodo($apodo)!=NULL){
           return $this->msjAtencion('error, ya existe un usuario con ese apodo intenta con otro');
        }
        // Generando un token para enviarlo por mail y luego validarlo en $this->metodoConfirmacion()
        $token=uniqid(rand(),true);
        Modelo::altaUsuario($apodo,$mail,password_hash($pass1,PASSWORD_DEFAULT),'USER',$token,'SINCONF',$redSocial1,$redSocial2);
        
        $vecUser=Modelo::buscarUsuarioPorApodo($_POST['unApodo']);

        if(($resultado=$this->controlarImgSubida($apodo))!='OK'){
            $msjErr.=$resultado;
            $nombre=NULL;
        }else{
            $nombre = $this->nuevaImg($_FILES['unImg']['name'],$apodo);
            Modelo::actualizarImgUsuario($vecUser['idUsuario'],$nombre);
        }
       
        // Enviando mail
        $mensaje="Bienvenida/o ".$apodo." a ".NOMBRE_SITIO.", te enviamos este mail para confirmar tu registro al sitio, ahora como ultimo";
        $mensaje .= "paso necesitas ir al siguiente enlace para confirmar tu registracion: ".DOMINIO."/RegistrarUsuario/Confirmacion/";
        $mensaje .= $vecUser['idUsuario'].'/'.$token;
        if (($resultado=$this->enviarMail($mensaje,$vecUser['mail'],$apodo))!='OK') {
            return $this->msjAtencion($resultado);
        }
        $msj='<h2>BienVenida/o a '.NOMBRE_SITIO.'!<br/>Enviamos un correo a la direccion de mail que indicaste para que
            lo confirmes y termines de registrarte en forma definitiva.<br/>'.$msjErr.'</h2>';
        $this->getVista()->modificarCuerpo('{panelIzq}',$msj);        
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/registrarse.js');
        return $this->getVista();
    }
    
    public function metodoConfirmacion($idUsuario,$unToken){
        $vecUser=Modelo::buscarUsuario($idUsuario);
        if($unToken==$vecUser['token']){
            Modelo::actualizarEstadoCuenta($idUsuario);
            $msj='<h2>BienVenida/o a'.NOMBRE_SITIO.'!<br/>Ahora estas habilitado definitivamente y puedes participar activamente en los
                    diferentes foros con tu sesion de usuario.</h2>';
            $this->getVista()->modificarCuerpo('{panelIzq}',$msj);
            $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
            $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/registrarse.js');
            return $this->getVista();
        }else{
            $unMsj='No pudo confirmarse el mail enviado a tu correo, revisa que el link que te enviamos por mail coincida exactamen';
            $unMsj.='te con el que figura en tu barra de direcciones. Si es así puedes reintentarlo, o puedes consultarnos por mail';
            return $this->msjAtencion($unMsj);
        }
    }
    
    public function metodoConfigPerfil($param) {
        if (!$this->getUsuario()->tieneSesion()) {
            return $this->msjAtencion('Tenes que tener tu sesion iniciada');
        }
        $submenuCamibarImg=null;
        $submenuPassyMai=null;
        $menuFuncAdmin  = '<li class="list-group-item bg-dark"><a href="/Administrar/IniciarAdmin" class="btn btn-primary bg-dark">
                            Funciones de administrador</a></li>';
        $menuImgCambiar = '<a href="/RegistrarUsuario/ConfigPerfil/img" class="btn btn-primary bg-dark">Cambiar</a>';
        $menuPassMail0  = '<li class="list-group-item bg-dark"><a href="/RegistrarUsuario/ConfigPerfil/mail" class="btn btn-primary
                             bg-dark">Cambiar direccion de mail';
        $menuPassMail0 .= '</a></li><li class="list-group-item bg-dark"><a href="/RegistrarUsuario/ConfigPerfil/pass" class="btn 
                            btn-primary bg-dark">Cambiar contraseña</a></li>';
        $menuPassMail0 .='<li class="list-group-item bg-dark"><a href="/RegistrarUsuario/ConfigPerfil/face" class="btn btn-primary bg-dark">
                            Cambiar Facebook</a></li><li class="list-group-item bg-dark"><a href="/RegistrarUsuario/ConfigPerfil/redSoc1" 
                            class="btn btn-primary bg-dark">Cambiar pagina personal</a></li>';       
        
        if ($param == 'img') {
            $submenuCambiarImg=file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/formularioCambiarImg');
            $submenuPassyMail=$menuPassMail0;
        }elseif ($param == 'mail'){
            $submenuCambiarImg = $menuImgCambiar;
            $submenuPassyMail  = file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/formularioCambiarMail');
        }elseif ($param == 'pass'){
            $submenuCambiarImg = $menuImgCambiar;
            $submenuPassyMail  = file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/formularioCambiarPass');
        }elseif ($param == 'face'){
            $submenuCambiarImg = $menuImgCambiar;
            $submenuPassyMail=file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/formularioCambiarFace');
        }elseif ($param == 'redSoc1'){
            $submenuCambiarImg = $menuImgCambiar;
            $submenuPassyMail=file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/formularioCambiarRedSocial1');
        }elseif ($param == 'default'){
            $submenuCambiarImg = $menuImgCambiar;
            $submenuPassyMail=$menuPassMail0;
        }elseif($param == 'subirImg'){
            $resultado=$this->cambiarImg();
            $submenuPassyMail=$menuPassMail0;
            $submenuCambiarImg='<a href="/RegistrarUsuario/ConfigPerfil/img" class="btn btn-primary bg-dark">Cambiar</a><small>'.$resultado.'</small>';
        }elseif($param == 'cambiarMail'){
            $resultado=$this->cambiarMail();
            return $this->msjAtencion($resultado);
        }elseif($param=='cambiarFace'){
            $resultado=$this->cambiarFace();
            return $this->msjAtencion($resultado);
        }elseif($param=='cambiarRedSocial1'){
            $resultado=$this->cambiarRedSocial1();
            return $this->msjAtencion($resultado);
        }elseif($param == 'cambiarPass'){
            $resultado=$this->cambiarPass();
            return $this->msjAtencion($resultado);
        }else{
            return $this->msjAtencion('pagina inexistente');
        }
        if($this->getUsuario()->esAdmin()){
            $submenuPassyMail .= $menuFuncAdmin;
        }
        $this->getVista()->armarHtml();
        $this->getVista()->modificarCuerpo('{sectorIzquierda}',file_get_contents(DIR_RAIZ.DIR_VISTA.DIR_HTMLS.'/cuerpoPerfil'));
        $this->getVista()->modificarCuerpo('{sectorDerecha}','');
        $this->getVista()->modificarCuerpo('{submenuCambiarImg}',$submenuCambiarImg);
        $this->getVista()->modificarCuerpo('{submenuPassyMail}',$submenuPassyMail);
        $this->getVista()->modificarCuerpo('{apodo}',$_SESSION['apodo']);
        $this->getVista()->modificarCuerpo('{mail}',$_SESSION['mail']);
        $this->getVista()->modificarCuerpo('{facebook}',$_SESSION['face']);
        $this->getVista()->modificarCuerpo('{redSocial1}',$_SESSION['redSoc1']);
        $this->getVista()->modificarCuerpo('{archCss}','/Vistas/css/index.css');
        $this->getVista()->modificarCuerpo('{scriptJs}','/Vistas/js/perfil.js');
        return $this->getVista();
    }
    
    public function cambiarImg() {
        $ext=0;
        $pass1 = trim($_POST['unaPassword1']);
        if(!isset($pass1)||empty($pass1)||strlen($pass1)>TAM_PASS_MAX||!is_string($pass1)||strlen($pass1)<TAM_PASS_MIN){
            return 'error en la contraseña ingresada';
        }
        $vecUser=Modelo::buscarUsuarioPorApodo($_SESSION['apodo']);
        if(! password_verify($pass1,$vecUser['contrasenia']) ){
            return 'error en la contraseña ingresada';
        }

        if(($resultado=$this->controlarImgSubida($_SESSION['apodo']))!='OK'){
            return $this->msjAtencion($resultado);
        }
        $nombre = $this->nuevaImg($_FILES['unImg']['name'],$_SESSION['apodo']);
        Modelo::actualizarImgUsuario($vecUser['idUsuario'],$nombre);
        $_SESSION['img'] = $nombre;
        return 'La imagen se subio correctamente';        
    }

    public function nuevaImg($nombreImg,$apodo){
        if (substr($nombreImg,-3)=='peg') {
            $ext=-4;
        }else{
            $ext=-3;            
        }
        $nombre  = $apodo.'Img.'.substr($nombreImg,$ext);
        $imgJpg  = $apodo.'Img.jpg';
        $imgJpeg = $apodo.'Img.jpeg';
        $imgPng  = $apodo.'Img.png';
        $imgGif  = $apodo.'Img.gif';
        
        if ( file_exists(DIR_RAIZ.DIR_VISTA.DIR_USERS.$imgJpg) && ($imgJpg != $nombre) ){
            unlink( DIR_RAIZ.DIR_VISTA.DIR_USERS.$imgJpg);
        }
        if ( file_exists(DIR_RAIZ.DIR_VISTA.DIR_USERS.$imgJpeg) && ($imgJpeg != $nombre) ) {
            unlink(DIR_RAIZ.DIR_VISTA.DIR_USERS.$imgJpeg);
        }
        if ( file_exists(DIR_RAIZ.DIR_VISTA.DIR_USERS.$imgPng) && ($imgPng != $nombre) ) {
            unlink(DIR_RAIZ.DIR_VISTA.DIR_USERS.$imgPng);
        }
        if ( file_exists(DIR_RAIZ.DIR_VISTA.DIR_USERS.$imgGif) && ($imgGif != $nombre) ) {
            unlink(DIR_RAIZ.DIR_VISTA.DIR_USERS.$imgGif);
        }        
        return $nombre;
    }
    
    public function cambiarMail() {
        $pass1=trim($_POST['unaPassword1']);
        if(!isset($pass1)||empty($pass1)||strlen($pass1)>TAM_PASS_MAX||!is_string($pass1)||strlen($pass1)<TAM_PASS_MIN){
            return 'error en la contraseña ingresada';
        }
        if(!filter_input(INPUT_POST,'unMail',FILTER_VALIDATE_EMAIL)){
            return 'error en el mail que se indico';
        }
        $vecUser=Modelo::buscarUsuarioPorApodo($_SESSION['apodo']);
        if(! password_verify($pass1,$vecUser['contrasenia']) ){
            return 'error en la contraseña ingresada';
        }
        // Enviando mail
        $token=uniqid(rand(),true);
        $mensaje="Hola ".$vecUSer['apodo'].", te enviamos este mail para confirmar tu nueva direccion de correo.";
        $mensaje .= "Ahora necesitas ir al siguiente enlace para confirmar el cambio: ";
        $mensaje .= DOMINIO."/RegistrarUsuario/Confirmacion/";
        $mensaje .= $vecUser['idUsuario'].'/'.$token;
        if (($resultado=$this->enviarMail($mensaje,$_POST['unMail'],$apodo))!='OK') {
            return $resultado;
        }
        Modelo::actualizarMailToken($vecUser['idUsuario'],$_POST['unMail'],$token);
        $this->cerrarSesion();
        return 'Se modifico tu direccion de correo y tu cuenta se deshabilito temporalemente hasta que confirmes el mail que acabamos de enviarte';
    }

    public function cambiarFace(){
        $pass1=trim($_POST['unaPassword0']);
        $face=trim($_POST['unFace']);
        if(!isset($pass1)||empty($pass1)||strlen($pass1)>TAM_PASS_MAX||!is_string($pass1)||strlen($pass1)<TAM_PASS_MIN){
            return 'error en la contraseña ingresada';
        }
        if(!isset($face)||empty($face)||strlen($face)>TAM_DIR_WEB_MAX||!is_string($face)||strlen($face)<TAM_DIR_WEB_MIN){
            return 'error en el link al facebook personal';
        }
        $vecUser=Modelo::buscarUsuarioPorApodo($_SESSION['apodo']);
        if(! password_verify($pass1,$vecUser['contrasenia']) ){
            return 'error en la contraseña ingresada';
        }
        Modelo::actualizarFace($vecUser['idUsuario'],$face);
        $_SESSION['face'] = $face;
        return 'El facebook se actualizo correctamente'; 
    }

    public function cambiarRedSocial1(){
        $pass1=trim($_POST['unaPassword0']);
        $red=trim($_POST['unaRedSocial1']);
        if(!isset($pass1)||empty($pass1)||strlen($pass1)>TAM_PASS_MAX||!is_string($pass1)||strlen($pass1)<TAM_PASS_MIN){
            return 'error en la contraseña ingresada';
        }
        if(!isset($red)||empty($red)||strlen($red)>TAM_DIR_WEB_MAX||!is_string($red)||strlen($red)<TAM_DIR_WEB_MIN){
            return 'error en el link al facebook personal';
        }
        $vecUser=Modelo::buscarUsuarioPorApodo($_SESSION['apodo']);
        if(! password_verify($pass1,$vecUser['contrasenia']) ){
            return 'error en la contraseña ingresada';
        }
        Modelo::actualizarRedSocial2($vecUser['idUsuario'],$red);
        $_SESSION['redSoc1'] = $red;
        return 'El link a tu pagina personal se actualizo correctamente'; 
    }

    public function cambiarPass() {
        $pass1=trim($_POST['unaPassword1']);
        $pass2=trim($_POST['unaPassword2']);
        $pass0=trim($_POST['unaPassword0']);
        if(!isset($pass1)||empty($pass1)||strlen($pass1)>TAM_PASS_MAX||!is_string($pass1)||strlen($pass1)<TAM_PASS_MIN){
            return 'error en la contraseña ingresada';
        }
        if(!isset($pass2)||empty($pass2)||strlen($pass2)>TAM_PASS_MAX||!is_string($pass2)||strlen($pass2)<TAM_PASS_MIN){
            return 'error en la contraseña ingresada';
        }
        if(!isset($pass0)||empty($pass0)||strlen($pass0)>TAM_PASS_MAX||!is_string($pass0)||strlen($pass0)<TAM_PASS_MIN){
            return 'error en la contraseña ingresada';
        }
        if($pass1!=$pass2){
            return 'error, las contraseñas no coinciden';
        }
        $vecUser=Modelo::buscarUsuarioPorApodo($_SESSION['apodo']);
        if(! password_verify($pass0,$vecUser['contrasenia']) ){
            return 'error en la contraseña ingresada';
        }
        // Enviando mail
        $token=uniqid(rand(),true);
        $mensaje =  "Hola ".$vecUser['apodo'].", te enviamos este mail para confirmar tu nueva contraseña. ";
        $mensaje .= "Ahora necesitas ir al siguiente enlace para confirmar el cambio: ";
        $mensaje .= DOMINIO."/RegistrarUsuario/Confirmacion/";
        $mensaje .= $vecUser['idUsuario'].'/'.$token;
        if (($resultado=$this->enviarMail($mensaje,$vecUser['mail'],$apodo))!='OK') {
            return $resultado;
        }
        Modelo::actualizarPassToken($vecUser['idUsuario'],password_hash($pass1,PASSWORD_DEFAULT),$token);
        $this->metodoFinSesion();
        return 'Se modifico tu contraseña y tu cuenta se deshabilito temporalemente hasta que confirmes el mail que acabamos de enviarte';
    }
    
    public function enviarMail($msj,$dirDestino,$apodo) {
            $mail = new PHPMailer;//(TRUE);
            $mail->SMTPDebug=0;
            $mail->isSMTP();
            $mail->Host='smtp.gmail.com';
            $mail->Username=MAIL_ADM;
            $mail->Password=PASS_MAIL;
            $mail->SMTPSecure='tls';
            $mail->SMTPAuth=true;
            $mail->Port=587;
            $mail->setFrom(MAIL_ADM,'Administrador de MundoUna');
            $mail->addAddress($dirDestino,$apodo);
            $mail->isHTML(TRUE);
            $mail->Subject="Aviso enviado desde el sitio ".NOMBRE_SITIO;
            $mail->Body= '<h3>'.$msj.'</h3>';
if( $mail->send()){
            $resultado='OK';
        }else{
            $resultado = 'No se pudo enviar el mail a tu direccion de correo. Por favor intentalo luego. '.$mail->ErrorInfo;
        }
        return $resultado;
    }
    
    public function controlarImgSubida($apodo){
        $extensiones=array(0=>'image/jpg',1=>'image/jpeg',2=>'image/png',3=>'image/gif');
        if(!isset($_FILES['unImg']['error'])|| is_array($_FILES['unImg']['error'])){
            return 'parametros invalidos';
        }
        switch ($_FILES['unImg']['error']){
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return 'no se envio ningun archivo';
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'limite de tamaño excedido';
            default:
                return 'error en la subida del archivo';
        }
        if($_FILES['unImg']['size']>100000){
            return 'El tamaño de la imagen supera el tamaño permitido(100KB). Puedes cargar una imagen desde tu perfil en otro momento.';//tamaño excedido
        }else if(empty($_FILES['unImg']['size'])){
            return 'No has cargado ninguna imagen, puedes hacerlo luego desde tu perfil';
        }else if( !in_array($_FILES['unImg']['type'],$extensiones) ){
            return 'El archivo subido no es una imagen (no tiene extension jpg, jpeg, gif o png). Puedes cargar una imagen desde tu perfil en otro momento.';//no es un imagen
        }
        $imagen_temporal = $_FILES['unImg']['tmp_name'];
        if (!is_uploaded_file($imagen_temporal)) {
            return ' No se subio ninguna imagen. Puedes cargar una imagen desde tu perfil en otro momento.';
        }else{            
            if (substr($_FILES['unImg']['name'],-3)=='peg') {
                $nombre=$apodo.'Img.'.substr($_FILES['unImg']['name'],-4);
            }else{
                $nombre=$apodo.'Img.'.substr($_FILES['unImg']['name'],-3);
            }                        
            if(!move_uploaded_file($imagen_temporal, DIR_RAIZ.DIR_VISTA.DIR_USERS.$nombre)){
                return 'Hubo un error y no se pudo subir el archivo imagen. Puedes cargar una imagen desde tu perfil en otro momento.';
            }
        }
        return 'OK';
    }
    
}

?>