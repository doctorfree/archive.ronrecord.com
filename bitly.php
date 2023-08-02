<?php
// Copyright (c) 2010,2011 Missy Restless
// All rights reserved.
//

ini_set('default_charset', 'UTF-8');
ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-GB; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');

if($_GET['site'] == 'bitly')
{
//echo "In here 2";
    $longurl = stripslashes($_GET['query']);
    $login = "o_7cqk5mr1mf";
    $appkey = "R_9adb39fb497b31b32269f2d913cc5eb1";
    $shorten_url = "https://api-ssl.bitly.com/v3/shorten?login=".$login."&apiKey=".$appkey."&longUrl=".urlencode($longurl)."&format=txt";
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch,CURLOPT_URL,$shorten_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
    $short_url = curl_exec($ch);
    curl_close($ch);
echo "Short URL is ".$short_url;
}
?>

