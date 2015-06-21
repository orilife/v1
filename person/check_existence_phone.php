<?php  
	header('content-type:text/html;charset=utf-8');
	require_once "../fun.php";


	$input=$_POST['input'];

	$timestamp=$_POST['timestamp'];
	$code=$_POST['code'];

	$str="orilife".$input;
	check_code($timestamp,$str,$code);

	function selectIdByPhone($input){
		$token=false;
		$sql  = 'SELECT u_id FROM user WHERE mobile = ?';
		$pdo=pdo_con();
		$smt=$pdo->prepare($sql);
		$smt->bindParam(1,$input,PDO::PARAM_STR);	//id
		$smt->execute();
		$row=$smt->fetch();
		$id= $row['u_id'];
		if($id){
		$sql  = "SELECT token FROM token WHERE u_id={$id}";
		$smt=$pdo->prepare($sql);
		$smt->execute();
		$row=$smt->fetch();
		$token=$row['token'];
		}return $token;

	}
	$token=selectIdByPhone($input);
	if($token){
		$result= array('token' => $token );
		json(1,"success",$result);
	}else{
		json(0,"fail","");
	}