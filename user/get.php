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
    $where = '';
    $orderBy = '';

    // $status = isset($_GET['status']) && $_GET['status'] != '' ? 'WHERE status = '.$_GET['status'] : '';
    $name = isset($_GET['name']) && $_GET['name'] != '' ? 'user_name like "%'.$_GET['name'].'%"' : '';

    $status = isset($_GET['status']) && $_GET['status'] != '' ? 'user_status = '.$_GET['status']  : '';

    $limit = isset($_GET['limit']) && $_GET['limit'] != '' ? 'LIMIT '.$_GET['limit'] : '';

    $offset = isset($_GET['page']) && $_GET['page'] != '' ? 'OFFSET '.($_GET['page'] - 1) * $_GET['limit'] : '';


    if($name && $status){
        $where = 'WHERE '.$name.'AND'.$status;
    }

    elseif (!$name && $status) {
        $where = 'WHERE '.$status;
    }

    elseif ($name && !$status) {
        $where = 'WHERE '.$name;
    }

    else{
        $where = '';
    }



    $sql_total = "SELECT * FROM user $where ";
    $rl_total = mysqli_query($conn,$sql_total);
    $count = mysqli_num_rows($rl_total);

    $sql = "SELECT * FROM user $where ORDER BY user_id DESC $limit $offset ";
    $rl = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($rl) ) {
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

    array_push($output, [
        'max' => $count,
        'data'=> $data,
    ]);

    echo json_encode($output);
	
?>