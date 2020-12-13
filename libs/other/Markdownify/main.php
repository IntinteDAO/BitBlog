<?php

function HTML2MD($text) {
	$converter = new Markdownify\Converter;
	return str_replace('˂', '\\<', $converter->parseString(str_replace('\\<', '˂', nl2br($text, false))));
}