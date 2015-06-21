<?php  
	header('content-type:text/html;charset=utf-8');
	require_once "../fun.php";
	$token=$_POST['token'];

	$id=token_check($token);
	if(!$id){
		json(0,"token_false","");
		exit;
	}



	
	$sql  = 'SELECT username,gender,note,avatar FROM user WHERE u_id = ?';
	$pdo=pdo_con();
	$smt=$pdo->prepare($sql);
	$smt->bindParam(1,$id,PDO::PARAM_STR);	//id
	if ($smt->execute()) {
		$row=$smt->fetch();
		$name=$row['username'];
		$sex=$row['gender'];
		$sign=$row['note'];
		$photo=$row['avatar'];
	}else{
		json(0,"fail","");
		exit;
	}

		

	

	$user=array(
		'username' => $name,
		'gender'  => $sex,
		'note'  =>$sign,
		'avatar' =>$photo
		);

json(1,"success",$user);