<?php
    include("../../connect.php");
    $data = [];

    $id = $_GET['id'] ;

    $sql = "SELECT * FROM news WHERE id = '$id' ";
    $rl = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($rl);

        $id = $row['id'];
        $id_category = $row['id_category'];
        $title = $row['title'];
        $img = $row['img'];
        $status = $row['status'];
        $des = $row['des'];
        $updated = $row['updated'];
        $created = $row['created'];

        $sql_ct = "SELECT * FROM category_news WHERE id = '$id_category' ";
        $rl_ct = mysqli_query($conn,$sql_ct);
        $data_ct = mysqli_fetch_assoc($rl_ct);


        array_push($data, [
            'id' => $id,
            'id_category' => $id_category,
            'name_category' => $data_ct['name'],
            'title' => $title,
            'img' => $img,
            'status' => $status,
            'des' => $des,
            'updated' => $updated,
            'created' => $created,
      ]);

    echo json_encode($data);
	
?>