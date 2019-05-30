<?php 
/* 1pts) (POST) venta de Medias (IdMedia, nombreCliente, fecha, importe) además de tener asociada una imagen (jpg, jpeg o png) a la venta. 
El nombre de la foto se conformará por el ID de la Media, el nombre del cliente y la fecha. 
La imagen se guardará en la carpeta /FotosVentas. (token: solos los encargados y los empleados). */
class ventaMedia
{
    private $_id;
    private $_idMedia;
    private $_nombreCliente;
    private $_fecha;
    private $_importe;

#region getters Y setters
    //############ Getters #################
    public function getIdMedia()
    {
        return $this->_idMedia;
    }
    public function getId()
    {
        return $this->_id;
    }
    public function getnombreCliente()
    {
        return $this->_nombreCliente;
    }
    public function getFecha()
    {
        return $this->_fecha;
    }
    public function getimporte()
    {
        return $this->_importe;
    }
    //############# Setters #######################
    public function setIdMedia($value)
    {
        $this->_idMedia = $value;
    }
    public function setId($value)
    {
        $this->_id = $value;
    }
    public function setnombreCliente($value)
    {
        $this->_nombreCliente = $value;
    }
    public function setFecha($value)
    {
        $this->_fecha = $value;
    }
    public function setImporte($value)
    {
        $this->_importe = $value;
    }

#endregion

#region Funciones
    public function insertarVenta()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into ventamedia (idMedia, nombreCliente, fecha, importe)values(:idMedia,:nombre,:fecha,:importe)");
        $consulta->bindValue(':idMedia', $this->getIdMedia(), PDO::PARAM_INT);
        $consulta->bindValue(':nombre',$this->getnombreCliente(), PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->getFecha(), PDO::PARAM_STR);
        $consulta->bindValue(':importe', $this->getImporte(),PDO::PARAM_INT);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function modificarVenta()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("update ventamedia 
        set idMedia=:idMedia,
        nombreCliente=:nombre, 
        fecha=:fecha, 
        importe=:importe
        WHERE id=:id");
        //bindeo de parametros
        $consulta->bindvalue(':id',$this->getId(),PDO::PARAM_INT);
        $consulta->bindvalue(':idMedia',$this->getIdMedia(),PDO::PARAM_INT);
        $consulta->bindValue(':nombre',$this->getnombreCliente(),PDO::PARAM_STR);
        $consulta->bindValue(':fecha',$this->getFecha(),PDO::PARAM_STR);
        $consulta->bindValue(':importe',$this->getimporte(),PDO::PARAM_INT);
        //ejecuto
        $consulta->execute();
        //retorno
        return $consulta;
        
    }
    public static function TraerUnaVenta($id)
    {
/*         $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select id, nombre as nombre, clave as clave,perfil as perfil from usuario where id = $id");
        $consulta->execute();
        $usuarioBuscado= $consulta->fetchObject('usuario');
        return $usuarioBuscado;		 */
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select id, idMedia, nombreCliente, fecha, importe from ventaMedia where id=:id");
        $consulta->bindvalue(':id',$id ,PDO::PARAM_INT);
        $consulta->execute();
        $ventaBuscada = $consulta->fetchObject('ventaMedia');
        return $ventaBuscada;

    }

    public function BorrarUnaVenta()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("
            delete
            from ventamedia
            where id=:id");
        $consulta->bindValue(':id',$this->getId(),PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->rowCount();
    }
#end region
}


?>