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
    $img_text = '';
	$time = time();

    $id = $_POST['id'] ;
    $img = $_FILES['img'];
    $status = $_POST['status'];


    if($img){
        $fileImg = $time.$img['name'];

        $img_text = 'img = '.json_encode($fileImg).',';
    }


    $sql_data = "SELECT * FROM banner WHERE id = '$id' ";
    $rl_data = mysqli_query($conn,$sql_data);
    $data_bn = mysqli_fetch_assoc($rl_data);


    $sql = "UPDATE banner SET $img_text status = '$status' WHERE id = '$id'";
    $rl = mysqli_query($conn,$sql);

    if($img){
        unlink('../assets/banner/'.$data_bn['img']);

        move_uploaded_file($img["tmp_name"], '../assets/banner/'.$fileImg);
    }
             
    array_push($data,['status'=> 'success', 'message'=> 'Sửa banner thành công!']);
        

    echo json_encode($data);
	
?>