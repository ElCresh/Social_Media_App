<?php

$redirected = false;
$location = "";

//$url = 'https://www.microsoft.com/it-it/';
//$url = 'https://httpstat.us/403';
//$url = 'https://sormani.it/';
//$url = 'https://www.google.com/';
$url = 'https://old.reddit.com/';

$curl = curl_init();

echo "> Making requst to: ".$url;

$cookies = array();
$tech_detected = array();
$redirected = false;

curl_setopt_array($curl, array(
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL => $url,
	CURLOPT_USERAGENT => 'User Agent X'
));
//curl_setopt($curl, CURLOPT_VERBOSE, 1);
curl_setopt($curl, CURLOPT_HEADER, 1);
curl_setopt($curl, CURLINFO_CERTINFO, 1);

$response = curl_exec($curl);

$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);

curl_reset($curl);

$headers = explode("\n", $header);
print_r($headers);

foreach($headers as $header){
	// Detecting HTTP/2 Status
	$tmp = explode("HTTP/2 ",$header);
	//print_r($tmp);
	if(strlen($tmp[0]) != strlen($header)){
		echo "HTTP Status: ".$tmp[1];
		$tmp = explode("301",$header);
		if(strlen($tmp[0]) != strlen($header)){
			$redirected = true;
		}
	}

	// Detecting HTTP/1.1 Status
	$tmp = explode("HTTP/1.1 ",$header);
	//print_r($tmp);
	if(strlen($tmp[0]) != strlen($header)){
		echo "HTTP Status: ".$tmp[1];
	}

	// Detecting server tag 
	$tmp = explode("Server: ",$header);
	//print_r($tmp);
	if(strlen($tmp[0]) != strlen($header)){
		$server_details = explode('/',$tmp[1]);
		if(strlen($tmp[1]) != strlen($server_details[0])){
			echo "\nServer type: ".$server_details[0];
			echo "\nServer version: ".$server_details[1];
		}else{
			echo "\nServer type: ".$tmp[1];
		}
	}

	// Detecting server tag without capital letter 
	$tmp = explode("server: ",$header);
	//print_r($tmp);
	if(strlen($tmp[0]) != strlen($header)){
		$server_details = explode('/',$tmp[1]);
		if(strlen($tmp[1]) != strlen($server_details[0])){
			echo "\nServer type: ".$server_details[0];
			echo "\nServer version: ".$server_details[1];
		}else{
			echo "\nServer type: ".$tmp[1];
		}
	}

	// Detecting ASP.NET tag 
	$tmp = explode("X-AspNet-Version: ",$header);
	if(strlen($tmp[0]) != strlen($header)){
		$tech_detected[] = "ASP.NET ".$tmp[1];
	}

	// Detecting Powered By tag 
	$tmp = explode("X-Powered-By: ",$header);
	if(strlen($tmp[0]) != strlen($header)){
		$tech_detected[] = $tmp[1];
	}

	// Detecting Content-Type 
	$tmp = explode("Content-Type: ",$header);
	if(strlen($tmp[0]) != strlen($header)){
		echo "\nResponse content type: ".$tmp[1];
	}

	// Detecting Cookies 
	$tmp = explode("set-cookie: ",$header);
	if(strlen($tmp[0]) != strlen($header)){
		$cookies[] = explode(";", $tmp[1]);
	}

	// Detecting redirect location 
	$tmp = explode("location: ",$header);
	if(strlen($tmp[0]) != strlen($header)){
		$location = $tmp[1];
	}
}

if(!empty($tech_detected)){
	echo "\n\nDetected technology:";
	foreach($tech_detected as $tech){
		echo "\n- ".$tech;
	}
}

if(!empty($cookies)){
	echo "\n\nCookies:";
	foreach($cookies as $index=>$cookie){
		echo "\n [".$index."]";
		foreach($cookie as $param){
			echo "\n  ".$param;
		}
	}
}

if($redirected && $location != ""){
	echo "\nMoved to: ".$location;
	$url = $location;
}

echo "\n\n";

curl_close($curl);

?>
