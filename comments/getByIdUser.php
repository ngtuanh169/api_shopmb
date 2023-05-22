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

    $user_id = isset($_GET['userId']) && $_GET['userId'] != '' ? $_GET['userId'] : '';

    $parent_id = isset($_GET['parentId']) && $_GET['parentId'] != '' ? $_GET['parentId'] : '';

    $limit = isset($_GET['limit']) && $_GET['limit'] != '' ? 'LIMIT '.$_GET['limit'] : '';

    $offset = isset($_GET['page']) && $_GET['page'] != '' ? 'OFFSET '.($_GET['page'] - 1) * $_GET['limit'] : '';


    $sql_total = "SELECT * FROM comments WHERE user_id = '$user_id' ";

    $rl_total = mysqli_query($conn,$sql_total);

    $count = mysqli_num_rows($rl_total);


    $sql = "SELECT * FROM comments WHERE user_id = '$user_id' ORDER BY id DESC $limit $offset ";

    $rl = mysqli_query($conn,$sql);

    while ($row = mysqli_fetch_assoc($rl) ){

        $id = $row['id'];

        $pro_id = $row['pro_id'];

        $user_id = $row['user_id'];

        $content = $row['content'];

        $created = $row['created'];


        $sql_user = "SELECT * FROM user WHERE user_id = '$user_id'";

        $rl_user = mysqli_query($conn,$sql_user);

        $row_user = mysqli_fetch_assoc($rl_user);

        $sql_pro = "SELECT * FROM product WHERE product_id = '$pro_id'";

        $rl_pro = mysqli_query($conn,$sql_pro);

        $row_pro = mysqli_fetch_assoc($rl_pro);

        array_push($data, [
            'id' => $row['id'],

            'pro_id' => $row['pro_id'],

            'user_id' => $row['user_id'],

            'user_name' => $row_user['user_name'],

            'pro_name' => $row_pro['product_name'],

            'pro_img' => $row_pro['product_img'],

            'admin' => $row_user['roles_id'] == '1',

            'content' => $row['content'],

            'created' => $row['created'],
        ]);

    }
    array_push($output, [
        'data' => $data,
        'max' => $count,
    ]);

    echo json_encode($output);
?> 