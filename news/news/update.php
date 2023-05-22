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
    $img_text = '';
	$time = time();

    $id = $_POST['id'] ;
    $title = $_POST['title'] ;
    $category = $_POST['category'];
    $img = $_FILES['img'];
    $status = $_POST['status'];
    $des = isset($_POST['des']) ? $_POST['des'] : '';
    
    if($img){
        $fileImg = $time.$img['name'];

        $img_text = 'img = '.json_encode($fileImg).',';
    }

    $_sql = "SELECT * FROM news WHERE id = '$id'";
    $_rl = mysqli_query($conn,$_sql);
    $data = mysqli_fetch_assoc($_rl);

    $sql = "SELECT * FROM news WHERE title = '$title' && id != '$id'";
    $rl = mysqli_query($conn,$sql);
    $check = mysqli_num_rows($rl);
    if($check > 0){
        array_push($data,['status'=> 'error', 'message'=> 'Lỗi! Tiêu đề này đã tồn tại']);
    }else{
        $sql = "UPDATE news SET id_category='$category', title='$title', $img_text status='$status', des='$des' WHERE id = '$id'";
        
        $rl = mysqli_query($conn,$sql);

        if($img){
            move_uploaded_file($img["tmp_name"], '../../assets/news/'.$fileImg);
            
            unlink('../../assets/news/'.$data['img']);
        }
             
        array_push($data,['status'=> 'success', 'message'=> 'Sửa tin tức thành công!']);
        
    }

    echo json_encode($data);
	
?>