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
      $response = $mysql->query("SELECT id,name,score,average from users ORDER BY score DESC,average ASC ");
    // $scoreboard = $response->fetch_all();
    $list = array();
    while ($row = $response->fetch_assoc()) {
        array_push($list, $row);
    }
    echo json_encode(["status"=>true,"scores"=>$list]); 
    }
?>