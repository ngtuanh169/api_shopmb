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

    
    $data = [];

    $id = $_POST['id'] ;
    $name = $_POST['name'] ;
    $status = $_POST['status'];

    
    $sql = "SELECT * FROM category_news WHERE name = '$name' && id != '$id'";
    $rl = mysqli_query($conn,$sql);
    $check = mysqli_num_rows($rl);
    if($check > 0){
        array_push($data,['status'=> 'error', 'message'=> 'Lỗi! Tên danh mục đã tồn tại']);
    }else{
        $sql = "UPDATE category_news SET name='$name', status = '$status' WHERE id = '$id'";
        
        $rl = mysqli_query($conn,$sql);
             
        array_push($data,['status'=> 'success', 'message'=> 'Sửa danh mục thành công!']);
        
    }

    echo json_encode($data);
	
?>