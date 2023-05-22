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

    if ($token_id != $_POST['user_id']) {
        
        die();
    }

    
    $output = [];

    $likeProducts = [];

    $id = isset($_POST['user_id']) && $_POST['user_id'] != '' ? $_POST['user_id'] : '';

    $pro_id = isset($_POST['pro_id']) && $_POST['pro_id'] != '' ? $_POST['pro_id'] : '';


    if(empty($id) && empty($pro_id)){

        die();

    }

    $sql = "SELECT * FROM user  WHERE user_id = $id";
        
    $rl = mysqli_query($conn,$sql);

    $data = mysqli_fetch_assoc($rl);

    if(empty($data['like_products'])){

        array_push($likeProducts, $pro_id);

        $likeProducts_ud = json_encode($likeProducts);

        $sql_ud = "UPDATE user SET like_products = '$likeProducts_ud' WHERE user_id = $id";

        $rl_ud = mysqli_query($conn,$sql_ud);
                 
        array_push($output,['status'=> 'success', 'message'=> 'Cập nhật trạng thái thành công!']);

        echo json_encode($output);

        die();

    }

    $likeProducts = json_decode($data['like_products'],true);

    for ($i=0; $i < count($likeProducts) ; $i++) { 

        if($likeProducts[$i] == $pro_id){

            unset($likeProducts[$i]);

            $likeProducts_ud = json_encode(array_values($likeProducts));

            $sql_ud = "UPDATE user SET like_products = '$likeProducts_ud' WHERE user_id = $id";

            $rl_ud = mysqli_query($conn,$sql_ud);
                 
            array_push($output,['status'=> 'success', 'message'=> 'Cập nhật trạng thái thành công!']);

            echo json_encode($output);

            die();
        }
    }

    array_push($likeProducts, $pro_id);

    $likeProducts_ud = json_encode($likeProducts);

    $sql_ud = "UPDATE user SET like_products = '$likeProducts_ud' WHERE user_id = $id";

    $rl_ud = mysqli_query($conn,$sql_ud);
                 
    array_push($output,['status'=> 'success', 'message'=> 'Cập nhật trạng thái thành công!']);

    echo json_encode($output);
    
?>