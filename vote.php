<?php

if(!empty($_POST['id'])) {
	if (preg_match("/^[a-f0-9]{64}$/", $_POST['id'])) {
		if(file_exists('articles/'.$_POST['id'].'.json')) {
			$nickname = 'fervi';
			$id_nickname = hash('sha256', $nickname);
			$upvote = !empty($_POST['upvote']);
			if($upvote == 1) {
				if(file_exists('indexes/downvotes/'.$_POST['id'].'/'.$id_nickname)) { unlink('indexes/downvotes/'.$_POST['id'].'/'.$id_nickname); }
				if(!file_exists('indexes/upvotes/'.$_POST['id'])) { mkdir ('indexes/upvotes/'.$_POST['id']); }
				if(!file_exists('indexes/upvotes/'.$_POST['id'].'/'.$id_nickname)) { touch('indexes/upvotes/'.$_POST['id'].'/'.$id_nickname); }
			} else {
				if(file_exists('indexes/upvotes/'.$_POST['id'].'/'.$id_nickname)) { unlink('indexes/upvotes/'.$_POST['id'].'/'.$id_nickname); }
				if(!file_exists('indexes/downvotes/'.$_POST['id'])) { mkdir ('indexes/downvotes/'.$_POST['id']); }
				if(!file_exists('indexes/downvotes/'.$_POST['id'].'/'.$id_nickname)) { touch('indexes/downvotes/'.$_POST['id'].'/'.$id_nickname); }
			}
		}
	}
}