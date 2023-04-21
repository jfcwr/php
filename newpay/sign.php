<?php
	header('content-type:application:json;charset=utf8'); 
	header('Access-Control-Allow-Origin:*'); 
	header('Access-Control-Allow-Methods:POST'); 
	header('Access-Control-Allow-Headers:x-requested-with,content-type'); 
	date_default_timezone_set('PRC');
	$input = file_get_contents("php://input");

$key       = '7B7510651F8248B577CB0BA8C1102753';
$card_pass = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQD8pYhMI2F+O7YTJaiUo9feRrNCB+wVlo8QGqfGKs7CtHEwYg41fadwH25bp4lsZQtxCGr42WdRsIgji5JtLN0yZiBFH76hBdauOdbmcFbT0VlxpNmZflDbv/ufrdFJEeqiY18x2PKAb56XNddhIc/LCyZyUSKezNZCx227T3c+XwIDAQAB';
$inputObj = json_decode($input);
$str =  json_encode([
			'amount'       => $inputObj->amount,
			'notify_url'   => $inputObj->notify_url,
			'out_trade_no' => $inputObj->out_trade_no,
			'partner_id'   => $inputObj->partner_id,
			'payType'      => $inputObj->payType,
			'sendTime'     => $inputObj->sendTime,
	],JSON_UNESCAPED_SLASHES);

$rsamsg = encrypt($str,formatKey($card_pass,'public'));
function encrypt($originalData,$key){
	$crypto = '';
	foreach (str_split($originalData, 117) as $chunk) {
		openssl_public_encrypt($chunk, $encryptData, $key,OPENSSL_PKCS1_PADDING);
		$crypto .= $encryptData;
	}
	return base64_encode($crypto);
}
/**
* 格式化RSA的key格式-多种支付会使用到
* @param $key
* @param string $type
* @return string
*/
function formatKey($key, $type = 'public'){
	$key = str_replace("-----BEGIN PRIVATE KEY-----", "", $key);
	$key = str_replace("-----END PRIVATE KEY-----", "", $key);
	$key = str_replace("-----BEGIN PUBLIC KEY-----", "", $key);
	$key = str_replace("-----END PUBLIC KEY-----", "", $key);
	$key = _trimAll($key);
	if ($type == 'public') {
		$begin = "-----BEGIN PUBLIC KEY-----\n";
		$end = "-----END PUBLIC KEY-----";
    } else {
		$begin = "-----BEGIN PRIVATE KEY-----\n";
		$end = "-----END PRIVATE KEY-----";
    }
	$key = chunk_split($key, 64, "\n");
	return $begin . $key . $end;
}

function _trimAll($str){
	$qian = array(" ", " ", "\t", "\n", "\r");
	$hou = array("", "", "", "", "");
	return str_replace($qian, $hou, $str);
}
;
$sign = json_encode([
	'rsamsg' => urlencode($rsamsg),
	'md5msg' => md5($str.$key),
],JSON_UNESCAPED_SLASHES);
echo($sign);
?>