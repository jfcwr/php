<?php
	header('content-type:application:json;charset=utf8'); 
	header('Access-Control-Allow-Origin:*'); 
	header('Access-Control-Allow-Methods:POST'); 
	header('Access-Control-Allow-Headers:x-requested-with,content-type'); 
	date_default_timezone_set('PRC');
	$input = file_get_contents("php://input");


$header_data = []; //去掉 ChinaRailway- 後，先後須按字母順序
$header_data["ChinaRailway-Application"] = "11205"; //應用ID
$header_data["Content-Type"] = "application/json";
$header_data["ChinaRailway-Event"] = "Pay";
$header_sign_str = getHeadSingStr($header_data);
/**
* header k=>v 資料，取得加密字串
* @param array $header_data
* @return string
*/
function getHeadSingStr($header_data) {
	$sign_arr = [];
	foreach ($header_data as $k => $v) {
		if ("ChinaRailway-Signature" == $k || "ChinaRailway-Code" == $k || "" === $v) {
			continue;
		}
		$sign_arr[preg_replace("/^ChinaRailway-/", "", $k)] = $v;
	}
	$header_sign_str = http_build_query($sign_arr);
	return $header_sign_str;
}

//生成 sha256WithRSA 签名
function getSign($content, $privateKey){
    $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
        wordwrap($privateKey, 64, "\n", true) .
        "\n-----END RSA PRIVATE KEY-----";

    $key = openssl_get_privatekey($privateKey);
    openssl_sign($content, $signature, $key, OPENSSL_ALGO_SHA256);
    openssl_free_key($key);
    $sign = base64_encode($signature);
    return $sign;
}

$sign_data_hs = hash_hmac('sha256', $header_sign_str. json_encode(json_decode($input),JSON_UNESCAPED_UNICODE), base64_decode('xiZgYsxagQWmtW2cNPA2L9hZ299jGa7wpEiL2SV75OA='));
$sign=null;
$privateKey = "MIICXAIBAAKBgQCU1Zax/xxWW63qnpMHy+kMqFYQVILYgUOJ67ceZI+zydZO1RjHz/QJVnfYnjQGz3TOeQ7X6q3LI0ebbzhpp77YnRpqGZ9b27jWDxWcS/7Gag/VoNJEFQ6hIYcJzV+4Z/me6JUhwWvhoTVnfuddOZsXdXMWWLnwqL7atGxAZzbdUQIDAQABAoGAPnQcDqgCz5gb9CpDf0+JBQc0Shj0aP5hBWR0jFvdUy+8bosMIXh5Klvu4WaNZVgzZxq0aFUxRV8SAr7aeLdrpNz/dZo7Iu+5XHiDCsbc4XJwqES+EHHMZPam/21gIlVydm8o3I0R1lMDIugRl4mXAcx6BKbff21uOD3BnP1Kv2MCQQDFew6agKJvK37AWiLGIMIri2zg1pggyIoCsRrqAndMeVVTk75lQq44qacNaRznXVaaSMGcohjRASbKGNXQahQ3AkEAwPA0V2pNSJkpXCHkt04zPfGZ09cwM+IWN5CwIOoPIum1GVVpw+L63g2DorM8lP/vD6Q2JYnetqC49zTCgg/mtwJAfgVfdl0sS0E4a8uZcVIqVttsqbaH8jPHTpxyjm7wbo7nUqRv1fLNdz9caYRwe3zm7SIM28XdjFLty/45Q/zN1QJAG6cRyWmZ9ArHq8XL0x3pKDqlcoBID1ebGZGqPL1vN/DJHP1p8EJq9EJ5mGsaBrz71YESpaSx//jfKcctDZkb1wJBAIRHDDedkmcvFpvE8G5OvSoyJyiE1JqIPb1lAlxcogGnqgzsEnd95KYfXZkO2ZkYMR+4TWJYboSWPUDsNID753E=";

$sign = getSign($sign_data_hs,$privateKey);
echo($sign_data_hs.".".$sign);
?>