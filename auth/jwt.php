<?php

function headerToken($payload=null){ 
 
	$header = [
			"alg"=> "HS256",
		  	"typ"=> "JWT",
		];

	return $header;

}

function keyAccessToken($payload=null){

	return 'accessTokenSMB';

}

function keyRefreshToken($payload=null){

	return 'refreshTokenSMB';

}

function formatString($str=null){

	if($str){

		$strFormat = str_replace(['+', '/', '='], ['-', '_', ''], $str);

		return $strFormat;
	}
}

function createAccessToken($payload=null){

	$key_access = keyAccessToken();

	if(empty($payload)){
		return null;
	}

	$time = time();

	$payload['iat']= $time;

	$payload['exp']= $time + 120;

	$payload['type']= 'accessToken';

	return signJWT($payload,$key_access);
}

function createRefreshToken($payload=null){

	$key_refresh = keyRefreshToken();

	if(empty($payload)){
		return null;
	}

	$time = time();

	$payload['iat']= $time;

	$payload['exp']= $time + (60*60*24*7);

	$payload['type']= 'refreshToken';

	return signJWT($payload,$key_refresh);
	
}

function signJWT($payload=null, $key=null){

	$header = headerToken();
	// $alg = 'sha256';

	// json encode
	$headerJson = json_encode($header);

	$payloadJson = json_encode($payload);

	// mã hóa base64
	$base64Header = base64_encode($headerJson);

	$base64Payload = base64_encode($payloadJson);

	// thay thế ký tự trong chuỗi đã mã hóa base64
	$formatHeader = formatString($base64Header);

	$formatPayload = formatString($base64Payload);

	// mã hóa signature
	$msg = $formatHeader.".".$formatPayload;

	$signatureJson = hash_hmac('sha256', $msg, $key, true);

	// mã hóa base64 signature
	$base64Signature = base64_encode($signatureJson);

	// thay thế ký tự trong chuỗi đã mã hóa signature
	$signature = formatString($base64Signature);

	// nối header, payload, signature bằng dấu "."
	$jwt = $msg.".".$signature;

	return $jwt;
}
function verifyAccessToken($token){

	$key_access = keyAccessToken();

	return verifyJWT($token, $key_access);
}
function verifyRefreshToken($token=null){

	$key_refresh = keyRefreshToken();

	return verifyJWT($token, $key_refresh);
}
function verifyJWT($token, $key){

	$data=[];
  
  	if (!$token) {

		$data['err']=true;

		$data['msg']='Chưa có token';

		return $data;
	}
	
	// tách token thành mảng ['header','payload','signature']
	$detached = explode('.', $token);

	// kiểm tra nếu token không đúng định dạng
 	if (count($detached) != 3) {

 		http_response_code(403);

		$data['err']=true;

		$data['msg']='Token không đúng định dạng';

		return $data;
	}

	// lấy payload trong token
	$base64Payload = $detached[1];

	// giải mã base64 
	$payloadJson = base64_decode($base64Payload);

	// json decode
	$payload = json_decode($payloadJson, true);

	// tạo token mới bằng dữ liệu token cũ
	$jwt = signJWT($payload,$key);

	// kiểm tra nếu token hết hạn

	if ($payload['exp'] < time()) {

		http_response_code(401);

		$data['err']=true;

		$data['msg']='Token đã hết hạn';

		return $data;
	}

	// kiểm tra nếu token không trùng khớp

	if ($token != $jwt) {

		http_response_code(403);

		$data['err']=true;

		$data['msg']='Token không trùng khớp';

		return $data;
	}

	
	// token hợp lệ
	$data['err']=false;

	$data['user']=$payload;

	return $data;
}
?>

