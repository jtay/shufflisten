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

	if($total < 1){
		die('
			<h1 class="ui center aligned icon header">
				<i class="warning icon"></i>
				<div class="content">
					Whoops!
					<div class="sub header">
						This playlist is empty!
						<br><br>
						<a href="/" class="ui green button">
							Home
						</a>
					</div>
				</div>
			</h1>
			');		
	}

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

	 foreach($all_tracks as $k => $x){
	 	$json_tracks[strval($k + 1)]['track_name'] = $x['track']['artists'][0]['name'] . ' - ' . $x['track']['name'];
	 	$json_tracks[strval($k + 1)]['track_length'] = strval($x['track']['duration_ms']);
	 	if(!isset($x['track']['external_urls']['spotify'])){
	 		$json_tracks[strval($k + 1)]['track_url'] = 'https://spotify.com';
	 	}else{
			$json_tracks[strval($k + 1)]['track_url'] = $x['track']['external_urls']['spotify'];
	 	}
	 	
	 	$json_tracks[strval($k + 1)]['track_spotify_id'] = $x['track']['id'];
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
					<i class="user icon"></i><?= $playlist['owner']['display_name']; ?>
				</a>
				<span class="ui blue label">
					<i class="hashtag icon"></i><?= number_format($playlist['tracks']['total']); ?> tracks
				</span>			 		
		 	</div>
		 </span>
	 </h1>

	 <h3 class="ui top attached header">
	 	<i class="game icon"></i> Controls
	 </h3>
	 <div class="ui bottom attached segment">
	 	<div class="ui stackable grid">
	 		<div class="three wide column">
	 			<div class="ui fluid blue button" data-jq-id="shuffleButton">
	 				<i class="refresh icon"></i> Shuffle
	 			</div>
	 		</div>
	 		<div class="three wide column" data-jq-id="previousButton">
	 			<div class="ui fluid red button">
	 				<i class="backward icon"></i> Previous
	 			</div>
	 		</div>	 		
	 		<div class="three wide column" data-jq-id="playButton">
	 			<div class="ui fluid green button">
	 				<i class="play icon"></i> Play
	 			</div>
	 		</div>
	 		<div class="three wide column" data-jq-id="nextButton">
	 			<div class="ui fluid red button">
	 				<i class="forward icon"></i> Next
	 			</div>
	 		</div>	
	 		<div class="four wide column">
	 				<div class="ui selection dropdown" data-jq-id="deviceDropdown">
	 					<input type="hidden" name="deviceDropdown">
	 					<i class="dropdown icon"></i>
	 					<div class="default text">
	 						Choose a Device
	 					</div>
	 					<div class="menu" data-jq-id="deviceDropdownMenu">
	 					</div>
	 				</div>
	 				<div class="ui circular icon green label" data-jq-id="deviceRefresh" style="cursor: pointer;">
	 					<i class="fitted refresh icon"></i>
	 				</div>
	 		</div>	 		 			 		
	 	</div>
	 </div>

	 <script>
 		var 
 			$shuffleBtn = $('[data-jq-id=shuffleButton]'),
 			$playBtn = $('[data-jq-id=playButton]'),
 			$previousBtn = $('[data-jq-id=previousButton]'),
 			$nextBtn = $('[data-jq-id=nextButton]'),
 			$deviceDropdown = $('[data-jq-id=deviceDropdown]'),
 			$deviceDropdownMenu = $('[data-jq-id=deviceDropdownMenu]'),
 			$refreshBtn = $('[data-jq-id=deviceRefresh]'),
 			tracks = `<?= json_encode($json_tracks); ?>`,
 			selectedDevice,
 			playlistID = "<?= $pl_id; ?>"
 		;	

 		function checkDevices(){
 			$.getJSON('/api/v1/get_devices.php', function(data){
 				toastr.success(data['message']);
 				$deviceDropdownMenu.html('');
 				for (var i = data['data']['devices'].length - 1; i >= 0; i--) {
 					item = `
 						<div class="item" data-value="` + data['data']['devices'][i]['device_id'] + `">
 							<i class="` + data['data']['devices'][i]['device_type'] + ` icon"></i>` + data['data']['devices'][i]['device_name'] + `
 						</div>
 					`;
 					$deviceDropdownMenu.append($(item));
 					console.log(data['data']['devices'][i]);
 				}
 			})
 		}

 		function shuffle(){
 			$.getJSON('/api/v1/shuffle.php?playlist=' + playlistID, function(data){
 				if(data['success'] == true){

 				}else{
 					toastr.error(data['message']);
 				}
 			})
 		}

	 	$(document).ready(function(){

	 		$deviceDropdown.dropdown({
	 			onChange : function(value, text, $dom){
	 				selectedDevice = value;
	 				console.log(value, text, $dom);
	 			}
	 		});

	 		$refreshBtn.on('click touchstart', function(){
	 			checkDevices();
	 		})

	 		tracks = tracks.replace(/\\n/g, "\\n")  
               .replace(/\\'/g, "\\'")
               .replace(/\\"/g, '\\"')
               .replace(/\\&/g, "\\&")
               .replace(/\\r/g, "\\r")
               .replace(/\\t/g, "\\t")
               .replace(/\\b/g, "\\b")
               .replace(/\\f/g, "\\f");
				// remove non-printable and other non-valid JSON chars
				tracks = tracks.replace(/[\u0000-\u0019]+/g,"");

				tracks = JSON.parse(tracks);

				//console.log(tracks);

				for (var i = 0; i < tracks.length; i++) {
					console.log(tracks[i]['track_name'])
				}
	 		;
	 	});
	 </script>
	
