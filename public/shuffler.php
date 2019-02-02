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

	$all_tracks = [];

	$limit = 50;

	$tracks = json_decode(json_encode($sl->api->getPlaylistTracks($pl_id, ['limit' => $limit])), true);

	$playlist = json_decode(json_encode($sl->api->getPlaylist($pl_id)), true);

	$total = $tracks['total'];

	$all_tracks = $tracks['items'];

	$json_tracks = [];

	if($limit - $total < 0){
		$loops_needed = ceil($total / $limit);
		for($i = 1; $i < $loops_needed; $i++){
			$options = [
				'offset' => $i * $limit,
				'limit' => $limit
			];
			$more_tracks = json_decode(json_encode($sl->api->getPlaylistTracks($pl_id, $options)), true);
			$all_tracks = array_merge($all_tracks, $more_tracks['items']);
		}	

	 }
	 echo '<ul>';
	 foreach($all_tracks as $k => $x){
	 	//print_r($x);
	 	echo '
	 		<li>
	 			<strong>' . (intval($k) + 1) . '</strong> - ' . $x['track']['artists'][0]['name'] . ' - ' . $x['track']['name'] . '
	 		</li>
	 	';
	 }
	 echo '</ul>';

	 foreach($all_tracks as $k => $x){
	 	$json_tracks[strval($k + 1)]['track_name'] = $x['track']['artists'][0]['name'] . ' - ' . $x['track']['name'];
	 	$json_tracks[strval($k + 1)]['track_length'] = strval($x['track']['duration_ms']);
	 	$json_tracks[strval($k + 1)]['track_url'] = $x['track']['external_urls']['spotify'];
	 	$json_tracks[strval($k + 1)]['track_spotify_id'] = $x['track']['id'];
	 	//$json_tracks[strval($k + 1)]['track_image_url'] = $x['track']['album']['images'][0]['url'];
	 }

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
	 			<div class="ui fluid green button" data-jq-id="shuffleButton">
	 				<i class="refresh icon"></i> Shuffle!
	 			</div>
	 		</div>
	 		<div class="four wide column" data-jq-id="playButton">
	 			<div class="ui fluid blue button">
	 				<i class="play icon"></i> Play!
	 			</div>
	 		</div>
	 		<div class="four wide column" data-jq-id="stopButton">
	 			<div class="ui fluid red button">
	 				<i class="stop icon"></i> Stop!
	 			</div>
	 		</div>	
	 		<div class="four wide column">
	 				<div class="ui fluid selection dropdown" data-jq-id="deviceDropdown">

	 				</div>
	 				<div class="ui circular icon green button" data-jq-id="deviceRefresh">
	 					<i class="refresh icon"></i>
	 				</div>
	 		</div>	 		 			 		
	 	</div>
	 </div>

	 <script>
	 	$(document).ready(function(){

	 		var 
	 			$shuffleBtn = $('[data-jq-id=shuffleButton]'),
	 			$playBtn = $('[data-jq-id=playButton]'),
	 			$stopBtn = $('[data-jq-id=stopButton]'),
	 			$deviceDropdown = $('[data-jq-id=deviceDropdown]'),
	 			$refreshBtn = $('[data-jq-id=deviceRefresh]'),
	 			tracks = `<?= json_encode($json_tracks); ?>`
	 		;

	 		// tracks = tracks.replace(/\\n/g, "\\n")  
    //            .replace(/\\'/g, "\\'")
    //            .replace(/\\"/g, '\\"')
    //            .replace(/\\&/g, "\\&")
    //            .replace(/\\r/g, "\\r")
    //            .replace(/\\t/g, "\\t")
    //            .replace(/\\b/g, "\\b")
    //            .replace(/\\f/g, "\\f");
				// // remove non-printable and other non-valid JSON chars
				// tracks = tracks.replace(/[\u0000-\u0019]+/g,"");

				// tracks = JSON.parse(tracks);

				console.log(tracks);

	 			// for (var i = tracks.length - 1; i >= 0; i--) {
	 			// 	console.log(tracks[i]['track_name'])
	 			// }
	 		;
	 	});
	 </script>
	
