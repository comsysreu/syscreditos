<?php
/** Clase abstracta para el manejo de datos en tablas
* @author: MonoLabs; monolabs AT gmail DOT com
* @version 1.0 2014-20-01
*/
class SQLConection {
	/** Nombre de la tabla */
	public $table;
	/** definicion de campos de la tabla
	** @code: $instance->fields =array (	array (fieldName, class, defaultValue), ...	);
	fieldName: nombre del campo en la tabla
	class: tipo de campo (public, private, system)
	*/
	public $fields;
	/** Si es verdadero los métodos de esta clase devuelven un recurso MySQL; si no, una matriz asociativa */
	private $returnSQLResult =false;
	/**
	** $table Nombre de la tabla que se manejará por esta instancia
	*/
	public function __construct ($table){
		$this->table =$table;
		$this->fields =array ();
		}

////////		Public Methods

	/** Devuelve los registros de la tabla
	* @param $where_str: Cadena=''. Condición para filtrar resultados.
	* @param $order_str: Cadena=''. Campo sobre el que se ordenarán los registros.
	* @param $count: Entero =false . Número de registros a devolver. Si es false, toda la tabla
	* @param $start: Entero =0. Indica a partir de qué registros se devuelven datos, por default 0.
	*/
	public function getRecords ($where_str=false, $order_str=false, $count=false, $start=0){
		$where =$where_str ? "WHERE $where_str" : "";
		$order =$order_str ? "ORDER BY $order_str" : "";
		$count = $count ? "LIMIT $start, $count" : "";
		$campos =$this->getAllFields ();
		//$limit="";
		//echo $query ="SELECT $campos FROM {$this->table} $where $order $limit";
		$query ="SELECT $campos FROM {$this->table} $where $order $limit";
		return $this->returnSQLResult ? mysql_query ($query) : $this->sql ($query);		
		}
	/** Devuelve un registro de la tabla
	* @param $id: Entero. Id del registro a devolver.
	*/
	public function getRecord ($id){
		return $this->getRecords ("id=$id", false, 1);
		}
	public function insertRecord ($data){
		$campos =$this->getTableFields(false, false);
		$sysData =$this->getDefaultValues ();
		$data =stripslashes(mysql_real_escape_string(implode ("', '", $data)));
      //echo "INSERT INTO {$this->table} ($campos) VALUES ($sysData, '$data')";
		$query = "INSERT INTO {$this->table} ($campos) VALUES ($sysData, '$data')";
/*
		$log =fopen ('c:\leo\logworb.txt', 'w');
		fwrite ($log, $query);
		fclose ($log);
*/
		mysql_query ($query);
		return $this->validateOperation ();		
		}
	public function updateRecord ($id, $data){
		$campos =$this->getEditableFields (true);
		$datos =array ();
		foreach ($campos as $ind => $campo){
			$current_data =$data[$ind];
			array_push ($datos, "$campo='$current_data'");
			}
		$datos =implode (", ", $datos);
		//echo "UPDATE {$this->table} SET $datos WHERE id=$id";
		mysql_query ("UPDATE {$this->table} SET $datos WHERE id=$id");
		return $this->validateOperation ();
		
		}
	public function deleteRecord ($id){
		//echo "DELETE FROM {$this->table} WHERE id=$id";
		mysql_query ("DELETE FROM {$this->table} WHERE id=$id");
		return $this->validateOperation ();
		}
	public function showMessage ($titulo, $msg, $tipo='success') { // tipo = success, error, info, block
		echo '
		<div class="alert alert-'.$tipo.'">
		<a class="close" data-dismiss="alert">×</a>
		<strong>'.$titulo.'</strong>
		'.$msg.'
		</div>
		';	
		}

////////		Private Methods

	private function getFieldsByType ($type=''){
		$return =array ();
		$types =explode ('|', $type);
		foreach ($this->fields as $field){
			$includeField =false;
			foreach ($types as $t){
				if ($field[0] == $t){
					array_push ($return, $field);
					}
				}
			}
		return $return;
		}
	private function getNameFields ($type){
		$return =array ();
		$pos = $type=='system'? 2 : 1;
		$fields =$this->getFieldsByType ($type);
		foreach ($fields as $field){
			array_push ($return, $field[$pos]);
			}
		return $return;
		}
	private function getTitleFields ($type) {
		$return =array ();
		$pos = $type=='public'? 2 : 3;
		$fields =$this->getFieldsByType ($type);
		foreach ($fields as $field){
			array_push ($return, $field[$pos]);
			}
		return $return;
		}
	private function getEditableFields ($asArray=false){
		$return =$this->getNameFields ('public');
		return $asArray ? $return : implode (', ', $return);
		}
	public function getTableFields ($asArray=false, $wSystem=true){
		$return =$this->getNameFields ('private');
		$return = array_unique(array_merge($return,$this->getNameFields ('public'))); //nosotros pasamos por aqui :)
		if($wSystem) { $return = array_unique(array_merge($return,$this->getNameFields ('system'))); } //nosotros pasamos por aqui :)
		return $asArray ? $return : implode (', ', $return);
		}
	public function getTableTitleFields ($asArray=false){
		$return =$this->getTitleFields ('private');
		$return = array_unique(array_merge($return,$this->getTitleFields ('public'))); //nosotros pasamos por aqui :)
		$return = array_unique(array_merge($return,$this->getTitleFields ('system'))); //nosotros pasamos por aqui :)
		return $asArray ? $return : implode (', ', $return);
		}
	private function getAllFields ($asArray=false){
		$return =$this->getNameFields ('public|private|system');
		return $asArray ? $return : implode (', ', $return);
		}
	private function getDefaultValues ($asArray=false){
		$return =array ();
		$fields =$this->getFieldsByType ('private');
		foreach ($fields as $field){
			array_push ($return, $field[2]);
			}
		return $asArray ? $return : implode (', ', $return);
		}
	public function validateOperation (){
		return mysql_error()=='' ? true : false;
		}
	public function getLastId(){
		return mysql_insert_id();
		}
	public function sql ($consulta){
		//echo $consulta;
		$consQ =mysql_query ($consulta);
		$resultado =array ();
		if ($consQ){
			while ($consF =mysql_fetch_array ($consQ))
				array_push ($resultado, $consF);
			}
		return $resultado;
		}
	public function sqlTrans ($consulta){
		//echo $consulta;
 		return mysql_query ($consulta);
		}

}?>
