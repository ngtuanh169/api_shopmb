<?php
    include("../../connect.php");

    include("../../auth/jwt.php");

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

    $isAdmin = $payload['admin'];

    if(!$isAdmin){

        die();
    }
    
    $data = [];
    $time = time();

    $title = $_POST['title'] ;
    $img = $_FILES['img'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $des = isset($_POST['des']) ? $_POST['des'] : '';

    $fileImg = $time.$img['name'];



    $sql = "SELECT * FROM news WHERE title = '$title'";
    $rl = mysqli_query($conn,$sql);
    $check = mysqli_num_rows($rl);
    if($check > 0){
        array_push($data,['status'=> 'error', 'message'=> 'Lỗi! Tiêu đề này đã tồn tại']);
    }else{
        $sql = "INSERT INTO news (id_category, title, img, status, des) 
        VALUES ('$category', '$title', '$fileImg', '$status',  '$des') ";
        $rl = mysqli_query($conn,$sql);
        $id_insert = mysqli_insert_id($conn);
        //kiểm tra thêm vào database chưa
        if($id_insert > 0){

            move_uploaded_file($img["tmp_name"], '../../assets/news/'.$fileImg);

            array_push($data,['status'=> 'success', 'message'=> 'Thêm tin tức thành công!']);
        }else{
            array_push($data,['status'=> 'error', 'message'=> 'Lỗi! Thêm tin tức thất tại']);
        }  
    }
    echo json_encode($data);
	
?>