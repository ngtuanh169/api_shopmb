<?php
    include("../connect.php");

    $output = [];

    $star = 0;

    $pro_id = isset($_GET['pro_id']) && $_GET['pro_id'] != '' ? $_GET['pro_id'] : '';


    $sql_total = "SELECT * FROM rating WHERE pro_id = '$pro_id' ";

    $rl_total = mysqli_query($conn,$sql_total);

    $totalRating = mysqli_num_rows($rl_total);

    while($row = mysqli_fetch_assoc($rl_total)){

        $star += $row['star'];
    }

    $tbcRating = $totalRating > 0 ? floor(($star / $totalRating)) : 0 ;

    $sqlRating1s = "SELECT * FROM rating WHERE pro_id = '$pro_id' AND star = 1";
    $rlRating1s = mysqli_query($conn,$sqlRating1s);
    $totalRating1s = mysqli_num_rows($rlRating1s);
    $tbcRating1s = round(($totalRating1s / $totalRating)*100,1);

    $sqlRating2s = "SELECT * FROM rating WHERE pro_id = '$pro_id' AND star = 2";
    $rlRating2s = mysqli_query($conn,$sqlRating2s);
    $totalRating2s = (double)$totalRating2s = mysqli_num_rows($rlRating2s);
    $tbcRating2s = round(($totalRating2s / $totalRating)*100,1);

    $sqlRating3s = "SELECT * FROM rating WHERE pro_id = '$pro_id' AND star = 3";
    $rlRating3s = mysqli_query($conn,$sqlRating3s);
    $totalRating3s = mysqli_num_rows($rlRating3s);
    $tbcRating3s = round(($totalRating3s / $totalRating)*100,1);

    $sqlRating4s = "SELECT * FROM rating WHERE pro_id = '$pro_id' AND star = 4";
    $rlRating4s = mysqli_query($conn,$sqlRating4s);
    $totalRating4s = mysqli_num_rows($rlRating4s);
    $tbcRating4s = round(($totalRating4s / $totalRating)*100,1);

    $sqlRating5s = "SELECT * FROM rating WHERE pro_id = '$pro_id' AND star = 5";
    $rlRating5s = mysqli_query($conn,$sqlRating5s);
    $totalRating5s = mysqli_num_rows($rlRating5s);
    $tbcRating5s = round(($totalRating5s / $totalRating)*100,1);

    array_push($output, [
        'totalRating' => $totalRating,
        'tbcRating' => $tbcRating,
        'tbcRating1s' => $tbcRating1s,
        'tbcRating2s' => $tbcRating2s,
        'tbcRating3s' => $tbcRating3s,
        'tbcRating4s' => $tbcRating4s,
        'tbcRating5s' => $tbcRating5s,
    ]);

    echo json_encode($output);
?> 