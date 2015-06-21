<?php
require_once "include/config.php";

function userLogin($input,$pwd){
	$r = 0;
	$login_type=check_input($input);
	
	if ($login_type == 1) $r = userLogin_email($input,$pwd); 
	if ($login_type == 2) $r = userLogin_mobile($input,$pwd);
	return $r;
}

function userLogin_email($input,$pwd){
	$token = 0;
	$sql="select * from user where email=? and password=?";
	$pdo=pdo_con();//接收pdo连接对象
	$smt=$pdo->prepare($sql);
	$smt->bindParam(1,$input,PDO::PARAM_STR);//设置参数
	$smt->bindParam(2,$pwd,PDO::PARAM_STR);
	$smt->execute(); 
	if ($smt->rowCount() == 1){
		$r = $smt->fetch();//返回结果集
		$token = check_user($r["u_id"],$r["status"]);
	}
	return $token;
	
}

function userLogin_mobile($input,$pwd){
	$token = 0;
	$sql='select * from user where mobile=? and password=?';
	$pdo=pdo_con();//接收pdo连接对象
	$smt=$pdo->prepare($sql);
	$smt->bindParam(1,$input,PDO::PARAM_STR);//设置sql参数
	$smt->bindParam(2,$pwd,PDO::PARAM_STR);
	$smt->execute(); 
	if ($smt->rowCount() == 1){
		$r = $smt->fetch();//返回结果集
		$token = check_user($r["u_id"],$r["status"]);
	}
	

	return $token;
}

// 正则匹配邮箱和手机号
function check_input($input){
	$type=0;
	$email = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i"; 
	$moblie= "/^13[0-9]{1}[0-9]{8}$|^15[0-9]{1}[0-9]{8}$|^18[0-9]{1}[0-9]{8}$/";
	if(preg_match($email, $input)){$type=1;}
	if(preg_match($moblie, $input)){$type=2;}

	return $type;
}




function check_user($u_id, $status){  //this is reserved for more complicated status check and error handling. 
	$token = 0; 
	$device = 1;    //  cell phone only for now
	$result = 0;

	if ($status == 1){
		// DELETE any existing session / token which is valid for this user on same type of device
		$sql = 'DELETE FROM token WHERE u_id = ? AND device = ?';
		$pdo = pdo_con();//接收pdo连接对象
		$smt = $pdo->prepare($sql);
		$smt->bindParam(1,$u_id,PDO::PARAM_STR);	//user id
		$smt->bindParam(2,$device,PDO::PARAM_STR);	//which device
		$smt->execute(); 
		$token = token_registration($u_id);
	}
	return $token;
}
  
function token_registration($u_id){   // generate the token and do the registration, return the token which is succesfully registered in DB, or 0 for any failure
	$timestamp = time();
	$token = md5($u_id.$timestamp);
	$r = token_regDB($u_id,$token);
	return $token;

}

function token_regDB($u_id,$token){   // token registration in DB - initial the Token, return the token which is succesfully registered in DB, or 0 for any failure
	$r      = 0;  
	$device = 1; // cell phone
	$status = 1; // active

	$sql= "INSERT INTO token (token, u_id, status, device) VALUES (?, ? , ? , ?)";
	$pdo=pdo_con();//接收pdo连接对象
	$smt=$pdo->prepare($sql);
	$smt->bindParam(1,$token,PDO::PARAM_STR);	//user's token
	$smt->bindParam(2,$u_id,PDO::PARAM_STR);	//user id
	$smt->bindParam(3,$status,PDO::PARAM_STR);	//set the status of the token
	$smt->bindParam(4,$device,PDO::PARAM_STR);	//On which device the token will be used
	$smt->execute(); 
	if (intval($pdo->lastInsertId()) != 0) $r = $token; // check the last insert id to see if it's successfully done or not
	return $r;
}

function token_check($token){          //check the token is still valid or not, if it's valid, then it will be automatically renewed and return the token, if it's not a valid token, then return 0
	$result = false;  
	$device = 1;  // only cell phone for now  
	$status = 0;

	$sql    = 'SELECT * FROM token WHERE token = ? AND device = ?';

	$pdo=pdo_con();
	$smt=$pdo->prepare($sql);
	$smt->bindParam(1,$token,PDO::PARAM_STR);	//token for validation
	$smt->bindParam(2,$device,PDO::PARAM_STR);	//token on which device

	$smt->execute(); 
	if ($smt->rowCount() == 1){			  // only 1 session is valid on the same type of device for one user
		$r = $smt->fetch();
		$t_id = $r["id"];                 // accurate token ID
		$u_id = $r["u_id"];				  // user id
		$status = $r["status"];           // token status
		//echo "aaa".$r["status"]; 
		if ($status == 1){                // validation is PASSED , the token is still active - the reason why STATUS = 1 is not in the SQL, is because we can add more logic here in the future.
			$r = token_update($t_id);	  // renew the token 
			$result = $u_id;            
		}
		
	}
	return $result;
}


function token_update($t_id){
	date_default_timezone_set('PRC'); 
	$now = date("Y-m-d H:i:s");

	$sql = 'UPDATE token set last_update = ? WHERE id = ?';

	$pdo=pdo_con();
	$smt=$pdo->prepare($sql);
	$smt->bindParam(1,$now,PDO::PARAM_STR);	     // current timestamp for renew
	$smt->bindParam(2,$t_id,PDO::PARAM_STR);	// token record id
	$smt->execute(); 
	return $smt->rowCount();
}



//使用pdo连接数据库
function pdo_con(){
	global $CONFIG;
	try{
	$pdo=new PDO("mysql:dbname={$CONFIG['mysql_db']};host={$CONFIG['mysql_host']}",$CONFIG['mysql_user'],$CONFIG['mysql_pass']);
	}
	catch (PDOException $e) {  
	   // echo "Failed to get DB handle: " . $e->getMessage() . "\n";  
		json(0,"db_failed","");
	   exit;  
	 }  

	//设置返回结果集为关联数组
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,true);
	$pdo->exec('set names utf8');
	return $pdo;
}


function getNowTime(){
	date_default_timezone_set('PRC'); 
	$time=date("Y-m-d H:i:s") ;
	return $time;
}

function picUpload($file,$type,$dir){
		$rand=time().mt_rand();
	 	$filename=$rand.'.'.$type;
		$allow=array('png','jpg','gif','jpeg');
		$size=10*1024*1024;
		$fsize=filesize($pic);
		$dfile=$dir.'/'.$filename;
		if(in_array($type,$allow)){
			if($fsize<=$size){
				if($a = file_put_contents($dfile, $file)){
					$result= $filename;
					return $result;
				}
				else{
					json(0,"uploadError","");
					
					return false;
				}
			}else{
					json(0,"allowError","");
				
					return false;
			}
			
		}else{
					json(0,"typeError","");
			
					return false;
		}	
}

function code ($length){
	$arr=array_merge(range(0,9),range('a','z'));
	shuffle($arr);
	$str=join('',array_slice($arr,0,$length));
	return $str;
}

function check_code($timestamp,$str,$code){
		$result=md5($timestamp.$str);
		if($code!=$result){
			json(0,"code_false","");
			exit();
		}
	}
function json($success, $message = '', $data= array()) {
		
		if(!is_numeric($success)) {
			return '';
		}

		$result = array(
			'success' => $success,
			'message' => $message,
			'result' => $data
		);

		echo json_encode($result);
		exit;
	}
