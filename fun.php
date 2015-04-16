<?php

function userLogin($login_type,$input,$pwd){
	$r = 0;
	if ($login_type == 1) $r = userLogin_email($input,$pwd); 
	if ($login_type == 2) $r = userLogin_mobile($input,$pwd);
	return $r;
}

function userLogin_email($input,$pwd){
	$token = 0;
	$q = mysql_escape("SELECT * FROM user WHERE email = ? AND password = ?",$input,$pwd);
	if (mysql_num_rows($q) == 1){
		$r = mysql_fetch_assoc($q);
		$token = check_user($r["u_id"],$r["status"]);
	}
	return $token;
}

function userLogin_mobile($input,$pwd){
	$token = 0;
	$q = mysql_escape("SELECT * FROM user WHERE mobile = ? AND password = ?",$input,$pwd);
	if (mysql_num_rows($q) == 1){
		$r = mysql_fetch_assoc($q);
		$token = check_user($r["u_id"],$r["status"]);
	}
	return $token;
}
 
function check_user($u_id, $status){  //this is reserved for more complicated status check and error handling. 
	$token = 0; 
	if ($status == 1) $token = token_registration($u_id);
	return $token;
}

function token_registration($u_id){ 
	$timestamp = time();
	$token = md5($u_id.$timestamp.$CONFIG["APP_ID"]);
	$token = token_regDB($u_id,$token);
	return $token;
}

function token_regDB($u_id,$token){
	
	return $token;
}

?>