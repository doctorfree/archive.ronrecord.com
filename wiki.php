<?php
/* Copyright (c) 2007, Katharine Berry
 * Copyright (c) 2010, Missy Restless
 * All rights reserved.
 *
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

function unhtmlentities($string)
{
    // replace numeric entities
    $string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
    $string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
    // replace literal entities
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);
    return strtr($string, $trans_tbl);
}

if($_GET['site'] == 'wikipedia')
{
	$search = stripslashes($_GET['query']);
	$url = str_replace(' ','_',$search);
        ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-GB; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');
	$text = file_get_contents("http://en.wikipedia.org/wiki/{$url}");
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
	$lines = wordwrap(htmlentities(str_replace("\n",' ',preg_replace('~\[([0-9]+?|[a-z]+?needed)\]~is','',unhtmlentities(strip_tags($matches[0]))))),1000,"<br>",true);
	if (empty($lines)) {
		print "<result success=\"0\"> MISSING";
	}
	else {
		print "<result success=\"1\"> {$lines}";
	}
}
else
{
        header( 'Location: http://www.google.com/' ) ;
}
?>

