<?php
    include("../connect.php");

    include("../auth/jwt.php");

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

    if ($token_id != $_POST['user_id']) {
        
        die();
    }



    $output = [];

    $likeComments = [];

    $id = isset($_POST['user_id']) && $_POST['user_id'] != '' ? $_POST['user_id'] : '';

    $cmt_id = isset($_POST['cmt_id']) && $_POST['cmt_id'] != '' ? $_POST['cmt_id'] : '';

    if(empty($id) && empty($cmt_id)){

        die();

    }

    //data user

    $sql = "SELECT * FROM user  WHERE user_id = $id";
        
    $rl = mysqli_query($conn,$sql);

    $data = mysqli_fetch_assoc($rl);

    //data comment

    $sql_cmt = "SELECT * FROM comments  WHERE id = '$cmt_id'";
        
    $rl_cmt = mysqli_query($conn,$sql_cmt);

    $data_cmt = mysqli_fetch_assoc($rl_cmt);



    if(!empty($data['dislike_comments'])){

       $dislikeComments = json_decode($data['dislike_comments'],true);

       for ($i=0; $i < count($dislikeComments) ; $i++) { 

            if($dislikeComments[$i] == $cmt_id){

                unset($dislikeComments[$i]);

                //update db user

                $dislikeComments_ud = json_encode(array_values($dislikeComments));

                $sql_ud = "UPDATE user SET dislike_comments = '$dislikeComments_ud' WHERE user_id = $id";

                $rl_ud = mysqli_query($conn,$sql_ud);
                     
                //update db comments

                $coutDislikes = $data_cmt['dislikes'] - 1;

                $sql_cmt = "UPDATE comments SET dislikes = '$coutDislikes' WHERE id = $cmt_id";

                $rl_cmt = mysqli_query($conn,$sql_cmt);
            }
        }
    }


    if(empty($data['like_comments'])){

        array_push($likeComments, $cmt_id);

        //update db user

        $likeComments_ud = json_encode($likeComments);

        $sql_ud = "UPDATE user SET like_comments = '$likeComments_ud' WHERE user_id = $id";

        $rl_ud = mysqli_query($conn,$sql_ud);

        //update db comments

        $coutlikes = $data_cmt['likes'] + 1;

        $sql_cmt = "UPDATE comments SET likes = '$coutlikes' WHERE id = $cmt_id";

        $rl_cmt = mysqli_query($conn,$sql_cmt);
                 
        array_push($output,['status'=> 'success', 'message'=> 'Cập nhật trạng thái thành công!']);

        echo json_encode($output);

        die();

    }

    $likeComments = json_decode($data['like_comments'],true);

    for ($i=0; $i < count($likeComments) ; $i++) { 

        if($likeComments[$i] == $cmt_id){

            unset($likeComments[$i]);

            //update db user

            $likeComments_ud = json_encode(array_values($likeComments));

            $sql_ud = "UPDATE user SET like_comments = '$likeComments_ud' WHERE user_id = $id";

            $rl_ud = mysqli_query($conn,$sql_ud);

            //update db comments

            $coutlikes = $data_cmt['likes'] - 1;

            $sql_cmt = "UPDATE comments SET likes = '$coutlikes' WHERE id = $cmt_id";

            $rl_cmt = mysqli_query($conn,$sql_cmt);


            var_dump(array_values($likeComments));

            var_dump($likeComments_ud);

            die();
        }
    }

    array_push($likeComments, $cmt_id);

    //update db user

    $likeComments_ud = json_encode($likeComments);

    $sql_ud = "UPDATE user SET like_comments = '$likeComments_ud' WHERE user_id = $id";

    $rl_ud = mysqli_query($conn,$sql_ud);

    //update db comments

    $coutlikes = $data_cmt['likes'] + 1;

    $sql_cmt = "UPDATE comments SET likes = '$coutlikes' WHERE id = $cmt_id";

    $rl_cmt = mysqli_query($conn,$sql_cmt);

                 
    array_push($output,['status'=> 'success', 'message'=> 'Cập nhật trạng thái thành công!']);

    echo json_encode($output);
    
?>