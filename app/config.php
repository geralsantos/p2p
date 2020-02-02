<?php
//SEPARADOR
function siteURL()
    {
        /*$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        return $protocol.$domainName;*/
       // return "https://advisorsystem.net";
       return "http://localhost";
    }
define('DS', DIRECTORY_SEPARATOR);
//UBICACIONES PRIVADAS
define( "ROOT", dirname(dirname(__FILE__)));
define( "APP", ROOT . DS . 'app' );
define( "JS_ROOT", ROOT . DS . 'js' );
define( "PUBLICO", ROOT );

//UBICACIONES PUBLICAS
//define( "BASE", "/deincon/portal/" );
define( "BASE", "/p2p/" );

define( "IMG", BASE . 'img' );
define( "JS", BASE . 'js' );
define( "CSS", BASE . 'css' );
define( "ASSETS", BASE . 'assets' );
define( "IMAGES", BASE . 'images' );
define( 'SITE_URL', siteURL() );
define( 'SITE_URL_DIR', siteURL() . BASE );

//define( "RUTA_LOGO", 'https://advisorsystem.net/app/assets/img/deicon.png' );
define( "RUTA_LOGO", 'https://payload220.cargocollective.com/1/2/87981/6711909/lorem_905.png' );
define( "RUTA_IMPRESION", SITE_URL_DIR ."portal/index?id_empresa=2&serie_cor=F001-1&monto=5.00&fecha_emision=2018-08-23" );

//ACCESO A BASE DE DATOS
define('HOST_BASE_DATOS', 'localhost');
define('NOMBRE_BASE_DATOS', 'streaming');
define('USUARIO_BASE_DATOS', 'root');
define('CLAVE_BASE_DATOS', '');
define ('NUM_PERPAGE',10);

//RUTA POR DEFECTO
define ('MODULO_DEFAULT','acceso');
define ('ACCION_DEFAULT','index');

//AMBIENTE
define ('DESARROLLO',TRUE);

//CONFIGURACIÓN DE LA APLICACIÓN
define ('REQUIERE_ACCESO',TRUE);
define ('URL_AMIGABLE',TRUE);
define ('HTML',TRUE);
define ('TITULO_DEFAULT','PORTAL DE CONSULTA');


//RECURSOS GENERALES
$CSS = array("bootstrap/bootstrap.min","bootstrap/bootstrap-theme-yeti.min","global");
$JS  = array("bootstrap/bootstrap.min", "global");
$READY = array("");

//AUXILIARES

define( "URL_PDF", SITE_URL_DIR."app/cargas/" );
define( "URL_PDF_2", SITE_URL_DIR."portal/index" );