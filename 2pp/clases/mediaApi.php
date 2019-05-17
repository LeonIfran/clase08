<?php
require_once 'media.php';
require_once 'IApiUsable.php';

class mediaApi extends media implements IApiUsable
{
 	public function TraerUno($request, $response, $args) {
     	$id=$args['id'];
        $elmedia=media::TraerUnmedia($id);
        if(!$elmedia)
        {
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->error="No esta La media";
            $NuevaRespuesta = $response->withJson($objDelaRespuesta, 500); 
        }else
        {
            $NuevaRespuesta = $response->withJson($elmedia, 200); 
        }     
        return $NuevaRespuesta;
    }
     public function TraerTodos($request, $response, $args) {
      	$todosLosmedias=media::TraerTodoLosmedias();
     	$newresponse = $response->withJson($todosLosmedias, 200);  
    	return $newresponse;
    }

      public function CargarUno($request, $response, $args) {
     	
        $objDelaRespuesta= new media();
        
        $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);
        $color= $ArrayDeParametros['color'];
        $marca= $ArrayDeParametros['marca'];
        $precio= $ArrayDeParametros['precio'];
        $talle =$ArrayDeParametros['talle'];
        

        $mimedia = new media();
        $mimedia->setColor($color);
        $mimedia->setMarca($marca);
        $mimedia->setPrecio($precio);
        $mimedia->setTalle($talle);
        $mimedia->setFoto("vacio");

        //$mimedia->InsertarElmediaParametros();
        
        $archivos = $request->getUploadedFiles();
        $destino="clases/fotos/";
        //var_dump($archivos);
        //var_dump($archivos['foto']);
        if(isset($archivos['foto']))
        {
            $nombreAnterior=$archivos['foto']->getClientFilename();
            $extension= explode(".", $nombreAnterior)  ;//quito el punto y guardo la extension y el nombre en distintos indices
            //var_dump($nombreAnterior);
            $extension=array_reverse($extension);//doy vuelta el array para que la extension este en el indice 0
            //echo $destino.$color.".".$extension[0];
            $archivos['foto']->moveTo($destino.$marca."_".$color.".".$extension[0]);
            //$archivos['foto']->moveTo($destino.$color.".".$extension[0]);
            $pathCompleto = $destino.$marca."_".$color.".".$extension[0];
            //$mimedia->foto=$destino.$marca."_".$color.".".$extension[0];
            $mimedia->setFoto($pathCompleto);
        }

        $mimedia->InsertarElmediaParametros();       
        //$response->getBody()->write("se guardo el media");
        $objDelaRespuesta->respuesta="Se guardo la media $marca $color en: ".$mimedia->getFoto();   
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function subirSoloFoto($request, $response, $args)
    {
        $archivos = $request->getUploadedFiles();
        $datos = $request->getParsedBody();
        $destino="clases/fotos/";
        if(isset($archivos['foto']))
        {
            $nombreAnterior=$archivos['foto']->getClientFilename();
            $extension= explode(".", $nombreAnterior)  ;//quito el punto y guardo la extension y el nombre en distintos indices
           
            $extension=array_reverse($extension);//doy vuelta el array para que la extension este en el indice 0
            
            $archivos['foto']->moveTo($nombreAnterior.".".$extension[0]);
            
            $pathCompleto = $nombreAnterior.".".$extension[0];
            
            $mimedia->setFoto($pathCompleto);
        }
        return $response->withJson($archivos);
        //to-do: pasar la foto de la carpeta temporal a la final
    }



      public function BorrarUno($request, $response, $args) {

        $id=$args['id'];
        $fotoBorrar =media::TraerUnmedia($id);//obtengo el objeto que voy a borrar
     	$media= new media();
     	$media->setID($id);
     	$cantidadDeBorrados=$media->Borrarmedia();
        
     	$objDelaRespuesta= new stdclass();
        $objDelaRespuesta->cantidad=$cantidadDeBorrados;

	    if($cantidadDeBorrados>0)
	    	{
                 $objDelaRespuesta->resultado="Se borro la media con ID $id";
                 
                 if (file_exists($fotoBorrar->foto)) 
                 {
                    unlink($fotoBorrar->foto);//borro la foto
                    $objDelaRespuesta->resultadoFoto = "Se borro la foto en: ".$fotoBorrar->foto;
                 }
                 
	    	}
	    	else
	    	{
	    		$objDelaRespuesta->resultado="no Borro nada!!!";
	    	}
	    $newResponse = $response->withJson($objDelaRespuesta, 200);  
      	return $newResponse;
    }
     
     public function ModificarUno($request, $response, $args) {
     	//$response->getBody()->write("<h1>Modificar  uno</h1>");
     	$ArrayDeParametros = $request->getParsedBody();
	    //var_dump($ArrayDeParametros);    	
	    $mimedia = new media();
	    $mimedia->setID($ArrayDeParametros['id']);
	    $mimedia->setColor($ArrayDeParametros['Color']);
	    $mimedia->setMarca($ArrayDeParametros['cantante']);
        $mimedia->setPrecio($ArrayDeParametros['precio']);
        $mimedia->setTalle($ArrayDeParametros['talle']);

	   	$resultado =$mimedia->ModificarmediaParametros();
	   	$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
		return $response->withJson($objDelaRespuesta, 200);		
    }


}