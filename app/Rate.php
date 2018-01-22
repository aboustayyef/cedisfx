<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;

class Rate extends Model
{
	protected $fillable = ['buying','selling'];
	
	public static function getLatest(){
		if (Cache::has('latestRates')) {
			return Cache::get('latestRates');
		}
		$html = Web::getHtml(env('WEB_SOURCE'));
		$c = new Crawler((string) $html);
		$buying = $c->filter(env('USD_BUYING_SELECTOR'))->text();
		$selling = $c->filter(env('USD_SELLING_SELECTOR'))->text();

		$result = [
			'buying'	=> (float) $buying,
			'selling'	=>	(float) $selling
		];

		Cache::put('latestRates', $result, 5);
		return $result;
	}
}
