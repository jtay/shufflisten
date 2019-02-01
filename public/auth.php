<?php

	$pageTitle = "Authorize";

	require_once('../inc/page.setup.php');

	if(!isset($_GET['code'])){
		die('Invalid Authorization Code! Please try again.');
	}

	if($sl->authorize($_GET['code'])){
		die("<script>document.location = '/';</script>");
	}else{
		die('Invalid Authorization Code! Please try again.');
	} 
?>