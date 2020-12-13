<?php

function verify_markdown($markdown) {

	preg_match_all("/!?\[.*\]\(.*\)+/mU", $markdown, $keywords);
	$keywords = $keywords[0];
	$keywords = array_unique($keywords);

	if(!empty($keywords)) {

		for($i=0; $i<=count($keywords)-1; $i++) {

			$type = 0;
			// Check name
			if($keywords[$i][0] != "!") { $type = 1; }
			if(!empty($type)) {
				$name = explode("](", $keywords[$i])[0];
				$name = substr($name, 1);

				if(empty($name)) {
					$markdown = str_replace($keywords[$i], '', $markdown);
					continue;
				}
			}

			// Check link
			$link = explode("](", $keywords[$i])[1];
			$link = substr($link, 0, -1);

			if(filter_var($link, FILTER_VALIDATE_URL) == false) {
				$markdown = str_replace($keywords[$i], '', $markdown);
			}

		}
			return $markdown;
	} else {
		return $markdown;
	}

}