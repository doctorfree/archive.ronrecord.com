<?php
/* Copyright (c) 2011, Missy Restless
 * All rights reserved.
 ******************************************************************************/

include 'wa_wrapper/WolframAlphaEngine.php';

ini_set('default_charset', 'UTF-8');
ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-GB; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');

$appID = 'AJ8PYR-5VG39V43X5';

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
    if (!isset($_GET['lang'])) {
        $lang = "en";
    } else {
        $lang = stripslashes($_GET['lang']);
    }
    $search = stripslashes($_GET['query']);
    // instantiate an engine object with your app id
    $engine = new WolframAlphaEngine($appID);

    // construct a basic query to the api
    $response = $engine->getResults($search);

    // getResults will send back a WAResponse object
    // this object has a parsed version of the wolfram alpha response
    // as well as the raw xml ($response->rawXML) 
  
    // we can check if there was an error from the response object
    if ($response->isError) {
        header('HTTP/1.1 404 Not Found');
    }
    // echo $response->rawXML;
    // if there are any pods, display them
    if (count($response->getPods()) > 0) {
        foreach ($response->getPods() as $pod) {
            echo $pod->attributes['title'] . " :\n\t";
            foreach ($pod->getSubpods() as $subpod) {
                echo $subpod->plaintext . "\n\t";
            }
	    echo "\n";
        }
    }
}
else
{
    header( 'Location: http://www.google.com/' ) ;
}
?>

