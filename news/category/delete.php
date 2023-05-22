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

	$sql = "DELETE FROM category_news WHERE id = '$id'";
	$rl = mysqli_query($conn,$sql);
	$insert_id = mysqli_insert_id($conn);
	if($insert_id > 0){
		array_push($data,['status'=> 'error', 'message'=> 'Xóa danh mục thất bại!']);
	}else{

		array_push($message,['status'=> 'success', 'message'=> 'Xóa danh mục thàng công!']);
	}

	echo json_encode($message);