<?php

require_once "AutentificadorJWT.php";
class MWparaAutentificar
{
 /**
   * @api {any} /MWparaAutenticar/  Verificar Usuario
   * @apiVersion 0.1.0
   * @apiName VerificarUsuario
   * @apiGroup MIDDLEWARE
   * @apiDescription  Por medio de este MiddleWare verifico las credeciales antes de ingresar al correspondiente metodo 
   *
   * @apiParam {ServerRequestInterface} request  El objeto REQUEST.
 * @apiParam {ResponseInterface} response El objeto RESPONSE.
 * @apiParam {Callable} next  The next middleware callable.
   *
   * @apiExample Como usarlo:
   *    ->add(\MWparaAutenticar::class . ':VerificarUsuario')
   */
	public function VerificarUsuario($request, $response, $next) {
         
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta="";
	   
/*  		if($request->isGet())
		{
		 $response->getBody()->write('<p>NO necesita credenciales para los get </p>');
		 $response = $next($request, $response);
		}
		else
		{  */
			//$response->getBody()->write('<p>verifico credenciales</p>');

			//perfil=Profesor (GET, POST)
			//$datos = array('usuario' => 'rogelio@agua.com','perfil' => 'profe', 'alias' => "PinkBoy");
			
			//perfil=Administrador(todos)
			//$datos = array('usuario' => 'rogelio@agua.com','perfil' => 'Administrador', 'alias' => "PinkBoy");
			//tomo el token del header
			
				$arrayConToken = $request->getHeader('token');
				$token=$arrayConToken[0];			
			
			//var_dump($token);
			$objDelaRespuesta->esValido=true; 
			try 
			{
				//$token="";
				AutentificadorJWT::verificarToken($token);
				$objDelaRespuesta->esValido=true;      
			}
			catch (Exception $e) {      
				//guardar en un log
				$objDelaRespuesta->excepcion=$e->getMessage();
				$objDelaRespuesta->esValido=false;     
			}

			if($objDelaRespuesta->esValido)
			{						
				if($request->isGet())
				{
					// el get sirve para todos los logeados			    
					$response = $next($request, $response);
				}
				else if($request->isPost())
				{
					//al post pueden entrar solo encargados y los empleados
					$payload=AutentificadorJWT::ObtenerData($token);
					if ($payload->perfil=="encargado" || $payload->perfil=="empleado") 
					{
						$response = $next($request, $response);
					}
					else
					{
						$objDelaRespuesta->respuesta="Solo Encargados y empleados";
					}
					
				}
				else if($request->isDelete())
				{
					//solo el dueño tiene acceso al delete
					$payload=AutentificadorJWT::ObtenerData($token);
					//var_dump($payload);
					// DELETE,PUT y DELETE sirve para todos los logeados y admin
					if($payload->perfil=="dueño")
					{
						$response = $next($request, $response);
					}		           	
					else
					{	
						$objDelaRespuesta->respuesta="Solo Dueño";
					}
				}
				else if($request->isPut())
				{	//solo el encargado va a tener acceso
					$payload=AutentificadorJWT::ObtenerData($token);
					if ($payload->perfil=="encargado") 
					{
						$response = $next($request, $response);
					} 
					else 
					{
						$objDelaRespuesta->respuesta="Solo Encargados";
					}
					
				}		          
			}    
			else
			{
				//   $response->getBody()->write('<p>no tenes habilitado el ingreso</p>');
				$objDelaRespuesta->respuesta="Solo usuarios registrados";
				$objDelaRespuesta->elToken=$token;

			}  
		//} //fin del else		  
		if($objDelaRespuesta->respuesta!="")
		{
			$nueva=$response->withJson($objDelaRespuesta, 401);  
			return $nueva;
		}
		  
		 //$response->getBody()->write('<p>vuelvo del verificador de credenciales</p>');
		 return $response;   
	}
}