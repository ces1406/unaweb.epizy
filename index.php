<?php
//error_reporting(0);// ------>OJO CUANDO SE DESACTIVA NO SE MANTIENEN SESIONES
$dir = dirname(__FILE__,1);
define('DIR_RAIZ',          $dir);
define('DIR_APP',           '/LogicaAplicacion');
define('DIR_DESPACHADOR',   '/Despachador.php');
define('DIR_PEDIDO',        '/Pedido.php');
define('DIR_CONTROLADOR',   '/Controladores');
define('DIR_VISTA',         '/Vistas');
define('DIR_HTMLS',         '/htmls');
define('DIR_USERS',         '/imagenesUsers/');
define('DIR_MODELOS',       '/modelo/modelo.php');
include DIR_RAIZ.DIR_APP.'/init.php';
include DIR_RAIZ.DIR_APP.DIR_PEDIDO;
include DIR_RAIZ.DIR_APP.DIR_DESPACHADOR;
try {
    $pedido = new Pedido();
    $despachador = new Despachador($pedido);
    $vista = $despachador->responder();
    $vista->renderizar();
}catch (Exception $e){
    echo $e->getMessage();
}

?>
