<?php
    include("../connect.php");

    $output = [];

    $data = [];

    $pro_id = isset($_GET['pro_id']) && $_GET['pro_id'] != '' ? 'WHERE pro_id ='.$_GET['pro_id'] : '';

    $limit = isset($_GET['limit']) && $_GET['limit'] != '' ? 'LIMIT '.$_GET['limit'] : '';

    $offset = isset($_GET['page']) && $_GET['page'] != '' ? 'OFFSET '.($_GET['page'] - 1) * $_GET['limit'] : '';


    $sql_total = "SELECT * FROM rating  $pro_id ";

    $rl_total = mysqli_query($conn,$sql_total);

    $count = mysqli_num_rows($rl_total);


    $sql = "SELECT * FROM rating  $pro_id ORDER BY id DESC $limit $offset ";

    $rl = mysqli_query($conn,$sql);

    while ($row = mysqli_fetch_assoc($rl) ){
        $id = $row['id'];
        $pro_id = $row['pro_id'];
        $user_id = $row['user_id'];
        $star = $row['star'];
        $content = $row['content'];
        $created = $row['created'];

        $sql_user = "SELECT * FROM user WHERE user_id = '$user_id'";

        $rl_user = mysqli_query($conn,$sql_user);

        $row_user = mysqli_fetch_assoc($rl_user);

        array_push($data, [
            'id' => $row['id'],

            'pro_id' => $row['pro_id'],

            'user_id' => $row['user_id'],

            'user_name' => $row_user['user_name'],

            'user_avt' => $row_user['user_avt'],

            'admin' => $row_user['roles_id'] == '1',

            'star' => $row['star'],

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