<?php

	$pageTitle = "Playlist";

	require_once('../inc/page.setup.php');

	if(!isset($_GET['playlist'])){
		die('
			<h1 class="ui center aligned icon header">
				<i class="warning icon"></i>
				<div class="content">
					Whoops!
					<div class="sub header">
						Please make sure you have a playlist selected!
						<br><br>
						<a href="/" class="ui green button">
							Home
						</a>
					</div>
				</div>
			</h1>
			');
	}

	$pl_id = $_GET['playlist'];

	$pl = $_GET['playlist'];

	$allTracks = [];

	$limit = 50;

	$tracks = json_decode(json_encode($sl->api->getPlaylistTracks($pl_id, ['limit' => $limit])), true);

	$playlist = json_decode(json_encode($sl->api->getPlaylist($pl_id)), true);

	$total = $tracks['total'];

	$allTracks = $allTracks + $tracks['items'];

	if($limit - $total < 0){
		echo 'catch1';
		$loops_needed = ceil($total / $limit);
		for($i = 1; $i < $loops_needed; $i++){
			$options = [
				'offset' => $i * $limit,
				'limit' => $limit
			];
			$more_tracks = json_decode(json_encode($sl->api->getPlaylistTracks($pl_id, $options)), true);
			// print_r($moreTracks);
			$allTracks = array_merge($allTracks, $more_tracks['items']);
		}	

	 }
	 echo '<ul>';
	 foreach($allTracks as $k => $x){
	 	//print_r($x);
	 	echo '
	 		<li>
	 			<strong>' . (intval($k) + 1) . '</strong> - ' . $x['track']['artists'][0]['name'] . ' - ' . $x['track']['name'] . '
	 		</li>
	 	';
	 }
	 echo '</ul>';
	 ?>

	 <h1 class="ui image header">
	 	<img src="<?= $playlist['images'][0]['url']; ?>" class="ui avatar image">
	 	<div class="content">
	 		<a class="header" href="<?= $playlist['external_urls']['spotify'] ;?>">
		 		<?= $playlist['name']; ?>
		 	</a>
		 	<div class="sub header" style="margin-left: 0;">
				<a class="ui green label" href="<?= $playlist['owner']['external_urls']['spotify']; ?>">
					<i class="user icon"></i>&nbsp;&nbsp;<?= $playlist['owner']['display_name']; ?>
				</a>
				<span class="ui blue label">
					<i class="hashtag icon"></i>&nbsp;&nbsp;<?= number_format($playlist['tracks']['total']); ?> tracks
				</span>			 		
		 	</div>
		 </span>
	 </h1>

	 <h3 class="ui top attached header">
	 	<i class="game icon"></i> Controls
	 </h3>
	 <div class="ui bottom attached segment">
	 	<div class="ui stackable grid">
	 		<div class="four wide column">
	 			<div class="ui fluid green button">
	 				<i class="refresh icon"></i> Shuffle!
	 			</div>
	 		</div>
	 		<div class="four wide column">
	 			<div class="ui fluid blue button">
	 				<i class="play icon"></i> Play!
	 			</div>
	 		</div>
	 		<div class="four wide column">
	 			<div class="ui fluid red button">
	 				<i class="stop icon"></i> Stop!
	 			</div>
	 		</div>	
	 		<div class="four wide column">
	 			
	 		</div>	 		 			 		
	 	</div>
	 </div>
	
