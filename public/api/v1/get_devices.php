<?php

	$require_login = true;
	require_once($_SERVER['DOCUMENT_ROOT'] . '/../inc/api.setup.php');

	$devices = json_decode(json_encode($api->sl->api->getMyDevices()), true);

	if(count($devices['devices']) < 1){
		$api->update(true, 'No devices found!', ['count' => 0]);
		$api->finish();
	}

	$clean_devices = [];

	foreach ($devices['devices'] as $k => $v) {

		if($v['is_restricted'] == true || $v['is_private_session'] == true){
			continue;
		}

		$out = [
			'device_id' => $v['id'],
			'device_is_active' => $v['is_active'],
			'device_name' => $v['name'],
			'device_type' => NULL
		];

		switch($v['type']){
			case 'Computer':
				$out['device_type'] = 'desktop';
				break;
			case 'Tablet':
				$out['device_type'] = 'tablet';
				break;
			case 'Smartphone':
				$out['device_type'] = 'mobile';
				break;				
			case 'Speaker':
				$out['device_type'] = 'volume up';
				break;			
			case 'TV':
				$out['device_type'] = 'tv';
				break;				
			case 'GameConsole':
				$out['device_type'] = 'gamepad';
				break;				
			case 'Automobile':
				$out['device_type'] = 'car';
				break;				
			default:
				$out['device_type'] = 'cube';
				break;
		}

		$clean_devices[] = $out;

	}

	$api->update(true, 'Devices found.', ['count' => count($devices['devices']), 'devices' => $clean_devices]);
	$api->finish();