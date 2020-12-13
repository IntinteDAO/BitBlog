<?php

function remove_markdown_chars($text) {
	$text = str_replace('#', '', $text);
	$text = str_replace('\n', '', $text);
	$text = str_replace('\r', '', $text);
	$text = str_replace('*', '', $text);
	$text = str_replace('\\', '', $text);
	return $text;
}