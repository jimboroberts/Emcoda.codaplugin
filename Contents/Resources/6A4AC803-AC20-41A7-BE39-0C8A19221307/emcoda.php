#!/usr/bin/php
<?php
/*--------------------------------------------------------------
emcoda.php

Version:	2.1
Author:		James Roberts
Website: 	http://www.method.org.uk

Copyright 2003-2014 James Roberts. 
This code cannot be redistributed for profit without
permission from http://www.method.org.uk/

More info at: http://www.method.org.uk/
--------------------------------------------------------------*/

$input = '';
$ext = '';

$fp = fopen("php://stdin", "r");
while ( $line = fgets($fp, 1024) )
	$input .= $line;
fclose($fp);
	
if(strlen(trim($input))>0) {
										
		$comment_open 		= "<!--";
		$comment_close 		= "-->";

		echo $comment_open;
		echo "EMCODA HASHED EMAIL";
		echo $comment_close;
		echo "\n";
		
		$data = $input;
		list($email, $link) = explode(",", $data);

		echo encodehash($email,$link);
		
}

function transpose($str) {
    # function takes the string and swaps the order of each group of 2 chars
    $len = strlen($str);
    $ret = "";
    for ($i=0; $i<$len; $i=$i+2) {
		if ($i+1 == $len)
			$ret .= substr($str, $i, 1);
		else
			$ret .= sprintf("%s%s", substr($str, $i+1, 1), substr($str, $i, 1));
		}
	return $ret;
}
  
function escapeencode ($str) {
    $ret = "";
    $arr = unpack("C*", $str);
    foreach ($arr as $char)
      $ret .= sprintf("%%%X", $char);
    return $ret;
}

function encodehash($href, $text) {
    $prepend = "";
    if (preg_match("/^mailto:/", $href)) {
		$href = preg_replace("/^mailto:/", "", $href);
		$prepend = "mailto:";
    }
    if (preg_match("/^http:\/\//", $href)) {
		$href = preg_replace("/^http:\/\//", "", $href);
		list($server,$url) = split("/", $href, 2);
		$href = $url;
		$prepend = "http://$server/";
    }
    $UserCode = sprintf("<a href=\"%s%s\">%s</a>",
		$prepend,
		escapeencode($href), $text);
    return $UserCode;
}

?>