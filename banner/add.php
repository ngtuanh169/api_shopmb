<?php
    include("../connect.php");
    include("../auth/jwt.php");

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

    $isAdmin = $payload['admin'];

    if(!$isAdmin){

        array_push($message,['status'=> 'error', 'message'=> 'Tài khoản của bạn không có quyền dùng chức năng này!']);

        echo json_encode($message);

        die();
    }


    
    $data = [];

    $img = $_FILES['img'];
    $status = $_POST['status'];

    $time = time();
    $fileImg = $time.$img['name'];

    if(!$img){
        die();
    }
    
    $sql = "INSERT INTO banner (img, status) VALUES ('$fileImg', '$status') ";
    $rl = mysqli_query($conn,$sql);
    $id_insert = mysqli_insert_id($conn);
    //kiểm tra thêm vào database chưa
    if($id_insert > 0){
        move_uploaded_file($img["tmp_name"], '../assets/banner/'.$fileImg);

        array_push($data,['status'=> 'success', 'message'=> 'Thêm banner thành công!']);
    }else{
         array_push($data,['status'=> 'error', 'message'=> 'Lỗi! Thêm banner thất tại']);
    }  
    

    echo json_encode($data);
	
?>