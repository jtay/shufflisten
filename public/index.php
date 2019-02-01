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
	<h1 class="ui header">
		Welcome to Shufflisten
		<div class="sub header">
			Logged in as <?= $_SESSION['user_info']['display_name']; ?> <a href="/logout.php" style="font-size: 0.6em;">Not you?</a>
		</div>
	</h1>
	<div class="ui four stackable cards">
		<?php

		$pl = json_decode(json_encode($sl->api->getUserPlaylists($_SESSION['user_info']['id'], ['limit' => 50])), true);
		$pl = $pl['items'];

		foreach($pl as $x){
			?>
			<div class="card">
				<a class="image" href="/shuffler.php?playlist=<?= $x['id']; ?>">
					<img src="<?= $x['images'][0]['url']; ?>">
				</a>
				<div class="content">
					<a class="header" href="/shuffler.php?playlist=<?= $x['id']; ?>">
						<?= $x['name']; ?>
					</a>
					<div class="meta">
						<a class="ui green label" href="<?= $x['owner']['external_urls']['spotify']; ?>">
							<i class="user icon"></i>&nbsp;&nbsp;<?= $x['owner']['display_name']; ?>
						</a>
						<span class="ui blue label">
							<i class="hashtag icon"></i>&nbsp;&nbsp;<?= number_format($x['tracks']['total']); ?> tracks
						</span>						
					</div>
				</div>
			</div>
			<?php
		}

		?>
	</div>