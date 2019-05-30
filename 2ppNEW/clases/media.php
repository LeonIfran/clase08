<?php
class media
{
	private $_id;
 	private $_color;
  	private $_marca;
  	private $_precio;
	private $_talle;
	private $_foto;

	#region setters y getters
	public function getID()
	{
		return $this->_id;
	}
	public function setID($value)
	{
		$this->_id=$value;
	}

	public function getColor()
	{
		return $this->_color;
	}
	public function setColor($value)
	{
		$this->_color=$value;
	}

	public function getMarca()
	{
		return $this->_marca;
	}
	public function setMarca($value)
	{
		$this->_marca=$value;
	}

	public function getPrecio()
	{
		return $this->_precio;
	}
	public function setPrecio($value)
	{
		$this->_precio=$value;
	}

	public function getTalle()
	{
		return $this->_talle;
	}
	public function setTalle($value)
	{
		$this->_talle=$value;
	}

	public function getFoto()
	{
		return $this->_foto;
	}
	public function setFoto($value)
	{
		$this->_foto=$value;
	}
	#endregion

  	public function BorrarMedia()
	 {
	 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				delete 
				from medias 				
				WHERE id=:id");	
				$consulta->bindValue(':id',$this->getID(), PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();
	 }

	public function Modificarmedia()
	 {

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update medias 
				set titel='$this->color',
				interpret='$this->marca',
				jahr='$this->precio'
				WHERE id='$this->id'");
			return $consulta->execute();

	 }

	  public function ModificarmediaParametros()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update medias 
				set color=:color,
				marca=:marca,
				precio=:precio
				WHERE id=:id");
			$consulta->bindValue(':id',$this->getID(), PDO::PARAM_INT);
			$consulta->bindValue(':color',$this->getColor(), PDO::PARAM_INT);
			$consulta->bindValue(':precio', $this->getPrecio(), PDO::PARAM_STR);
			$consulta->bindValue(':marca', $this->getMarca(), PDO::PARAM_STR);
			return $consulta->execute();
	 }

	 public function InsertarElmediaParametros()
	 {
				$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into medias (color, marca, precio,talle, foto)values(:color, :marca,:precio, :talle, :foto)");
				$consulta->bindValue(':color',$this->getColor(), PDO::PARAM_STR);
				$consulta->bindValue(':precio', $this->getPrecio(), PDO::PARAM_INT);
				$consulta->bindValue(':marca', $this->getMarca(), PDO::PARAM_STR);
				$consulta->bindValue(':talle',$this->getTalle(),PDO::PARAM_STR);
				$consulta->bindValue(':foto',$this->getFoto(),PDO::PARAM_STR);
				$consulta->execute();		
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
	 }
	 public function Guardarmedia()
	 {

	 	if($this->id>0)
	 		{
	 			$this->ModificarmediaParametros();
	 		}else {
	 			$this->InsertarElmediaParametros();
	 		}

	 }


  	public static function TraerTodoLosmedias()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,color,marca,precio, talle, foto from medias");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "media");		
	}

	public static function TraerUnmedia($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from medias where id = $id");
			$consulta->execute();
			$mediaBuscado= $consulta->fetchObject('media');
			return $mediaBuscado;	
	}

	public function mostrarDatos()
	{
	  	return "Metodo mostar:".$this->color."  ".$this->marca."  ".$this->precio;
	}

}