<?php

if(!empty($_GET['id'])) {
	if (preg_match("/^[a-f0-9]{64}$/", $_GET['id'])) {
		if(file_exists('articles/'.$_GET['id'].'.json')) {
			$nickname = 'fervi';
			$id_nickname = hash('sha256', $nickname);
			$upvote = !empty($_GET['upvote']);
			if($upvote == 1) {
				if(file_exists('indexes/downvotes/'.$_GET['id'].'/'.$id_nickname)) { unlink('indexes/downvotes/'.$_GET['id'].'/'.$id_nickname); }
				if(!file_exists('indexes/upvotes/'.$_GET['id'])) { mkdir ('indexes/upvotes/'.$_GET['id']); }
				if(!file_exists('indexes/upvotes/'.$_GET['id'].'/'.$id_nickname)) { touch('indexes/upvotes/'.$_GET['id'].'/'.$id_nickname); }
			} else {
				if(file_exists('indexes/upvotes/'.$_GET['id'].'/'.$id_nickname)) { unlink('indexes/upvotes/'.$_GET['id'].'/'.$id_nickname); }
				if(!file_exists('indexes/downvotes/'.$_GET['id'])) { mkdir ('indexes/downvotes/'.$_GET['id']); }
				if(!file_exists('indexes/downvotes/'.$_GET['id'].'/'.$id_nickname)) { touch('indexes/downvotes/'.$_GET['id'].'/'.$id_nickname); }
			}
		}
	}
}