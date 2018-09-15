<?php
	  header("Access-Control-Allow-Origin: *");
   header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	require_once('JWT.php');
	require_once('db.php');
	require_once('secrets.php');
	$_POST = json_decode(file_get_contents('php://input'), true);

	$enroll_id=str_replace(" ","",$_POST['enrollment']);
 	$dname=strtoupper($_POST['name']);
	$enroll_id=strtoupper($enroll_id);   //TODO:error beutify


   if(preg_match('/^[1-1][5-8][1-1]/', $enroll_id) && preg_match('/[0-9][0-9][0-9]$/', $enroll_id) && strlen($enroll_id)>=6 && strlen($enroll_id)<=7 && strlen($dname)>0 ){
   	$date = date('Y-m-d H:i:s');
    $stmtins = $mysql->prepare("insert into users values(DEFAULT,?,?,'0',?,?,0.0)");
    $stmtins->bind_param('ssss',$dname,$enroll_id,$date,$date);
    $stmtins->execute();
	$find = $mysql->prepare("SELECT * FROM `users` WHERE enrollment=? ");
	$find->bind_param('s', $enroll_id);
	$find->execute();
	$user = $find->get_result()->fetch_assoc();
    echo json_encode(["success" => true,"message"=> "Login Successfull","token"=>JWT::encode(json_encode($user),$jwt_secret), "user" => $user]);
    $res = $stmtins->get_result();
	}else{
		echo json_encode(["success"=> false,"message" => "Validation Failed"]);
	}
?>