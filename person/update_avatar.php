<?php
	header('content-type:text/html;charset=utf-8');
	include "../fun.php";


	$pics = $_POST['avatar'];//获取前端传来的编码过后的图片
	$token=$_POST['token'];
	
	$id=token_check($token);
	if(!$id){
		json(0,"token_false","");
		exit;
	}

	$time=getNowTime();



	$fenge = explode(",",$pics);//分隔得到图片的格式png，gif，jpg等
	$types = $fenge[0];
	$imgData = $fenge[1];
	list($type3, $type2) = explode("/", $types);
	list($type, $type4) = explode(";", $type2);	  

	$pic = base64_decode($imgData);//图片解码

	$result=picUpload($pic,$type,'../public/avatar');
	if($result){
		updatePic($id,$result);
	}



function updatePic($id,$filename){
					
	del_old_pic($id);
	$sql="update user set avatar=? ,last_mod_time=? ,	last_mod_who=? where u_id=?";
	$pdo=pdo_con();//接收pdo连接对象
	$smt=$pdo->prepare($sql);
	$smt->bindParam(1,$filename,PDO::PARAM_STR);//设置sql参数
	$smt->bindParam(2,$time,PDO::PARAM_STR);//设置sql参数
	$smt->bindParam(3,$who_mod,PDO::PARAM_STR);//设置sql参数
	$smt->bindParam(4,$id,PDO::PARAM_STR);//设置sql参数
	if($smt->execute()){
		json(1,"success","");
	}else{
		json(0,"fail","");
	}
}
function del_old_pic($id){
	
	$sql  = 'SELECT avatar FROM user WHERE u_id = ?';
	$pdo=pdo_con();
	$smt=$pdo->prepare($sql);
	$smt->bindParam(1,$id,PDO::PARAM_STR);	//id
	if ($smt->execute()) 
	{
			$row=$smt->fetch();
			if($row['avatar']){
				unlink('../public/avatar/'.$row['avatar']);
			}
	}
	 

}