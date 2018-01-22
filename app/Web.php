<?php

namespace App;

use GuzzleHttp\Client;

class Web 
{
	public static function getHtml($url){
		$client = new Client();
            try {            
                $response = $client->request('GET', $url);
                return $response->getBody();
            } catch (\Exception $e) {
                return null;
            }
	}
}
