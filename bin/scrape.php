<?php require dirname(__DIR__) . '/vendor/autoload.php';

error_reporting(0);

$appdir = dirname(__DIR__);
$cli = new League\CLImate\CLImate;

$http = new GuzzleHttp\Client([
	'base_uri' => 'http://www.mouse-sensitivity.com',
	'timeout' => 1,
	'connect_timeout' => 2,
	'http_errors' => false,

	'headers' => [
		'X-Requested-With' 	=> 'XMLHttpRequest',
		'Referer' 			=> 'http://www.mouse-sensitivity.com/',
		'User-Agent' 		=> 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1'
	],

	'on_stats' => function (GuzzleHttp\TransferStats $stat) use ($cli) {
		$h = $stat->getHandlerStats();
		$cli->lightBlue(". status: {$h['http_code']}, time: {$h['total_time']}, size: {$h['request_size']}, url: {$h['url']}");
	}
]);



for ($i=2; $i<360; $i++) {

	$http->request('GET', '/calc/5.2/gameinfo.php',[
		'sink' => $appdir . '/scrape/gameinfo/gameinfo_' . $i . '.html',
		'query' => [
			'input' => 'gameinfo',
			'game1' => $i,
			'support' => 1
		]
	]);

	$http->request('GET', '/calc/5.2/gameinfo.php',[
		'sink' => $appdir . '/scrape/gamevars/gamevars_' . $i . '.json',
		'query' => [
			'input' => 'gamevars',
			'game1' => $i
		]
	]);

	$http->request('GET', '/calc/5.2/gamelist.php', [
		'sink' => $appdir . '/scrape/gamelist/aim_' . $i . '.html',
		'query' => [
			'input' => 'aim',
			'aim1' => $i,
			'support' => 1
		]
	]);

	usleep(rand(400, 1800) * 1000);
}


