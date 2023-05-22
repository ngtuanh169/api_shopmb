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

    $token_id = $payload['id'];

    if ($token_id != $_POST['userId']) {

        die();
    }


    $data = [];

    $arr = [];
    
    $where = '';

    $user_id = isset($_POST['userId']) && $_POST['userId'] != '' ? $_POST['userId'] : '';

    $password = isset($_POST['password']) && $_POST['password'] != '' ? $_POST['password'] : '';

    $newPassword = isset($_POST['newPassword']) && $_POST['newPassword'] != '' ? $_POST['newPassword'] : '';


    $sql_data = "SELECT * FROM user WHERE user_id = '$user_id' ";
        
    $rl_data = mysqli_query($conn,$sql_data);

    $data_user = mysqli_fetch_assoc($rl_data);

    if(md5($password)!=$data_user['user_password']){

        array_push($data,['status'=> 'error', 'message'=> 'Nhập sai mật khẩu !']);

        echo json_encode($data);

        die();
    }


    $md5NewPassword = md5($newPassword);

    $sql = "UPDATE user SET user_password = '$md5NewPassword' WHERE user_id = $user_id";
        
    $rl = mysqli_query($conn,$sql);
             
    array_push($data,['status'=> 'success', 'message'=> 'Thay đổi mật khẩu thành công!']);

    echo json_encode($data);
	
?>