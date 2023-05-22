<?php
    include("../../connect.php");
    $output = [];
    $data = [];
    $where = '';

    $name = isset($_GET['name']) && $_GET['name'] != '' ? 'name like "%'.$_GET['name'].'%"' : '';
    $limit = isset($_GET['limit']) && $_GET['limit'] != '' ? 'LIMIT '.$_GET['limit'] : '';
    $offset = isset($_GET['page']) && $_GET['page'] != '' ? 'OFFSET '.($_GET['page'] - 1) * $_GET['limit'] : '';
    $status = isset($_GET['status']) && $_GET['status'] != '' ? 'status = '.$_GET['status'] : '';

    if($name && $status){
        $where = 'WHERE '.$name.'AND'.$status;
    }
    elseif (!$name && $status) {
        $where = 'WHERE '.$status;
    }
    elseif ($name && !$status) {
        $where = 'WHERE '.$name;
    }
    else{
        $where = '';
    }


    $sql_total = "SELECT * FROM category_news $where ";
    $rl_total = mysqli_query($conn,$sql_total);
    $count = mysqli_num_rows($rl_total);



    $sql = "SELECT * FROM category_news $where ORDER BY id asc $limit $offset ";
    $rl = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($rl) ) {
        $id = $row['id'];
        $name = $row['name'];
        $status = $row['status'];
        $dateCreated = $row['created'];

        array_push($data, [
            'id' => $id, 
            'name' => $name, 
            'status' => $status, 
            'dateCreated' => $dateCreated
        ]);
    }

    array_push($output, [
        'max' => $count,
        'data'=> $data,
    ]);

    echo json_encode($output);
	
?>