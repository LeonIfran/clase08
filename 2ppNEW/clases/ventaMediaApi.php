<?php 
require_once 'IApiUsable.php';
require_once 'ventaMedia.php';

class ventaMediaApi extends ventaMedia implements IApiUsable
{
    #region funciones
    public function CargarUno($request, $response, $args)
    {
        $miRespuesta = new stdclass();

        $arrayDeParametros = $request->getParsedBody();
        $archivos = $request->getUploadedFiles();
        $destino = "clases/FotosVentas/";
        //guardo los parametros en variables locales
        $idMed = $arrayDeParametros['idMedia'];
        $cliente = $arrayDeParametros['nombreCliente'];
        //$fecha = $arrayDeParametros['fecha'];
        $importe = $arrayDeParametros['importe'];
        $fecha = date("Y-m-d H-i-s");//#####RECORDAR SACARLE LA HORA SI HAY PROBLEMAS!!!######
        $nombreFoto = $idMed."_".$cliente."_".$fecha;
        
        $miVenta = new ventaMedia();
        $miVenta->setidMedia($idMed);
        $miVenta->setnombreCliente($cliente);
        $miVenta->setFecha($fecha);
        $miVenta->setImporte($importe);

        //#### Guardar la foto si esta seteada #####
        if (isset($archivos['fotoV'])) 
        {//El nombre de la foto se conformará por el ID de la Media, el nombre del cliente y la fecha
            //echo var_dump($archivos['fotoV']).'<br>';
            
            //obtengo la extension del archivo, uso la funcion getClientFilename porque este es un objeto de slim
            $extension = pathinfo($archivos['fotoV']->getClientFilename(), PATHINFO_EXTENSION);
            //echo $extension.'<br>';
            $nombreFoto = $nombreFoto.".".$extension;
            $archivos['fotoV']->moveTo($destino.$nombreFoto);
        }
        //##### Ejecuto la consulta #####
        $miVenta->insertarVenta();

        $miRespuesta->respuesta = "Se guardo la venta con ID $idMed y el archivo en $destino".$nombreFoto;
        return $response->withJson($miRespuesta,200);
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $laventa = ventaMedia::TraerUnaVenta($id);
        $NuevaRespuesta;
        if (!$laventa) 
        {
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->error="No Se encontro la venta";
            $NuevaRespuesta = $response->withJson($objDelaRespuesta, 500); 
        }
        else 
        {
            $NuevaRespuesta = $response->withjson($laventa,200);
        }
        return $NuevaRespuesta;
    } 
    public function TraerTodos($request, $response, $args)
    {}
    public function BorrarUno($request, $response, $args)
    {
        $id=$args['id'];
        $venta = new ventaMedia();
        $venta->setId($id);
        $objABorrar = ventaMedia::TraerUnaVenta($id);//obtengo el objeto a borrar para sacarle el filename y demas
        $destino = 'clases/FotosVentas/';
        $nombreFoto = $objABorrar->idMedia."_".$objABorrar->nombreCliente;
        $cantidadDeBorrados = $venta->BorrarUnaVenta();//borro el objeto
       
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->cantidad=$cantidadDeBorrados;

        //######### PARA BORRAR LA IMAGEN###############
        $arrImagenes = glob($destino.$nombreFoto."_*.{jpeg,jpg,png}",GLOB_BRACE);
        //################## TERMINA EL BORRADO DE LA IMAGEN ############
        if ($cantidadDeBorrados>0) 
        {   
            $objDelaRespuesta->resultado = "Se borro la venta con id: $id ";

            if (!empty($arrImagenes[0])) 
            {
                $ext = pathinfo($arrImagenes[0],PATHINFO_EXTENSION);
                unlink($arrImagenes[0]);
                $objDelaRespuesta->resultado = "Se borro la foto de venta con nombre: ".$arrImagenes[0];

            }
        }
        else 
        {
            $objDelaRespuesta->resultado="no Borro nada!!!";
        }
        
        $newResponse = $response->withJson($objDelaRespuesta,200);
        return $newResponse;

    }
    public function ModificarUno($request, $response, $args)
    {//uso esto asumiendo que la request va a ser post o PUT conform url encoded
        $miRespuesta = new stdclass();
        $miVenta = new ventaMedia();
        $carpeta = 'clases/FotosVentas/';
        $archivos = $request->getUploadedFiles();

        $arrayDeParametros = $request->getParsedBody();
        $id = $arrayDeParametros['id'];
        $idMedia = $arrayDeParametros['idMed'];
        $nombre = $arrayDeParametros['nombre'];
        $fecha = $arrayDeParametros['fecha'];
        //$fecha = date("Y-m-d H-i-s"); // #### en caso de que no haya que modificar la fecha ####
        $importe = $arrayDeParametros['importe'];

        //tomo el nombre de la imagen (El nombre de la foto se conformará por el ID de la Media, el nombre del cliente y la fecha)
        $nombreImagen = $idMedia."_".$nombre."_".$fecha;

        $miVenta->setId($id);
        $miVenta->setIdMedia($idMedia);
        $miVenta->setnombreCliente($nombre);
        $miVenta->setFecha($fecha);
        $miVenta->setImporte($importe);

        //####################### verificacion de la imagen y demas ##################################
        if (isset($archivos['fotoV'])) 
        {
            $arrImagenes = glob($carpeta.$nombreImagen.".{jpeg,jpg,png}",GLOB_BRACE);
            if (!empty($arrImagenes[0])) 
            {
                $ext = pathinfo($arrImagenes[0],PATHINFO_EXTENSION);
                rename($arrImagenes[0],"clases/FotosVentas/Backup/".$nombreImagen.".".$ext);
                $miRespuesta->Imgbackup="Se movio la imagen $nombreImagen.$ext a la carpeta backup";
    
            }
            $extension = pathinfo($archivos['fotoV']->getClientFilename(), PATHINFO_EXTENSION);
            
            $archivos['fotoV']->moveTo($carpeta.$nombreImagen.".".$extension);
        }
        //####################### fin de la verificacion de imagen ##################################

        $resultado = $miVenta->modificarVenta();

        $miRespuesta->resultado = $resultado;
        $miRespuesta->mensaje = "Se modifico la venta con ID $id";
        return $response->withJson($miRespuesta,200);
    }

    public static function GuardarImagen()
    {

    }
    #endregion
}

?>