<?php  
	header('content-type:text/html;charset=utf-8');
	require_once "../fun.php";

	$token=$_POST['token'];

	$id=token_check($token);

	if(!$id){
		json(0,"token_false","");
		exit;
	}
	$pageSize=intval($_POST['pageSize']);
	$next=$_POST['next'];
	$refresh=$_POST['refresh'];
// $next='18';//拇指向上拉取内容
// // $refresh=10;//拇指向下拉取最新状态
// $pageSize=5;


//获取作品表信息
// 拇指向下滑取数据
if(isset($next)){
	$next=intval($next);
	$productionsql  = 'SELECT production_id,name,pic,u_id,favour_num,is_delete 
	FROM production WHERE production_id<?   AND is_delete=0   ORDER BY production_id
		DESC LIMIT 0,? ' ;



	$pdo=pdo_con();	
	$smt=$pdo->prepare($productionsql);
	$smt->bindParam(1,$next,PDO::PARAM_INT);
	$smt->bindParam(2,$pageSize,PDO::PARAM_INT);
	$smt->execute();
	$production_rows=$smt->fetchAll();
}
elseif(isset($refresh)){
	$refresh=intval($refresh);
	$productionsql  = 'SELECT production_id,name,pic,u_id,favour_num,is_delete 
	FROM production WHERE production_id>?   AND is_delete=0   
	ORDER BY production_id DESC LIMIT 0,? ' ;

	$pdo=pdo_con();
	$smt=$pdo->prepare($productionsql);
	$smt->bindParam(1,$refresh,PDO::PARAM_INT);
	$smt->bindParam(2,$pageSize,PDO::PARAM_INT);
	$smt->execute();
	$production_rows=$smt->fetchAll();
}



	foreach ($production_rows as $key =>  $value) {
		


		 	$userid=$value['u_id'];



			$production_id=$value['production_id'];

			$production_rows[$key]['is_favour']=null;
			// 查询创作者的相关个人信息
			$usersql  = "SELECT username,avatar FROM user WHERE u_id='{$userid}'";

			$smt=$pdo->prepare($usersql);
			$smt->execute();
			$user_row=$smt->fetch();
			$username=$user_row['username'];
			$avatar=$user_row['avatar'];
			$production_rows[$key]['user_name']=$username;
			$production_rows[$key]['avatar']=$avatar;

			// 查找创作者是否点赞
			$favoursql  = "SELECT u_id FROM favour WHERE production_id='{$production_id}'";
			$smt=$pdo->prepare($favoursql);
			$smt->execute();
			$favour_row=$smt->fetchAll();

			if($favour_row){
			$arr=array();

			foreach ($favour_row as $key => $value) {				
				 $arr[]=$value['u_id'];
			}
			$is_favour=null;
			$is_favour = in_array($id,$arr);
			$production_rows[$key]['is_favour']=$is_favour;
			
		}
	}



	$result=array("production_rows"=>$production_rows);


	json(1,"success",$result);