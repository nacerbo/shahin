<?php
/*
	Mysql connect & querys
*/

class sn_SQL{

	public  $connect;
	private $query_result;
	private $result_array;
	private $result_object;
	private $result_num;

	function sn_SQL(){
		$this->connect= @mysqli_connect(BR_HOST,BR_USER_NAME,BR_PASSWORD,BR_DB_NAME);

		$this->query("SET character_set_connection = utf8"); 
		$this->query("SET character_set_client = utf8"); 
		$this->query("SET character_set_results = utf8");
		$this->query("SET COLLATION_CONNECTION = 'utf8';");
		$this->query("SET NAMES utf8");

		return (bool) $this->connect;
	}

	function __constract(){
		$this->connect= @mysqli_connect(BR_HOST,BR_USER_NAME,BR_PASSWORD,BR_DB_NAME);
		
		$this->query("SET character_set_connection = utf8"); 
		$this->query("SET character_set_client = utf8"); 
		$this->query("SET character_set_results = utf8");
		$this->query("SET COLLATION_CONNECTION = 'utf8';");
		$this->query("SET NAMES utf8");

		return $this->connect;
	}


	function query($sql=""){
		
		if($sql=="") return false;

		$this->query_result=  @mysqli_query($this->connect,$sql);

		return $this->query_result;
	}

	function query_array(){
		$this->result_array =  @mysqli_fetch_array($this->query_result);
		return $this->result_array;
	}

	function query_object(){
		$this->result_object= @mysqli_fetch_object($this->query_result);
		return $this->result_object;
	}

	function query_num(){
		$this->result_num=  @mysqli_num_rows($this->query_result);
		return $this->result_num;
	}

	public function sn_close(){
		return  mysql_close($this->connect);
	}
}

$sn_sql = new sn_SQL();