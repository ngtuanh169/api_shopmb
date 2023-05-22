<?php
    include("../connect.php");

    include("../auth/jwt.php");

    $headers = getallheaders();

    $accessToken = $headers['access_token'];

    $vrAccessToken = verifyAccessToken($accessToken);

    if($vrAccessToken['err']){

        die();

    } 

    $arr = explode('.', $accessToken);

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

    $min = isset($_GET['limit']) && $_GET['limit'] != '' ? $_GET['limit']*($_GET['page'] - 1) : '';

    $max = isset($_GET['page']) && $_GET['page'] != '' ? $_GET['limit']*$_GET['page']: '';

    $sql = "SELECT * FROM user WHERE user_id = '$user_id' ";

    $rl = mysqli_query($conn,$sql);

    $data_user = mysqli_fetch_assoc($rl);

    $arrLikeProducts=json_decode($data_user['like_products'],true);


    for ($i=0; $i < count($arrLikeProducts); $i++) {

        if($i >= $min && $i < $max){

            $id = $arrLikeProducts[$i];

            $sql = "SELECT * FROM product WHERE product_id = '$id' ";
            $rl = mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($rl);

            $id = $row['product_id'];
            $name = $row['product_name'];
            $category = $row['category_id'];
            $brand = $row['brand_id'];
            $versions = $row['versions'];
            $colors = $row['colors'];
            $img = $row['product_img'];
            $imgs = $row['product_imgs'];
            $price = $row['product_price'];
            $sale = $row['sale'];
            $qty = $row['product_qty'];
            $sold = $row['sold'];
            $des = $row['product_des'];
            $created = $row['product_created'];

            $sql_ct = "SELECT * FROM category_product WHERE category_id = '$category' ";
            $rl_ct = mysqli_query($conn,$sql_ct);
            $data_ct = mysqli_fetch_assoc($rl_ct);

            $sql_br = "SELECT * FROM brand_product WHERE brand_id = '$brand' ";
            $rl_br = mysqli_query($conn,$sql_br);
            $data_br = mysqli_fetch_assoc($rl_br);

            $sql_total = "SELECT * FROM rating WHERE pro_id = '$id' ";

            $rl_total = mysqli_query($conn,$sql_total);

            $totalRating = mysqli_num_rows($rl_total);

            $star = 0;

            while($row = mysqli_fetch_assoc($rl_total)){

                $star += $row['star'];
            }

            $tbcRating = $totalRating > 0 ? round(($star / $totalRating)) : 0 ;

            array_push($data, [
                'id' => $id, 
                'name' => $name, 
                'category_id' => $data_ct['category_id'], 
                'category' => $data_ct['category_name'], 
                'brand_id' => $data_br['brand_id'], 
                'brand' => $data_br['brand_name'], 
                'versions' =>$versions,
                'colors' =>$colors,
                'img' => $img, 
                'imgs' => $imgs, 
                'price' => $price, 
                'sale' => $sale, 
                'qty' => $qty,
                'sold' => $sold,
                'star' => $tbcRating,
                'des' => $des,
                'created' => $created,
          ]);

        }
    }

    array_push($output, [

        'max'=>count($arrLikeProducts),

        'data'=>$data,

    ]);

    echo json_encode($output);
	
?>