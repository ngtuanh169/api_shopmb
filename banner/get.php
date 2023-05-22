<?php
    include("../connect.php");

    // include("../auth/jwt.php");

    // $headers = getallheaders();

    // $token = $headers['access_token'];

    // $verify=verifyAccessToken($token);

    // if ($verify['err']) {
    //     die();
    // }

    // $arr = explode('.', $token);

    // $base64Payload = $arr[1];

    // $jsonPayload = base64_decode($base64Payload);

    // $payload = json_decode($jsonPayload, true);

    // $isAdmin = $payload['admin'];

    // if(!$isAdmin){

    //     die();
    // }



    $output = [];

    $data = [];


    $status = isset($_GET['status']) && $_GET['status'] != '' ? 'WHERE status = '.$_GET['status'] : '';
    $limit = isset($_GET['limit']) && $_GET['limit'] != '' ? 'LIMIT '.$_GET['limit'] : '';
    $offset = isset($_GET['page']) && $_GET['page'] != '' ? 'OFFSET '.($_GET['page'] - 1) * $_GET['limit'] : '';

    $sql_total = "SELECT * FROM banner $status ";
    $rl_total = mysqli_query($conn,$sql_total);
    $count = mysqli_num_rows($rl_total);
    

    $sql = "SELECT * FROM banner $status ORDER BY id DESC $limit $offset ";
    $rl = mysqli_query($conn,$sql);

    while ($row = mysqli_fetch_assoc($rl) ) {
        $id = $row['id'];
        $img = $row['img'];
        $status = $row['status'];
        $dateCreated = $row['created'];

        array_push($data, [
            'id' => $id,
            'img' => $img,  
            'status' => $status,
            'dateCreated' => $dateCreated,
            
        ]);
    }
    array_push($output, [
        'max' => $count,
        'data' => $data
    ]);

    echo json_encode($output);
?>