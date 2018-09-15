<?php
	   header("Access-Control-Allow-Origin: *");
   header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,Authorization");
	require_once('JWT.php');
	require_once('db.php');
	require_once('secrets.php');
	$data = null;
  	$headers = apache_request_headers();
  	if(isset($headers['Authorization'])){
    	$data = json_decode(JWT::decode($headers['Authorization'],$jwt_secret));
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