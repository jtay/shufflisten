<?php

	$pageTitle = "Home";

	require_once('../inc/page.setup.php');

	if($sl->isUserLoggedIn() == false){
		?>
			<h1 class="ui header">
				Hey there!
				<div class="sub header">
					Welcome to Shufflisten, it seems you're not logged in! Please click the button below to log in.
				</div>
			</h1>
			<a class="ui green button" href="<?= $sl->getAuthURL(); ?>"><i class="spotify icon"></i> Log in with Spotify</a>
		<?php
		die();
	}

?>