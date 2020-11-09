<?php

function tag_verify($tag) {

	if (preg_match("/^[a-z]{2}-?[a-z]{1,21}$/", $tag)) {
		return true;
	} else {
		return false;
	}

}