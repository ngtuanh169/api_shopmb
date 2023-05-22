<?php
    include("../../connect.php");
    $output = [];
    $data = [];
    $WHERE = '';
    $orderBy = '';

    $name = isset($_GET['name']) && $_GET['name'] != '' ? 'title like "%'.$_GET['name'].'%"' : '';
    $noId = isset($_GET['noId']) && $_GET['noId'] != '' ? 'id != '.$_GET['noId'] : '';
    $idCategory = isset($_GET['idCategory']) && $_GET['idCategory'] != '' ? 'id_category = '.$_GET['idCategory'] : '';
    $limit = isset($_GET['limit']) && $_GET['limit'] != '' ? 'LIMIT '.$_GET['limit'] : '';
    $offset = isset($_GET['page']) && $_GET['page'] != '' ? 'OFFSET '.($_GET['page'] - 1) * $_GET['limit'] : '';
    $status = isset($_GET['status']) && $_GET['status'] != '' ? 'status = '.$_GET['status'] : '';

    if($idCategory && $status && $noId){
        $WHERE = 'WHERE '.$idCategory.' && '.$status.'&&'.$noId;
    }

    elseif ($idCategory && $status && !$noId) {
       $WHERE = 'WHERE '.$idCategory.' && '.$status;
    }

    elseif (!$idCategory && $status && $noId) {
       $WHERE = 'WHERE '.$status.'&&'.$noId;
    }

    elseif ($idCategory && !$status && $noId) {
       $WHERE = 'WHERE '.$idCategory.' && '.$noId;
    }

    elseif (!$idCategory && !$status && !$noId) {
        $WHERE='';
    }
    
    else{
        $WHERE = 'WHERE '.$idCategory.' '.$status.' '.$noId;
    }

    if($WHERE && $name){
        $WHERE = $WHERE.' && '.$name;
    }elseif (!$WHERE && $name) {
        $WHERE = 'WHERE '.$name;
    }
  
    $sql_total = "SELECT * FROM news  $WHERE ";
    $rl_total = mysqli_query($conn,$sql_total);
    $count = mysqli_num_rows($rl_total);
// var_dump($sql_total);
// var_dump($rl_total);
    $sql = "SELECT * FROM news  $WHERE ORDER BY id DESC $limit $offset ";
    $rl = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($rl) ) {
        $id = $row['id'];
        $category_id = $row['id_category'];
        $title = $row['title'];
        $img = $row['img'];
        $status = $row['status'];
        $des = $row['des'];
        $created = $row['created'];
        $updated = $row['updated'];

        $sql_ct = "SELECT * FROM category_news WHERE id = '$category_id' ";
        $rl_ct = mysqli_query($conn,$sql_ct);
        $data_ct = mysqli_fetch_assoc($rl_ct);

        array_push($data, [
            'id' => $id, 
            'title' => $title, 
            'img' => $img, 
            'category_id' => $category_id,
            'category_name' => $data_ct['name'],
            'status' => $status, 
            'des' => $des, 
            'created' => $created, 
            'updated' => $updated,
        ]);
    }

    array_push($output, [
        'max' => $count,
        'data'=> $data,
    ]);

    echo json_encode($output);
	
?>