<?php
    include("../connect.php");

    include("../auth/jwt.php");
    
    $output = [];

    $data = [];

    $headers = getallheaders();

    $accessToken = $headers['access_token'];

    $arr = explode('.', $accessToken);

    $base64Payload = $arr[1];

    $jsonPayload = base64_decode($base64Payload);

    $payload = json_decode($jsonPayload, true);

    $id = $payload['id'];

    $vrAccessToken = verifyAccessToken($accessToken);

    if($vrAccessToken['err']){

        die();

    } 

    $sql = "SELECT * FROM user WHERE user_id = '$id' ";
    $rl = mysqli_query($conn,$sql);
    $data_user = mysqli_fetch_assoc($rl);

    array_push($data, [

        'id' => $data_user['user_id'],

        'name' => $data_user['user_name'],

        'email' => $data_user['user_email'],

        'sdt' => $data_user['user_sdt'],

        'avt' => $data_user['user_avt'],

        'admin' => $data_user['roles_id'] == 1,

        'likeProducts' => $data_user['like_products'],

        'likeComments' => $data_user['like_comments'],

        'dislikeComments' => $data_user['dislike_comments'],

        'status' => $data_user['user_status'],

        'created' => $data_user['user_created'],

        'updated' => $data_user['user_updated'],

    ]);

    echo json_encode($data);
	
?>