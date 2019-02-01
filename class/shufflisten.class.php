<?php

	class Shufflisten {

		public function __construct(){

			require '../vendor/autoload.php';
			require '../config.php';

			if(!headers_sent()){
				session_start();
			}

			$this->user = new SpotifyWebAPI\Session(
				SPOTIFY_ACCESS_TOKEN,
				SPOTIFY_SECRET_TOKEN,
				SPOTIFY_REDIRECT_URI
			);

			$this->api = new SpotifyWebAPI\SpotifyWebAPI();

			if($this->isUserLoggedIn()){
				$this->user->refreshAccessToken($_SESSION['user_ref_token']);
				$_SESSION['user_token'] = $this->user->getAccessToken();
				$_SESSION['user_ref_token'] = $this->user->getRefreshToken();
				$this->api->setAccessToken($_SESSION['user_token']);
			}

		}

		public function getAuthURL(){
			$options = [
				'scope' => [
	                'user-read-email',
	                'playlist-read-private'
            	]
			];

			$auth_url = $this->user->getAuthorizeUrl($options);

			return $auth_url;
		}	

		public function isUserLoggedIn(){
			if(isset($_SESSION['user_login_status']) && $_SESSION['user_login_status'] === true){
				return true;
			}else{
				return false;
			}
		}

		public function authorize($code){
			if($this->user->requestAccessToken($code)){
				$token = $this->user->getAccessToken();
				$ref_token = $this->user->getRefreshToken();
				$this->api->setAccessToken($token);

				$user = $this->api->me();

				$user = json_decode(json_encode($user), true);

				$_SESSION['user_info'] = $user;

				$_SESSION['user_token'] = $token;

				$_SESSION['user_ref_token'] = $ref_token;

				$_SESSION['user_login_status'] = true;

				return true;

			}else{
				return false;	
			}
			

		}



	}