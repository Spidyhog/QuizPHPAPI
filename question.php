<?php
	   header("Access-Control-Allow-Origin: *");
   header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,Authorization");
	require_once('JWT.php');
	require_once('db.php');
	require_once('secrets.php');
		$data = null;
  	if(isset($_SERVER['HTTP_AUTHORIZATION'])){
    try{
    	$data = json_decode(JWT::decode($_SERVER['HTTP_AUTHORIZATION'],$jwt_secret));
  	    }catch(Exception $e){
  	        
  	    }
    }
    if (!isset($data) && $data == null ) {
    	echo json_encode(["success"=>null,"message"=>"UnAuthorised Access"]);
    	die();
    }else{
    	
    	$find = $mysql->prepare("SELECT * FROM `users` WHERE id = ?");
		$find->bind_param('i', $data->id);
		$find->execute();
		$user = $find->get_result()->fetch_assoc();
		$score = $user['score']+1;
		$response = $mysql->query("SELECT id, image from questions WHERE id =".$score);
		$question = $response->fetch_assoc();
		echo json_encode(["status"=>true,"question"=>$question]);	
    }

  
?>