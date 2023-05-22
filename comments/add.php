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

    if($token_id != $_POST['user_id']){

        die();
    }

    $output = [];

    $pro_id = isset($_POST['pro_id']) && $_POST['pro_id'] != '' ? $_POST['pro_id'] : '';

    $user_id = isset($_POST['user_id']) && $_POST['user_id'] != '' ? $_POST['user_id'] : '';

    $parent_id = isset($_POST['parent_id']) && $_POST['parent_id'] != '' ? $_POST['parent_id'] : '0';

    $content = isset($_POST['content']) && $_POST['content'] != '' ? $_POST['content'] : '';

    if(!$user_id || !$pro_id){
        die();
    }

    $sql = "INSERT INTO comments (pro_id, user_id, parent_id, content) VALUES ('$pro_id', '$user_id', '$parent_id', '$content')";

    $rl = mysqli_query($conn,$sql);

    $id_insert = mysqli_insert_id($conn);


    if($id_insert>0){
        

        array_push($output, ['status'=>'success', 'message'=>'Bình luận sản phẩm thành công!']);

    }else{

        array_push($output, ['status'=>'error', 'message'=>'Bình luận sản phẩm thất bại!']);
    }
    echo json_encode($output);
?> 