<?php

	$require_login = true;
	require_once($_SERVER['DOCUMENT_ROOT'] . '/../inc/api.setup.php');

	if(!isset($_GET['playlist'])){
		$api->update(false, 'Playlist is empty!', []);
		$api->finish();	
	}

	$pl_id = $_GET['playlist'];

	$pl = $_GET['playlist'];

	$all_tracks = [];

	$limit = 50;

	$tracks = json_decode(json_encode($api->sl->api->getPlaylistTracks($pl_id, ['limit' => $limit])), true);

	$playlist = json_decode(json_encode($api->sl->api->getPlaylist($pl_id)), true);

	$total = $tracks['total'];

	if($total < 1){
		$api->update(false, 'Playlist is empty!', []);
		$api->finish();	
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
			$more_tracks = json_decode(json_encode($api->sl->api->getPlaylistTracks($pl_id, $options)), true);
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

		$api->update(true, 'Playlist is empty!', [$json_tracks, $playlist]);
		$api->finish();		 