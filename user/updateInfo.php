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

    if ($token_id != $_POST['id']) {
        die();
    }

    

    $data = [];

    $arr = [];

    $where = '';

    $time = time();

    $img_text = '';

    $id = isset($_POST['id']) && $_POST['id'] != '' ? $_POST['id'] : '';

    $name = isset($_POST['name']) && $_POST['name'] != '' ? $_POST['name'] : '';

    $email = isset($_POST['email']) && $_POST['email'] != '' ? $_POST['email'] : '';

    $avt = isset($_FILES['avt'])  ? $_FILES['avt'] : '';

    $sdt = isset($_POST['sdt']) && $_POST['sdt'] != '' ? $_POST['sdt'] : '';

    $email = isset($_POST['email']) && $_POST['email'] != '' ? $_POST['email'] : '';

    if($avt){

        $fileImg = $time.$avt['name'];

        $img_text = ',user_avt = '.json_encode($fileImg);
    }


    $sql_data = "SELECT * FROM user WHERE user_id = '$id'";
        
    $rl_data = mysqli_query($conn,$sql_data);

    $data_user = mysqli_fetch_assoc($rl_data);


    $sql = "UPDATE user SET user_name = '$name', user_email='$email', user_sdt='$sdt' $img_text WHERE user_id = '$id' ";
        
    $rl = mysqli_query($conn,$sql);

    if($avt){

        unlink('../assets/user/'.$data_user['user_avt']);

        move_uploaded_file($avt["tmp_name"], '../assets/user/'.$fileImg);
    }
             
    array_push($data,[
        'status'=> 'success', 

        'message'=> 'Cập nhật trạng thái thành công!', 

    ]);

    array_push($data,[

        'name'=> $name, 

        'email'=> $email,

        'sdt'=> $sdt,  

        'avt'=> $avt ? $fileImg : $data_user['user_avt'],  

    ]);

    echo json_encode($data);
	
?>