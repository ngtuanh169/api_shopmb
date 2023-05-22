<?php
    include("../connect.php");

    $output = [];

    $data = [];

    // $pro_id = isset($_GET['pro_id']) && $_GET['pro_id'] != '' ? $_GET['pro_id'] : '';

    $parent_id = isset($_GET['parent_id']) && $_GET['parent_id'] != '' ? $_GET['parent_id'] : '0';

    $limit = isset($_GET['limit']) && $_GET['limit'] != '' ? $_GET['limit'] : '';

    // $offset = isset($_GET['page']) && $_GET['page'] != '' ? 'OFFSET '.($_GET['page'] - 1) * $_GET['limit'] : '';


    $sql_total = "SELECT * FROM comments WHERE parent_id = '$parent_id' ";

    $rl_total = mysqli_query($conn,$sql_total);

    $count = mysqli_num_rows($rl_total);


    $sql = "SELECT * FROM comments WHERE parent_id = '$parent_id' ORDER BY likes DESC LIMIT 50  ";

    $rl = mysqli_query($conn,$sql);

    while ($row = mysqli_fetch_assoc($rl) ){
        $id = $row['id'];
        $pro_id = $row['pro_id'];
        $user_id = $row['user_id'];
        $content = $row['content'];
        $likes = $row['likes'];
        $dislikes = $row['dislikes'];
        $parent_id = $row['parent_id'];
        $created = $row['created'];

        $sql_user = "SELECT * FROM user WHERE user_id = '$user_id'";

        $rl_user = mysqli_query($conn,$sql_user);

        $row_user = mysqli_fetch_assoc($rl_user);

        array_push($data, [
            'id' => $row['id'],

            'pro_id' => $row['pro_id'],

            'user_id' => $row['user_id'],

            'user_name' => $row_user['user_name'],

            'admin' => $row_user['roles_id'] == '1',

            'user_avt' => $row_user['user_avt'],

            'content' => $row['content'],

            'likes' => $row['likes'],
            
            'dislikes' => $row['dislikes'],

            'parent_id' => $row['parent_id'],

            'created' => $row['created'],
        ]);

    }
    for ($i=0; $i < $limit ; $i++) { 

        if($data[$i]){

            array_push($output, $data[$i]);
        }
    }

    echo json_encode($output);
?> 