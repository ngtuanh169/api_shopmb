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

    $isAdmin = $payload['admin'];

    if(!$isAdmin || $token_id != $_POST['user_id']){

        die();
    }

    $output = [];

    $rating_id = isset($_POST['rating_id']) && $_POST['rating_id'] != '' ? $_POST['rating_id'] : '';

    $user_id = isset($_POST['user_id']) && $_POST['user_id'] != '' ? $_POST['user_id'] : '';

    $content = isset($_POST['content']) && $_POST['content'] != '' ? $_POST['content'] : '';

    if(!$user_id){
        die();
    }

    $sql = "INSERT INTO feedback_rating (rating_id, user_id, content) VALUES ('$rating_id', '$user_id', '$content')";

    $rl = mysqli_query($conn,$sql);

    $id_insert = mysqli_insert_id($conn);


    if($id_insert>0){
        

        array_push($output, ['status'=>'success', 'message'=>'Đánh giá sản phẩm thành công!']);

    }else{

        array_push($output, ['status'=>'error', 'message'=>'Đánh giá sản phẩm thất bại!']);
    }
    echo json_encode($output);
?> 