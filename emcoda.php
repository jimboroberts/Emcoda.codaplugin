#!/usr/bin/php
<?php
/*--------------------------------------------------------------
emcoda.php

Version:	2.0
Author:		James Roberts
Website: 	http://www.method.org.uk

Copyright 2003-2013 James Roberts. 
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
		echo " ".trim($input)." ";
		echo $comment_close;
		echo "\n";
		echo $comment_open;
		echo " /".trim($input)." ";
		echo $comment_close;

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
  
function encodelink($href, $text) {
    $code = sprintf("var s='%s';var r='';for(var i=0;i<s.length;i++,i++){r=r+s.substring(i+1,i+2)+s.substring(i,i+1)}document.write('<a href=\"'+r+'\">%s</a>');", transpose($href), $text);
    $UserCode = sprintf("%s%s%s",
    "<SCRIPT type=\"text/javascript\">eval(unescape('",
    escapeencode($code),
    "'))</SCRIPT>");
    return $UserCode;
}

function encodelink_delayed($href, $text) {
	static $usecount = 0;
	$usecount++;
	$code = sprintf("function pgregg_transpose%d(h) {var s='%s';var r='';for(var i=0;i<s.length;i++,i++){r=r+s.substring(i+1,i+2)+s.substring(i,i+1)}h.href=r;}document.write('<a href=\"#\" onMouseOver=\"javascript:pgregg_transpose%d(this)\" onFocus=\"javascript:pgregg_transpose%d(this)\">%s</a>');", $usecount, transpose($href), $usecount, $usecount, $text);
	$UserCode = sprintf("%s%s%s",
        "<SCRIPT type=\"text/javascript\">eval(unescape('",
        escapeencode($code),
        "'))</SCRIPT>");
    return $UserCode;
}
?>