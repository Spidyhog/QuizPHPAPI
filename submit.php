<?php
   header("Access-Control-Allow-Origin: *");
   header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,Authorization");
	require_once('JWT.php');
  require_once('db.php');
  require_once('secrets.php');
  $_POST = json_decode(file_get_contents('php://input'), true);
	$data = null;
    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
      $data = json_decode(JWT::decode($headers['Authorization'],$jwt_secret));
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
          $average_time = $user['average'];
	       
        	$res_ans=$mysql->query("select answer from questions where id='$score'");
          	if($res_ans->num_rows!=0){
              while ($row1 = $res_ans->fetch_assoc()) {
             $ans= $row1['answer'];
             }
          }
          if(isset($ans)){
                $ans=strtolower($ans);
                if($ans == strtolower($_POST['answer'])){
                  $id = $data->id;
                  $count_query = $mysql->query("SELECT COUNT(*) as count  FROM questions");
                  $count = $count_query->fetch_assoc()["count"];
                  
                    $date = date('Y-m-d H:i:s');
                    $date_diff = round(abs(strtotime($date) - strtotime($user['last_ques_time'])) / 60,2);
                    $average_time = $average_time + ( $date_diff/$count);
                  
                	$res_query=$mysql->query("update  users set score = score+1,last_ques_time = '$date',average = '$average_time'   where id='$id'") or die(json_encode(["success"=> false,"message" => "500 - Internal Error"]));
                	   echo json_encode(["success"=> true,"message" => "Correct Answer"]);
               }else
                	echo json_encode(["success"=> false,"message" => "Wrong Answer","ques_id"=> $score]);
          }else
              echo json_encode(["success"=> true,"message" => "You Made It Till End"]);

    }
?>
