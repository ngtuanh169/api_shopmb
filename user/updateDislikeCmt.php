<?php
    include("../connect.php");

    include("../auth/jwt.php");

    $headers = getallheaders();

    $accessToken = $headers['access_token'];

    $vrAccessToken = verifyAccessToken($accessToken);

    if($vrAccessToken['err']){

        die();

    } 

    $arr = explode('.', $accessToken);

    $base64Payload = $arr[1];

    $jsonPayload = base64_decode($base64Payload);

    $payload = json_decode($jsonPayload, true);

    $token_id = $payload['id'];

    if($token_id != $_POST['user_id']){

        die();

    } 

    
    $output = [];

    $dislikeComments = [];

    $id = isset($_POST['user_id']) && $_POST['user_id'] != '' ? $_POST['user_id'] : '';

    $cmt_id = isset($_POST['cmt_id']) && $_POST['cmt_id'] != '' ? $_POST['cmt_id'] : '';

    if(empty($id) && empty($cmt_id)){

        die();

    }

    //data user

    $sql = "SELECT * FROM user  WHERE user_id = '$id'";
        
    $rl = mysqli_query($conn,$sql);

    $data = mysqli_fetch_assoc($rl);

    //data comment

    $sql_cmt = "SELECT * FROM comments  WHERE id = '$cmt_id'";
        
    $rl_cmt = mysqli_query($conn,$sql_cmt);

    $data_cmt = mysqli_fetch_assoc($rl_cmt);

    

    if(!empty($data['like_comments'])){

        $likeComments = json_decode($data['like_comments'],true);

       for ($i=0; $i < count($likeComments) ; $i++) { 

            if($likeComments[$i] == $cmt_id){

                unset($likeComments[$i]);

                //update db user

                $likeComments_ud = json_encode(array_values($likeComments));

                $sql_ud = "UPDATE user SET like_comments = '$likeComments_ud' WHERE user_id = $id";

                $rl_ud = mysqli_query($conn,$sql_ud);

                //update db comments

                $coutLikes = $data_cmt['likes'] - 1;

                $sql_cmt = "UPDATE comments SET likes = '$coutLikes' WHERE id = $cmt_id";

                $rl_cmt = mysqli_query($conn,$sql_cmt);
            }
        }
    }


    if(empty($data['dislike_comments'])){

        array_push($dislikeComments, $cmt_id);

        $dislikeComments_ud = json_encode($dislikeComments);

        //update db user

        $sql_ud = "UPDATE user SET dislike_comments = '$dislikeComments_ud' WHERE user_id = $id";

        $rl_ud = mysqli_query($conn,$sql_ud);

        //update db comments

        $coutDislikes = $data_cmt['dislikes'] + 1;

        $sql_cmt = "UPDATE comments SET dislikes = '$coutDislikes' WHERE id = $cmt_id";

        $rl_cmt = mysqli_query($conn,$sql_cmt);

                 
        array_push($output,['status'=> 'success', 'message'=> 'Cập nhật trạng thái thành công!']);

        echo json_encode($output);

        die();

    }

    $dislikeComments = json_decode($data['dislike_comments'],true);

    for ($i=0; $i < count($dislikeComments) ; $i++) { 

        if($dislikeComments[$i] == $cmt_id){

            unset($dislikeComments[$i]);

            $dislikeComments  = array_values($dislikeComments);

            //update db user

            $dislikeComments_ud = json_encode($dislikeComments);

            $sql_ud = "UPDATE user SET dislike_comments = '$dislikeComments_ud' WHERE user_id = $id";

            $rl_ud = mysqli_query($conn,$sql_ud);

            //update db comments

            $coutDislikes = $data_cmt['dislikes'] - 1;

            $sql_cmt = "UPDATE comments SET dislikes = '$coutDislikes' WHERE id = $cmt_id";

            $rl_cmt = mysqli_query($conn,$sql_cmt);

                 
            array_push($output,['status'=> 'success', 'message'=> 'Cập nhật trạng thái thành công!']);

            echo json_encode($output);

            die();
        }
    }

    array_push($dislikeComments, $cmt_id);

    //update db user

    $dislikeComments_ud = json_encode($dislikeComments);

    $sql_ud = "UPDATE user SET dislike_comments = '$dislikeComments_ud' WHERE user_id = $id";

    $rl_ud = mysqli_query($conn,$sql_ud);   

    //update db comments

    $coutDislikes = $data_cmt['dislikes'] + 1;

    $sql_cmt = "UPDATE comments SET dislikes = '$coutDislikes' WHERE id = $cmt_id";

    $rl_cmt = mysqli_query($conn,$sql_cmt);



    array_push($output,['status'=> 'success', 'message'=> 'Cập nhật trạng thái thành công!']);

    echo json_encode($output);
    
?>