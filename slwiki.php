<?php
/* Copyright (c) 2010, Missy Restless
 * All rights reserved.
 *
 * Portions Copyright (c) 2007, Katharine Berry
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of Katharine Berry nor the names of any contributors
 *       may be used to endorse or promote products derived from this software
 *       without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY KATHARINE BERRY ``AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL KATHARINE BERRY BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 ******************************************************************************/

function spell_check ($qwery)
{
    $BASE_URL = "https://query.yahooapis.com/v1/public/yql";
    $suggestion="";
     
    $yql_query = "select * from search.spelling where query='$qwery'";
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

if($_GET['site'] == 'slwiki')
{
    $search = stripslashes($_GET['query']);
    $url = str_replace(' ','_',$search);
    $text = file_get_contents("http://wiki.secondlife.com/wiki/{$url}");
    $text = preg_replace('~</?[ai].*?>~is','',$text);
    $oldtext = $text;
    preg_match('~<title>(.+?) - Second Life Wiki</title>~i',$text,$matches);
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

