<?php
	header('content-type:application:json;charset=utf8'); 
	header('Access-Control-Allow-Origin:*'); 
	header('Access-Control-Allow-Methods:POST'); 
	header('Access-Control-Allow-Headers:x-requested-with,content-type'); 
	date_default_timezone_set('PRC');
	$input = file_get_contents("php://input");


$header_data = []; //去掉 ChinaRailway- 後，先後須按字母順序
$header_data["ChinaRailway-Application"] = "11204"; //應用ID
$header_data["Content-Type"] = "application/json";
$header_data["ChinaRailway-Event"] = "Charge";
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

//验证 sha256WithRSA 签名
function verify($content, $sign, $publicKey){

    $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
    wordwrap($publicKey, 64, "\n", true) .
    "\n-----END PUBLIC KEY-----";

    $key = openssl_get_publickey($publicKey);
    $ok = openssl_verify($content,base64_decode($sign), $key, 'SHA256');
    openssl_free_key($openssl_public_key);
    return $ok;
}

function Hex2String($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}

$sign_data_hs = hash_hmac('sha256', $header_sign_str. json_encode(json_decode($input),JSON_UNESCAPED_UNICODE), base64_decode('xiZgYsxagQWmtW2cNPA2L9hZ299jGa7wpEiL2SV75OA='));
$sign=null;
$privateKey = "MIICWwIBAAKBgQCyz5dWbgtFk9QtXQPQhQHQeE+NAhR6qEYagm9aRZag9VHLOnJQleIcjNIxD3UZKluKGC9K9iqcttgZQOK/CuKmpI9OvHKZMhBrhz/mCKU5MNWA1EKr6fJfm9fY+8Xs141XKbJ2nikBas9TiVid669B4g14dMUeUZIuvWzgjqmxNwIDAQABAoGAGFCkpohMoxemRMw/DmC6gu6L+W5Hek3InqaPCCpUqyrrhk8P0sfW7q/y4zX+uT8qdyhbwf312cf+scxs07K3aDHh5Cd1ltxhjdQcahPEmN8FU6pJd1G8NuxV2lXDYMe7fJl7ETmRasif3/leRJ6mvLsSkcSe7hs7Vvp03kC5yIECQQDWj9dbJnVYLj5umxwiZCvChCJhWgg4SVwi9SCm6ZOdfcVjB2LfDK/fNf5vAv2/7ZcUn0F+oVeoU6WAPEteJzi3AkEA1VgvYNN2jvgLfLBZpxhmwV96QcsYvIlpG41cbyTANK+rELf+OhsY32+CccYXrq74VY758AGBHHXe0u/wVXvLgQJADxRg8FkNmjr1zFQ+b/RWVv9uYyUuurX9Mb/EobZ8127FdqZIPqDno2pqDWdKajjKi3p94eZ5AK2QTijuqxlalQJANGNUNwWWPmNAhtzTFwoR6XglkFRr/ZgMfFyJDy3KGOXuLsvPnLscr3k1YfTPyfBjQLc8PqhP6Qo0rj2HGaatgQJAUgqw6/uYHQVzfN8c0SXcRQql+lCVT/4I4fEUBzOZROpYpAKRqJOQiPj+G1OAsS6M7O4xCCxp46JP6mOOBjTvsA==";

$sign = getSign($sign_data_hs,$privateKey);
echo($sign_data_hs.".".$sign);
?>