<?php 
	include("../../connect.php");

    include("../../auth/jwt.php");

    $headers = getallheaders();

    $token = $headers['access_token'];

    $verify=verifyAccessToken($token);

    if ($verify['err']) {
        die();
    }

    $arr = explode('.', $token);

    $base64Payload = $arr[1];

    $jsonPayload = base64_decode($base64Payload);

    $payload = json_decode($jsonPayload, true);

    $token_id = $payload['id'];

    $isAdmin = $payload['admin'];

    if(!$isAdmin){

        die();
    }

	$message = [];
    $arr = [];

	$id = $_GET['id'];

	$_sql = "SELECT * FROM news WHERE id = '$id'";
	$_rl = mysqli_query($conn,$_sql);
	$data = mysqli_fetch_assoc($_rl);

	$sql = "DELETE FROM news WHERE id = '$id'";
	$rl = mysqli_query($conn,$sql);
	$insert_id = mysqli_insert_id($conn);
	if($insert_id > 0){
	   array_push($message,['status'=> 'error', 'message'=> 'Xóa tin tức thất bại!']);
	}else{

		unlink('../../assets/news/'.$data['img']);

	    array_push($message,['status'=> 'success', 'message'=> 'Xóa tin tức thàng công!']);
	}
	echo json_encode($message);