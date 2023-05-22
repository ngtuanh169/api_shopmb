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


    $output = [];

    $data = [];

    $data_user = [];

    $count_user = 0;

    $time = isset($_GET['time']) && $_GET['time'] != '' ? $_GET['time'] : time();

    $min = $_GET['limit'] * ($_GET['page'] - 1);

    $max = $_GET['limit'] * $_GET['page'];


    $sql_user = "SELECT * FROM user ORDER BY user_id DESC ";

    $rl_user = mysqli_query($conn,$sql_user);

    while ($row = mysqli_fetch_assoc($rl_user) ) {

        $id = $row['user_id'];

        $name = $row['user_name'];

        $email = $row['user_email'];

        $sdt = $row['user_sdt'];

        $status = $row['user_status'];

        $created = $row['user_created'];

        $updated = $row['user_updated'];

        array_push($data, [

            'id' => $id, 

            'name' => $name, 

            'email' => $email, 

            'sdt' => $sdt, 

            'status' => $status, 

            'created' => $created, 

            'updated' => $updated, 

        ]);

    }

    for ($i=0; $i < count($data) ; $i++) { 

        $timeInt = strtotime($data[$i]['created']) - 25200;

        if ($timeInt <= $time ) {

            break;
        }

        $count_user++;

        if ($i >= $min && $i < $max ) {

            array_push($data_user, $data[$i]);
        }
        
    }

    array_push($output, [

        'max' => $count_user,

        'data' => $data_user,
        
    ]);

    echo json_encode($output);
	
?>