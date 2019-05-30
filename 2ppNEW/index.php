<?php
/* header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization');
 header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
 header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); 
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require './vendor/autoload.php';
require_once './clases/AccesoDatos.php';
require_once './clases/mediaApi.php';
require_once './clases/usuarioApi.php';
require_once './clases/ventaMediaApi.php';
require_once './clases/AutentificadorJWT.php';
require_once './clases/MWparaCORS.php';
require_once './clases/MWparaAutentificar.php';
require_once './clases/MWparaFiltrar.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
}); 

$app->group('/media',function(){
    $this->post('/alta',\mediaApi::class . ':CargarUno');
    $this->post('/altaFoto',\mediaApi::class . ':subirSoloFoto');
    $this->get('/listado',\mediaApi::class . ':TraerTodos')->add(\MWparaFiltrar::class . ':FiltrarDatos');
    $this->get('/traer/{id}', \mediaApi::class . ':TraerUno');
    $this->get('/traerTodas', \mediaApi::class . ':TraerTodasLasFotos');
    $this->delete('/borrar/{id}',\mediaApi::class . ':BorrarUno')->add(\MWparaAutentificar::class . ':VerificarUsuario');
    //$this->post('/',\personalApi::class . ':InsertarUno');
});

$app->group('/usuario',function(){
    $this->post('/alta',\usuarioApi::class . ':CargarUno');
    $this->get('/listado',\usuarioApi::class . ':TraerTodos')->add(\MWparaAutentificar::class . ':VerificarUsuario');
});

$app->group('/ventas',function(){
    $this->post('/alta',\ventaMediaApi::class . ':CargarUno')->add(\MWparaAutentificar::class . ':VerificarUsuario');
    $this->post('/modificar',\ventaMediaApi::class . ':ModificarUno');
    $this->get('/traer/{id}',\ventaMediaApi::class . ':TraerUno');
    $this->delete('/borrar/{id}',\ventaMediaApi::class . ':BorrarUno');
});

$app->group('/entrada',function(){
    $this->post('/login',\usuarioApi::class . ':LogearApi');
});

$app->put('/test/{valor1}',function(Request $request, Response $response, $args){
    $arrayDeparametros = $request->getParams();
    //$array2 = $request->getParsedBody();
    echo var_dump($arrayDeparametros);
    echo $args['valor1'];
    //echo var_dump($array2);
    echo var_dump($request->getAttribute('valor1'));
    //echo $arrayDeparametros['valor2'];
    $array2 = $request->getParsedBody();
    echo var_dump($array2);
});

$app->run();
?>