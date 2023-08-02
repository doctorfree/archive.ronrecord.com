<?php
// Copyright (c) 2010,2011 Missy Restless
// All rights reserved.
//

include 'wa_wrapper/WolframAlphaEngine.php';

function spell_check ($qwery)
{
    $BASE_URL = "https://query.yahooapis.com/v1/public/yql";
    $suggestion="";
     
    $yql_query = "select * from search.spelling where query='$qwery' and appid='Ymm43krV34G3QtCr6sZKgLs2EPtX6o8bQF6I1DK_aIMdQ6s_AjNWkuc1YOx4HHo3Qq36mg--'";
    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
    $session = curl_init($yql_query_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
    $response = curl_exec($session);
    require_once('JSON.php'); 
    $json = new Services_JSON(); 
    $phpObj = $json->decode($response);
    if(!is_null($phpObj->query->results)){
      $suggestion = $phpObj->query->results->suggestion;
    }
    return $suggestion;
}

ini_set('default_charset', 'UTF-8');
ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-GB; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');

if($_GET['site'] == 'wikipedia')
{
    if (!isset($_GET['lang'])) {
        $lang = "en";
    } else {
        $lang = stripslashes($_GET['lang']);
    }
    $search = stripslashes($_GET['query']);
    $url = str_replace(' ','_',$search);
    $text = file_get_contents("http://{$lang}.wikipedia.org/wiki/{$url}");
    $text = preg_replace('~</?[ai].*?>~is','',$text);
    $oldtext = $text;
    preg_match('~<title>(.+?) - Wikipedia, the free encyclopedia</title>~i',$text,$matches);
    $path = urlencode(str_replace(' ','_',$matches[1]));
    $title = $matches[1];
    while((strpos($oldtext,'</table>') < strpos($oldtext,'<p>') && strpos($oldtext,'</table>') !== false))
    {
        $oldtext = $text;
        $text = substr($text,strpos($text,'</table>')+8);
    }
    $oldtext = preg_replace('~<div class=.+?>.+?</div>~is','',$oldtext);
    if(!preg_match('~<p>([^<>]{0,15}<b>[^<>]+?</b>[^<>]{2,2}.+?)</p>~is',$oldtext,$matches))
    {
        preg_match('~<p>(.+?)</p>~is',$oldtext,$matches);
    }
    $lines = wordwrap(html_entity_decode(strip_tags($matches[0]), ENT_QUOTES, 'UTF-8'),1000,"<br>",true);
    if (empty($lines)) {
        header('HTTP/1.1 404 Not Found');
    }
    else {
        print "<result success=\"0\"><input>".htmlentities($search)."</input><that> {$lines}</that>";
    }
}
else if($_GET['site'] == 'wolfram')
{
    if (isset($_GET['lang'])) {
        $lang = stripslashes($_GET['lang']);
    } else {
        $lang = "en";
    }
    $appID = 'AJ8PYR-5VG39V43X5';
    if (isset($_GET['appID'])) {
        $appID = stripslashes($_GET['appID']);
    }
    $search = stripslashes($_GET['query']);
    // instantiate an engine object with your app id
    $engine = new WolframAlphaEngine($appID);

    // construct a basic query to the api
    //$response = $engine->getResults($search);
    $response = $engine->getResults($search, array(format => 'plaintext'));

    // getResults will send back a WAResponse object
    // this object has a parsed version of the wolfram alpha response
    // as well as the raw xml ($response->rawXML) 
  
    $answer = "";
    // we can check if there was an error from the response object
    if ($response->isError) {
        header('HTTP/1.1 404 Not Found');
    }
    else {
        // if there are any pods, display them
        if (count($response->getPods()) > 0) {
	    echo "<result success=\"0\"><input>".$search."</input><message>";
            foreach ($response->getPods() as $pod) {
                if ($pod->attributes['title'] != "Visual representation") {
                  if ($pod->attributes['title'] != "Number line") {
                    if ($pod->attributes['title'] == "Input interpretation") {
                      foreach ($pod->getSubpods() as $subpod) {
                          echo $subpod->plaintext;
                      }
	              echo " : \n";
                    }
                    else {
                      $plaintext_subpods = "";
                      foreach ($pod->getSubpods() as $subpod) {
                        $plaintext_subpods .= $subpod->plaintext;
                      }
                      if (!empty($plaintext_subpods)) {
			echo $pod->attributes['title'] . " : \n";
                        foreach ($pod->getSubpods() as $subpod) {
                            echo $subpod->plaintext;
                        }
	                echo "\n";
                      }
                    }
                  }
                }
            }
            echo "</message>";
        }
	else {
            header('HTTP/1.1 404 Not Found');
        }
    }
}
else if($_GET['site'] == 'bitly')
{
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
    echo $short_url;
}
else if($_GET['site'] == 'yahoo')
{
    $search = stripslashes($_GET['query']);
    $spell = spell_check($search);
    print "<result success=\"0\"><input>".htmlentities($search)."</input><that> {$spell}</that>";
}
else
{
    header( 'Location: http://www.google.com/' ) ;
}
?>

