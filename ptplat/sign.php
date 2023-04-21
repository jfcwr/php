<?php
	header('content-type:application:json;charset=utf8'); 
	header('Access-Control-Allow-Origin:*'); 
	header('Access-Control-Allow-Methods:POST'); 
	header('Access-Control-Allow-Headers:x-requested-with,content-type'); 
	$input = file_get_contents("php://input");
	$path = dirname(__FILE__);

//$url = "https://kioskpublicapi-am.hotspin88.com/admin/list";
//$url = "https://kioskpublicapi-am.hotspin88.com/customreport/getdata/reportname/PlayerGames/startdate/2021-11-04%2013:44:00/enddate/2021-11-04%2013:45:00/frozen/all/timeperiod/specify/page/1/perPage/500";
$url = str_replace(" ", "%20", $input);
$entity_key = '6a437c56605000c784cd1171b48d88baed7a54ce023b472e0cc87bdcdad451f187f095d5f2494a248382803ce4003704e641b310b6140c2a10a9fac3013ec180';
$header = array();
$header[] = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
$header[] = "Cache-Control: max-age=0";
$header[] = "Connection: keep-alive";
$header[] = "Keep-Alive:timeout=5, max=100";
$header[] = "Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3";
$header[] = "Accept-Language:es-ES,es;q=0.8";
$header[] = "Pragma: ";
$header[] = "X_ENTITY_KEY: " . $entity_key;
$tuCurl= curl_init();
curl_setopt($tuCurl, CURLOPT_URL, $url);
curl_setopt($tuCurl, CURLOPT_PORT , 443);
curl_setopt($tuCurl, CURLOPT_VERBOSE, 0);
curl_setopt($tuCurl, CURLOPT_HTTPHEADER, $header);
curl_setopt($tuCurl, CURLOPT_TIMEOUT, 60000 );
curl_setopt($tuCurl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($tuCurl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($tuCurl, CURLOPT_SSLCERT, $path . '/api/VND.pem');
curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($tuCurl, CURLOPT_SSLKEY, $path . '/api/VND.key');
$exec = curl_exec($tuCurl);
echo($exec);
curl_close($tuCurl);
//$data = json_decode($exec, TRUE);
//echo($data);
?>
