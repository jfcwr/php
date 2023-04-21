<?php
	header('content-type:application:json;charset=utf8'); 
	header('Access-Control-Allow-Origin:*'); 
	header('Access-Control-Allow-Methods:POST'); 
	header('Access-Control-Allow-Headers:x-requested-with,content-type'); 
	date_default_timezone_set('PRC');
	$input = file_get_contents("php://input");

//生成 sha256WithRSA 签名
function getSign($str){
    $re=hash('sha256', $str, true);
    return bin2hex($re);
}

$sign = getSign($input);
echo($sign);
?>