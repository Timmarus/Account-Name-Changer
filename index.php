<?php

//Change these to your information
$accountName = 'datrageguy@gmail.com'; //Current account Mojang login username or email
$accountPassword ='Minecraft1'; //Current account password
$newName = ''; //Desired new username
$time = ''; //Time the account becomes available in the following format: mm/dd/yy hh:mm:ss
//

$accountUrl = 'https://account.mojang.com/login';
$cookiePath = $_SERVER['DOCUMENT_ROOT'] . "/cookie.txt";
//Creating fields

$fields = array(
			'username' => urlencode($accountName),
			'password' => urlencode($accountPassword),
	);
$fields_string = '';
foreach($fields as $key => $value) {
	$fields_string .= $key . '=' . $value . '&';
}
rtrim($fields_string, '&');

$ch = curl_init($accountUrl);

//CURL Options
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath);

$result = curl_exec($ch);
if ($result == false) {
	echo "Curl error: " . curl_error($ch);
}
else {
	echo $result;
}


curl_close($ch);

echo $result;