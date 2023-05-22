<?php 
	include("../connect.php");

	include("./jwt.php");

	$output = [];

	$headers = getallheaders();

	$refreshToken = $headers['refresh_token'];

	$vrRefreshToken = verifyRefreshToken($refreshToken);

	if($vrRefreshToken['err']){

		die();

	}
	$detached = explode('.', $refreshToken);

	// lấy payload trong token
	$base64Payload = $detached[1];

	// giải mã base64 
	$payloadJson = base64_decode($base64Payload);

	// json decode
	$payload = json_decode($payloadJson, true);

	$accessToken = createAccessToken($payload);

    echo $accessToken;
?>