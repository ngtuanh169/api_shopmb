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

    if($token_id != $_GET['userId']){

        die();
    }


    $output = [];
    $data = [];
    $where = '';
    $orderBy = '';

    $user_id = isset($_GET['userId']) && $_GET['userId'] != '' ? $_GET['userId']  : '';

    $limit = isset($_GET['limit']) && $_GET['limit'] != '' ? 'LIMIT '.$_GET['limit'] : '';

    $offset = isset($_GET['page']) && $_GET['page'] != '' ? 'OFFSET '.($_GET['page'] - 1) * $_GET['limit'] : '';

    $sql_total = "SELECT * FROM orders WHERE user_id = '$user_id'";
    $rl_total = mysqli_query($conn,$sql_total);
    $count = mysqli_num_rows($rl_total);

    $sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY id DESC $limit $offset ";
    $rl = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($rl) ) {
        $id = $row['id'];
        $user_id = $row['user_id'];
        $user_name = $row['user_name'];
        $user_email = $row['user_email'];
        $status = $row['status'];
        $total = $row['total'];
        $city = $row['city'];
        $district = $row['district'];
        $ward = $row['ward'];
        $address = $row['address'];
        $des = $row['des'];
        $created = $row['created'];
        $updated = $row['updated'];

        $sql_1 = "SELECT * FROM orders_product WHERE order_id = '$id' ";

        $rl_1 = mysqli_query($conn,$sql_1);

        $orders_pro = [];

        while ($row_1 = mysqli_fetch_assoc($rl_1) ){

            array_push($orders_pro, [
                'id' => $row_1['id'],
                'pro_id' => $row_1['pro_id'],
                'pro_name' => $row_1['pro_name'],
                'pro_img' => $row_1['pro_img'],
                'pro_attr' => $row_1['pro_attr'],
                'pro_price' => $row_1['pro_price'],
                'pro_qty' => $row_1['pro_qty'],
                'created' => $row_1['created'],
                'updated' => $row_1['updated'],
            ]);

        }

        array_push($data, [
            'id' => $id, 
            'user_id' => $user_id, 
            'user_name' => $user_name, 
            'user_email' => $user_email, 
            'status' => $status, 
            'total' => $total, 
            'city' => $city, 
            'district' => $district, 
            'ward' => $ward, 
            'address' => $address, 
            'des' => $des, 
            'created' => $created, 
            'updated' => $updated, 
            'orders_product' => $orders_pro, 
        ]);
    }

    array_push($output, [
        'max' => $count,
        'data'=> $data,
    ]);
    echo json_encode($output);
	
?>