<?php
	
	require('shufflisten.class.php');

	class Shufflisten_API {

		public function __construct($require_login = false){

			$this->sl = new Shufflisten();

			$this->output = [
				'success' => false,
				'message' => 'An unexpected error occurred',
				'data' => []
			];

			if($require_login == true && $this->sl->isUserLoggedIn() == false){
				$this->update(false, 'You must be logged in to interact with this endpoint.', []);
				$this->finish();
			}

		}

		public function update($success = false, $message = 'An unexpected error occurred', $data = []){
			$this->output = [
				'success' => $success,
				'message' => $message,
				'data' => $data
			];
		}

		public function finish(){
			die(json_encode($this->output, JSON_PRETTY_PRINT));
		}

	}