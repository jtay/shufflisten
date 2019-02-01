<?php

	class Shufflisten {

		require '../vendor/autoload.php';
		require '../config.php';

		public function __construct(){

			$this->spotify = new SpotifyWebAPI\Session(
				SPOTIFY_ACCESS_TOKEN,
				SPOTIFY_SECRET_TOKEN,
				SPOTIFY_REDIRECT_URI
			);

		}

	}