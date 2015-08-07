<?php

//Change these to your information
$accountLoginName = 'alt152@mattbenson.net'; //Current account Mojang login username or email
$accountUsername = 'spacecadet18'; //Current in-game username
$accountPassword = 'dev123'; //Current account password
$newName = 'rqwerdasfd'; //Desired new username
$time = ''; //Time the account becomes available in the following format: mm/dd/yy hh:mm:ss
//

$accountUrl = 'https://account.mojang.com/login';
$cookiePath = $_SERVER['DOCUMENT_ROOT'] . "/cookie.txt";
$uuid = file_get_contents('https://api.mojang.com/users/profiles/minecraft/' . $accountUsername);
if (!empty($uuid)) {
	$uuid = explode(':', $uuid);
	$uuid = explode(',', $uuid[1]);
	$uuid = str_replace('"', '', $uuid[0]);
}
else {
	die("Invalid username");
}


//Creating fields

$fields = array(
			'username' => $accountLoginName,
			'password' => $accountPassword,
			'remember' => 'true'
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
//curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath);

$result = curl_exec($ch);
if ($result == false) {
	die("Curl error: " . curl_error($ch));
}
else {
	//echo $result;
	curl_setopt($ch, CURLOPT_URL, 'https://account.mojang.com/me/renameProfile/' . $uuid);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiePath);
	$result2 = curl_exec($ch);
	if ($result2 == false) {
		die("Curl error: " . curl_error($ch));
	}
	else {
		echo $result2;
		$authToken = explode('name="authenticityToken" value="', $result2);
		$authToken = explode('">', $authToken[1]);
		$authToken = $authToken[0];
		$fields = array(
					'newName' => $newName,
					'password' => $accountPassword,
					'authenticityToken' => $authToken);
		$fields_string = '';
		foreach($fields as $key => $value) {
			$fields_string .= $key . '=' . $value . '&';
		}
		rtrim($fields_string, '&');
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		$result3 = curl_exec($ch);
		if (stripos($result3, 'errors') !== false || stripos($result3, 'error') !== false) {
			echo "An error occured:<br /><br />" . $result3;
		}
		else {
			echo "Username changed successfully";
		}
	}
}

curl_close($ch);
