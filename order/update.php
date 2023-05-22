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

        die();
    }
    

    $ouput = [];

    $id = isset($_POST['id']) && $_POST['id'] != '' ? $_POST['id'] : '';

    $status = isset($_POST['status']) && $_POST['status'] != '' ? $_POST['status'] : '';


    $sql = "UPDATE orders SET status = '$status' WHERE id = '$id' ";

    $rl = mysqli_query($conn, $sql);

    array_push($ouput, [
        'status' => 'success',
        'message' => 'Cập nhập thành công!',
    ]);

    
    echo json_encode($ouput);
?> 