<?php
/*
 * Created on Jan 8, 2016
 *
 * sakr
 */
function pp($arr){
	echo nl2br(str_replace(' ','&nbsp;',print_r($arr,true)));
}
function db_input($string) {	
	if (function_exists('mysql_real_escape_string')) {		
		return mysql_real_escape_string($string);		
	}
	elseif (function_exists('mysql_escape_string')) {	
		return mysql_escape_string($string);		
	}	
	return addslashes($string);	
}
function db_perform($table, $data, $action = 'insert', $parameters = '') {
	$db = new dbclass;
	reset($data);	
	if ($action == 'insert') {		
		$query = 'insert into ' . $table . ' (';		
		while (list ($columns,) = each($data)) {			
			$query .= $columns . ', ';			
		}		
		$query = substr($query, 0, -2) . ') values (';		
		reset($data);		
		while (list (, $value) = each($data)) {			
			switch ((string) $value) {			
				case 'now()' :					
					$query .= 'now(), ';					
					break;					
				case 'null' :					
					$query .= 'null, ';					
					break;					
				default :					
					$query .= '\'' . db_input($value) . '\', ';				
				break;				
			}			
		}		
		$query = substr($query, 0, -2) . ')';		
	}
	elseif ($action == 'update') {		
		$query = 'update ' . $table . ' set ';		
		while (list ($columns, $value) = each($data)) {			
			switch ((string) $value) {			
				case 'now()' :					
					$query .= $columns . ' = now(), ';					
					break;					
				case 'null' :					
					$query .= $columns .= ' = null, ';					
					break;					
				default :					
					$query .= $columns . ' = \'' . db_input($value) . '\', ';				
				break;				
			}			
		}		
		$query = substr($query, 0, -2) . ' where ' . $parameters;		
	}
	elseif ($action == 'delete') {		
		$query = 'delete from ' . $table;
		$query .= ' where ' . $parameters;		
	}
	$db->sql_query($query);
	$iid = mysql_insert_id($db->CONN);
	$db->close();
	return $iid;
}
function get_all_get_params($exclude_array = '') {
	global $_GET;
	
	if (!is_array($exclude_array)) $exclude_array = array();
	//$exclude_array[]='page';
	
	$get_url = '';
	if (is_array($_GET) && (sizeof($_GET) > 0)) {
		reset($_GET);
		while (list($key, $value) = each($_GET)) {
			if ( (strlen($value) > 0) &&  (!in_array($key, $exclude_array))) {
				$get_url .= $key . '=' . rawurlencode(stripslashes($value)) . '&';
			}
		}
	}
	
	return $get_url;
}
function output_string($string, $translate = false, $protected = false) {
	if ($protected == true) {
		return htmlspecialchars($string);
	} else {
		if ($translate == false) {
			return parse_input_field_data($string, array (
				'"' => '&quot;'
			));
		} else {
			return parse_input_field_data($string, $translate);
		}
	}
}
function parse_input_field_data($data, $parse) {
	return strtr(trim($data), $parse);
}
function not_null($value) {
	if (is_array($value)) {
		if (sizeof($value) > 0) {
			return true;
		} else {
			return false;
		}
	} else {
		if ((is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) {
			return true;
		} else {
			return false;
		}
	}
}
function db_prepare_input($string) {
	if (is_string($string)) {
		return trim(sanitize_string(stripslashes($string)));
	}
	elseif (is_array($string)) {
		reset($string);
		while (list ($key, $value) = each($string)) {
			$string[$key] = db_prepare_input($value);
		}
		return $string;
	} else {
		return $string;
	}
}
function sanitize_string($string) {
	$string = ereg_replace(' +', ' ', trim($string));
	return preg_replace("/[<>]/", '_', $string);
}
if ( !function_exists( 'hex2bin' ) ) {
	function hex2bin( $str ) {
		$sbin = "";
		$len = strlen( $str );
		for ( $i = 0; $i < $len; $i += 2 ) {
			$sbin .= pack( "H*", substr( $str, $i, 2 ) );
		}
		
		return $sbin;
	}
}
function privtoWIF($privKey){
	$wif1='99'.$privKey;
	$wif2=hash('sha256', pack('H*',$wif1), false);
	$wif3=hash('sha256', pack('H*',$wif2), false);
	$wif4=substr($wif3,0,8);
	$wif5=$wif1.$wif4;
	$wif6=encodeBase58($wif5);
	return $wif6;
}
function WIFtoPriv($WIF){
	$uWif1=decodeBase58($WIF);
	$uWif2=substr($uWif1,2,strlen($uWif1)-12);
	return $uWif2;
}
function encodeBase58($hex) {
	$base58chars = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
	if (strlen($hex) % 2 != 0) {
		die("encodeBase58: uneven number of hex characters");
	}
	$orighex = $hex;
	
	$hex = decodeHex($hex);
	$return = "";
	while (gmp_cmp($hex, 0) > 0) {
		$dv = gmp_strval(gmp_div_q($hex, "58", 0));
		$rem = gmp_strval(gmp_mod($hex, "58"));
		$hex = $dv;
		$return = $return . $base58chars[$rem];
	}
	$return = strrev($return);
	
	for ($i = 0; $i < strlen($orighex) && substr($orighex, $i, 2) == "00"; $i += 2) {
		$return = "1" . $return;
	}
	return $return;
}

function decodeHex($hex) {
	$hexchars = "0123456789ABCDEF";
	$hex = strtoupper($hex);
	$return = "0";
	for ($i = 0; $i < strlen($hex); $i++) {
		$current = (string) strpos($hexchars, $hex[$i]);
		$return = gmp_strval(gmp_mul($return, "16"));
		$return = gmp_strval(gmp_add($return, $current));
	}
	return $return;
}
function decodeBase58($base58)
	{
	$origbase58=$base58;       
	$chars="123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
	$return="0";
	for($i=0;$i<strlen($base58);$i++)
	{
		$current=(string)strpos($chars,$base58[$i]);
		$return=gmp_strval(gmp_mul($return,"58"));
		$return=gmp_strval(gmp_add($return,$current));
	}       
	$return=encodeHex($return);
	for($i=0;$i<strlen($origbase58)&&$origbase58[$i]=="1";$i++)
	{
		$return="00".$return;
	}      
	if(strlen($return)%2!=0)
	{
		$return="0".$return;
	}       
	return $return;
	}
function encodeHex($dec)
	{
	$chars="0123456789ABCDEF";
	$return="";
	while (gmp_cmp($dec, 0) > 0)
	{
		$dv=gmp_strval(gmp_div_q($dec,"16",0));
		$rem=gmp_strval(gmp_mod($dec,"16"));
		$dec=$dv;
		$return=$return.$chars[$rem];
	}
	return strrev($return);
	}
function createDonation($amount,$name,$email){
	$ret=array();
	$resp=array();
	$db = new dbclass;
	$amount=db_input($amount);
	$name=db_input($name);
	$email=db_input($email);
	$db->close();
	$bitcoinECDSA = new BitcoinECDSA();	
	$privkey=bin2hex(openssl_random_pseudo_bytes(32));
    $bitcoinECDSA->setPrivateKey($privkey);
    $ret['pubKey']=$bitcoinECDSA->getAddress();
    $ret['privWIF']=privtoWIF($bitcoinECDSA->getPrivateKey().'01');
    $ret['amount']=$amount;
    $ret['name']=$name;
    $ret['email']=$email;
    $ret['success']=1;
    $connection = Tivoka\Client::connect('http://'.RPC_USERNAME.':'.RPC_PASSWORD.'@'.RPC_HOST.':'.RPC_PORT);
    try{
    $request = $connection->sendRequest('sendtoaddress', array($ret['pubKey'],floatval($amount)));
	$resp=json_decode($request->response,true);
	if(isset($resp['error'])){
		$ret['success']=0;
		return $ret;
	}else{
		$ret['txid']=$resp['result'];
		$dbret=-1;	
		$data_array = array (
			'id'=>'',
			'pubkey'=>$ret['pubKey'],
			'privkey'=>$ret['privWIF'],
			'amount'=>$ret['amount'],
			'txid'=>$ret['txid'],
			'name'=>$ret['name'],
			'email'=>$ret['email'],
			'dateadded'=>'now()',
			'lastMod'=>'now()'
		);
		$dbret=db_perform('donations', $data_array);
	}
    }catch(Exception $e){
    $ret['success']=0;	
    }    
    return $ret;
}	
?>
