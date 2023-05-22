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

    $user_id = isset($_POST['user_id']) && $_POST['user_id'] != '' ? $_POST['user_id'] : '';

    $pro_id = isset($_POST['pro_id']) && $_POST['pro_id'] != '' ? $_POST['pro_id'] : '';

    $star = isset($_POST['star']) && $_POST['star'] != '' ? $_POST['star'] : '';

    $content = isset($_POST['content']) && $_POST['content'] != '' ? $_POST['content'] : '';

    if(!$user_id){
        die();
    }


    $sql_sr = "SELECT * FROM rating WHERE pro_id = '$pro_id' && user_id = '$user_id'";

    $rl_sr = mysqli_query($conn,$sql_sr);

    $check = mysqli_num_rows($rl_sr);

    if($check>0){
        array_push($output, [

            'status' => 'error',

            'message' => 'Bạn đã đánh giá sản phẩm này rồi!',
        ]);

        echo json_encode($output);
        die();
    }


    $sql = "INSERT INTO rating (pro_id, user_id, star, content) VALUES ('$pro_id', '$user_id', '$star', '$content')";

    $rl = mysqli_query($conn,$sql);

    $id_insert = mysqli_insert_id($conn);


    if($id_insert>0){
        

        array_push($output, ['status'=>'success', 'message'=>'Đánh giá sản phẩm thành công!']);

    }else{

        array_push($output, ['status'=>'error', 'message'=>'Đánh giá sản phẩm thất bại!']);
    }
    echo json_encode($output);
?> 