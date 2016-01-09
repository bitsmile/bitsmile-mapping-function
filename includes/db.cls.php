<?
/*
 * Created on Sep 8, 2015
 *
 * sakr
 */
    
class dbclass {	
	var $CONN;
	function dbclass() { //constructor
		$conn = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);	
		mysql_set_charset('utf8');
		if(!$conn) 
		{	$this->error("Connection attempt failed");		}
		if(!mysql_select_db(DB_DATABASE,$conn)) 
		{	$this->error("Database Selection failed");		}
		$this->CONN = $conn;
		return true;
	}
	
	function close(){
		$conn = $this->CONN ;
		$close = mysql_close($conn);
		if(!$close){
			$this->error("Close Connection Failed");	}
		return true;
	}
	
	function error($text) {
		$no = mysql_errno();
		$msg = mysql_error();
		echo "<hr><font face=verdana size=2>";
		echo "<b>Custom Message :</b> $text<br><br>";
		echo "<b>Error Number :</b> $no<br><br>";
		echo "<b>Error Message	:</b> $msg<br><br>";
		echo "<hr></font>";
		exit;
	}
	
	function select ($sql=""){
		
		if(empty($sql)) {return false; }
		
		if(empty($this->CONN)) { return false; }
		
		$conn = $this->CONN;
		$results = @mysql_query($sql,$conn) or die(mysql_error());
		
		if((!$results) or empty($results))	{	return false;		}
		$count = 0;
		$data  = array();
		while ( $row = mysql_fetch_array($results))	{	
			$data[$count] = $row;
			$count++;		}
		mysql_free_result($results);
		return $data;
	}
	
	function affected($sql="")	{
		if(empty($sql)) { return false; }
		if(!eregi("^select",$sql)){
			echo "Wrong Query<hr>$sql<p>";
			return false;		}
		if(empty($this->CONN)) 	{ 	return false; 	}
		
		$conn = $this->CONN;
		$results = @mysql_query($sql,$conn);
		if( (!$results) or (empty($results)) ) 
		{	return false;	}
		$tot=0;
		$tot=mysql_affected_rows();
		return $tot;
	}
	
	function insert ($sql=""){
		if(empty($sql)) { return false; }
		if(!eregi("^insert",$sql)){	return false;		}
		if(empty($this->CONN)){	return false;		}
		$conn = $this->CONN;			
		$results = @mysql_query($sql,$conn);			
		if(!$results){
			$this->error("Insert Operation Failed..<hr>$sql<hr>");
			return false;		}
		$id = mysql_insert_id();
		return $id;
	}
	
	function edit($sql="")	{
		if(empty($sql)) { 	return false; 		}
		if(!eregi("^update",$sql)){	return false;		}
		if(empty($this->CONN)){	return false;		}
		$conn = $this->CONN;
		$results = @mysql_query($sql,$conn);
		$rows = 0;
		$rows = @mysql_affected_rows();
		return $rows;
	}
	
	function sql_query($sql="")	{	
		
		if(empty($sql)) { return false; }
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn) or $this->error("Something wrong in query<hr>$sql<hr>");
		
		if(!$results){
			$this->error("Query went bad ! <hr>$sql<hr>");
			return false;		}		
		if(!eregi("^select",$sql)){return true; 		}
		else {
			$count = 0;
			$data = array();
			while ( $row = mysql_fetch_array($results))
			{	$data[$count] = $row;
			$count++;				}
			mysql_free_result($results);
			return $data;
		}
	}	
	
	function adder($sql="")	{	
		if(empty($sql)) 
		{ 	return false; 	}
		if(empty($this->CONN))
		{	return false;	}
		
		$conn = $this->CONN;
		$results = @mysql_query($sql,$conn);
		
		if(!$results)
			$id = "";  
		else
			$id = mysql_insert_id();
		return $id;
	}
	
	/**
	 * @return array
	 * @param string $tablename the tablename
	 * @desc check if a table with the given name exists in DB
	 */
	function table_exists($tablename)
		{
		$conn = $this->CONN ;
		
		if(empty($conn)) { return false; }
		
		$results = mysql_list_tables(DB_DATABASE) or die("Could not access Table List...<hr>" . mysql_error());
		
		if(!$results){
			
			$message = "Query went bad!";
			//mysql_close($conn);
			die($message);
			return false;
			
		}else{
			
			$count = 0;
			$data = array();
			while ( $row = mysql_fetch_array($results)) {
				if ($row[0]==$tablename) {
					return true;
					//	mysql_close($conn);
					exit;
				}
			}
			mysql_free_result($results);
			//mysql_close($conn);
			return false;
		}
		}
	
	function table_list()
		{
		$conn = $this->CONN ;
		
		if(empty($conn)) { return false; }
		
		$results = mysql_list_tables(DB_DATABASE) or die("Could not access Table List...<hr>" . mysql_error());
		
		if(!$results){
			
			$message = "Query went bad!";
			//mysql_close($conn);
			die($message);
			return false;
			
		}else{
			
			$count = 0;
			$data = array();
			while ( $row = mysql_fetch_array($results)) {
				$data[]=$row;
			}
		}
		mysql_free_result($results);
		//mysql_close($conn);
		return $data;
		}
	function field_list($table)
		{
		$conn = $this->CONN ;
		
		if(empty($conn)) { return false; }
		$field_names = array();
		$res = mysql_query("SHOW COLUMNS FROM $table");
		for($i=0;$i<mysql_num_rows($res);$i++){
			array_push($field_names,mysql_result($res, $i));
		} 
		return $field_names;
		}
	
	
	function extraqueries($sql="")	{	
		
		if(empty($sql)) { return false; }
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn) or $this->error("Something wrong in query<hr>$sql<hr>");
		
		if(!$results){
			$this->error("Query went bad ! <hr>$sql<hr>");
			return false;		}		
		else {
			$count = 0;
			$data = array();
			while ( $row = mysql_fetch_array($results))
			{	$data[$count] = $row;
			$count++;				}
			mysql_free_result($results);
			return $data;
		}
	}	
	
	function db_query($sql="")	{
		if(empty($sql)) { return false; }
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn) or $this->error("Something went wrong in the query<hr>$sql<hr>");
		if(!$results){
			$this->error("Query went bad ! <hr>$sql<hr>");
			return false;	}
		else
			return	$results;
	}
	
	function db_fetch_row($result) {
		return mysql_fetch_row($result);
	}
	
	function db_free_result($result) {
		@mysql_free_result($result);
	}
	
	function db_num_rows($result) {
		return mysql_num_rows($result);
	}
	
	function db_num_fields($result) {
		return mysql_num_fields($result);
	}
	
	function db_field_name($result) {
		return mysql_field_name($result);
	}
	
	function db_field_type($result) {
		return mysql_field_type($result);
	}
	
} 


?>
