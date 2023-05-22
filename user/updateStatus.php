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

    if (!$isAdmin) {
        
        die();
    }

    $data = [];
    $arr = [];
    $where = '';

    $id = isset($_POST['id']) && $_POST['id'] != '' ? $_POST['id'] : '';'';

    $status = isset($_POST['status']) && $_POST['status'] != '' ? $_POST['status'] : '';
    
    if($status != '0' && $status != '1'){
        var_dump($status);
        die();
    }

    $sql = "UPDATE user SET user_status = '$status' WHERE user_id = $id";
        
    $rl = mysqli_query($conn,$sql);
             
    array_push($data,['status'=> 'success', 'message'=> 'Cập nhật trạng thái thành công!']);

    echo json_encode($data);
	
?>