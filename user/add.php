<?php
    include("../connect.php");

    $data = [];

    $name = isset($_POST['name']) && $_POST['name'] != '' ? $_POST['name'] : '';

    $email = isset($_POST['email']) && $_POST['email'] != '' ? $_POST['email'] : '';

    $password = isset($_POST['password']) && $_POST['password'] != '' ? md5($_POST['password'])  : '';

    $phoneNumber = isset($_POST['phoneNumber']) && $_POST['phoneNumber'] != '' ? $_POST['phoneNumber'] : '';

    if(!$name || !$email || !$password || !$phoneNumber){

        array_push($data, ['status'=>'error', 'message'=>'Bạn chưa nhập đủ thông tin ' ]);

        echo json_encode($data);

        die();
    }

    $sql_total = "SELECT * FROM user WHERE user_email = '$email'";

    $rl_total = mysqli_query($conn,$sql_total);

    $count = mysqli_num_rows($rl_total);

    if($count>0){

        array_push($data, ['status'=>'error', 'message'=>'Email đã tồn tại, vui lòng nhập email khác!' ]);

    }else{

        $sql = "INSERT INTO user (user_name, user_email, user_password, user_sdt) VALUES ('$name', '$email', '$password', '$phoneNumber') ";

        $rl = mysqli_query($conn,$sql);

        $id_insert = mysqli_insert_id($conn);

        if($id_insert>0){

            array_push($data, ['status'=>'success', 'message'=>'Đăng ký tài khoản thành công!']);

        }else{

            array_push($data, ['error'=>'success', 'message'=>'Đăng ký tài khoản thất bại!']);

        }
    }
    echo json_encode($data);
?> 