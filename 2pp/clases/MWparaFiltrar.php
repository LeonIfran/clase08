<?php 
class MWparaFiltrar
{
    public function FiltrarDatos($request, $response, $next)
    {
        $request = $next($request, $response);//tomo los datos que devolvio el listado
        $json = $request->getBody();//tomo el body de la variable anterior
        $data = json_decode($json, true);//decodifico de json a 

        $arrayComparar = array('id'=>0,'precio'=>0);//este array tiene las llaves que deseo remover
        //echo var_dump($arrayComparar);
        $arrayRetorno = array();//este array va a ser el retornado

        foreach ($data as $key => $value) 
        {
            //echo var_dump(array_diff_key($value,$arrayComparar));
            $arrayFiltrado = array_diff_key($value,$arrayComparar);//se le quitan tanto las llaves como sus valores a id y precio
            array_push($arrayRetorno,$arrayFiltrado);//envio el array filtrado al array de retorno
        }
        //echo var_dump($data);
        //echo var_dump($arrayRetorno);
        return $response->withJson($arrayRetorno,200);
    }
}


?>