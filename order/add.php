<?php
    include("../connect.php");
    include("../auth/jwt.php");

    $data = [];

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

    $user_id = isset($_POST['user_id']) && $_POST['user_id'] != '' ? $_POST['user_id'] : '';

    $user_name = isset($_POST['user_name']) && $_POST['user_name'] != '' ? $_POST['user_name'] : '';

    $user_email = isset($_POST['user_email']) && $_POST['user_email'] != '' ? $_POST['user_email'] : '';

    $total = isset($_POST['total']) && $_POST['total'] != '' ? $_POST['total'] : '';

    $city = isset($_POST['city']) && $_POST['city'] != '' ? $_POST['city'] : '';

    $district = isset($_POST['district']) && $_POST['district'] != '' ? $_POST['district'] : '';

    $ward = isset($_POST['ward']) && $_POST['ward'] != '' ? $_POST['ward'] : '';

    $address = isset($_POST['address']) && $_POST['address'] != '' ? $_POST['address'] : '';

    $des = isset($_POST['des']) && $_POST['des'] != '' ? $_POST['des'] : '';

    $products = isset($_POST['products']) && $_POST['products'] != '' ? $_POST['products'] : '';
 
    $products = json_decode($products,true);


    $sql = "INSERT INTO orders (user_id, user_name, user_email, total, city, district, ward, address, des) VALUES ('$user_id', '$user_name', '$user_email', '$total', '$city', '$district', '$ward', '$address', '$des')";

    $rl = mysqli_query($conn,$sql);

    $id_insert = mysqli_insert_id($conn);

    if($id_insert>0){
        for ($i=0; $i < count($products) ; $i++) { 

            $attr = [];

            array_push($attr, [
                'ver' => $products[$i]["ver"],
                'color' => $products[$i]["color"],
            ]);

            $pro_id = $products[$i]["idProduct"];

            $pro_name = $products[$i]["name"];

            $pro_img = $products[$i]["img"];

            $pro_attr = json_encode($attr);

            $pro_price = $products[$i]["price"]-($products[$i]["price"]*($products[$i]["sale"]/100));

            $pro_qty = $products[$i]["qty"];



            $sql_1 = "INSERT INTO orders_product (order_id, pro_id, pro_name, pro_img, pro_attr, pro_price, pro_qty) VALUES ('$id_insert', '$pro_id', '$pro_name', '$pro_img', '$pro_attr', '$pro_price', '$pro_qty')";

            $rl_1 = mysqli_query($conn,$sql_1);

            $id_insert_1 = mysqli_insert_id($conn);



            $sql_2 = "SELECT * FROM product WHERE product_id = '$pro_id'";

            $rl_2 = mysqli_query($conn,$sql_2);
 
            $data_pro = mysqli_fetch_assoc($rl_2);
 
            $qty = $data_pro['product_qty'] - $pro_qty;

            $sold = $data_pro['sold'] + $pro_qty;


            $sql_3 = "UPDATE product SET product_qty='$qty', sold ='$sold' WHERE product_id = '$pro_id'";
        
            $rl_3 = mysqli_query($conn,$sql_3);


        }       

        array_push($data, ['status'=>'success', 'message'=>'Đặt hàng thành công!']);

    }else{

        array_push($data, ['status'=>'error', 'message'=>'Đặt hàng thất bại!']);
    }
    echo json_encode($data);
?> 