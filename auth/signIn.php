<?php
    include("../connect.php");
    
    include("./jwt.php");

    $output = [];

    $infoUser = [];

    $email = isset($_POST['email']) && $_POST['email'] != '' ? $_POST['email'] : '';

    $password = isset($_POST['password']) && $_POST['password'] != '' ? md5($_POST['password']) : '';


    if(!$email || !$password ){

        array_push($output, [

            'status' => 'error',

            'message' => 'Bạn chưa nhập đủ thông tin',
        ]);

        
    }



    $sql = "SELECT * FROM user WHERE user_email = '$email' AND user_password = '$password' ";

    $rl = mysqli_query($conn,$sql);
    
    $data = mysqli_fetch_assoc($rl);


    if($data){
        $payload = [
            
            'id' => $data['user_id'],

            'name' => $data['user_name'],

            'email' => $data['user_email'],

            'admin' => $data['roles_id'] == 1,
        ];

        $accessToken = createAccessToken($payload);

        $refreshToken = createRefreshToken($payload);

        array_push($infoUser, [

            'id' => $data['user_id'],

            'name' => $data['user_name'],

            'email' => $data['user_email'],

            'sdt' => $data['user_sdt'],

            'avt' => $data['user_avt'],

            'admin' => $data['roles_id'] == 1,

            'likeProducts' => $data['like_products'],

            'likeComments' => $data['like_comments'],

            'dislikeComments' => $data['dislike_comments'],

            'status' => $data['user_status'],

            'created' => $data['user_created'],

            'updated' => $data['user_updated'],

        ]);

        array_push($output, [
            
            'status' => 'success',

            'message' => 'Đăng nhập thành công!',

            'accessToken' => $accessToken,

            'refreshToken' => $refreshToken,

            'infoUser' => $infoUser,
        ]);

    }else{
         array_push($output, [
            'status' => 'error',
            'message' => 'Tài khoản hoặc mật khẩu không chính xác',
        ]);
    }


    echo json_encode($output);
	
?>