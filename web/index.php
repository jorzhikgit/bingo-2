<?php

define('WEB_PATH', __DIR__ . '/');
define('INDEX_PATH', __DIR__ . '/../');

require INDEX_PATH . 'vendor/autoload.php';

$env = 'prod';

if(in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '192.168.0.1', '::1'))) {
	$env = 'dev';
}

use Slim\Slim;

$app = new Slim(require_once INDEX_PATH . 'config/' . $env . '.php');

$app->container->singleton('bingo', function() {
	return new \Bingo\Bingo();
});

$app->container->singleton('gamePersister', function() {
	return new \Bingo\FileGamePersister(array(
		'storagePath' => INDEX_PATH . 'runtime/games',
	));
});


// urls

// pages
$app->get('/', function() {
	\Slim\Slim::getInstance()->view()->display('index.php');
});
$app->get('/game', function() {
	Slim::getInstance()->view()->display('game.php');
});

// api
$app->get('/game/create/:number', function($number) {
	$game = Slim::getInstance()->bingo->createGame($number);
	$game->save();

	Slim::getInstance()->setCookie('gameId', $game->getId());

	echo json_encode(array(
		'cards' => $game->get('cards'),
	));
});

$app->get('/game/fetch', function() {
	$gameId = Slim::getInstance()->getCookie('gameId');
	$game = Slim::getInstance()->bingo->getGame($gameId);
	echo json_encode(array(
		'cards' => $game->get('cards'),
	));
});

$app->get('/game/turn/:order', function($order) {
	$gameId = Slim::getInstance()->getCookie('gameId');
	echo json_encode(array(
		'number' => Slim::getInstance()->bingo->getGame($gameId)->getNumber($order),
	));
});

$app->get('/game/playAll/:lastTurn', function($lastTurn) {
	$gameId = Slim::getInstance()->getCookie('gameId');
	echo json_encode(array(
		'numbers' => Slim::getInstance()->bingo->getGame($gameId)->getNumbersFrom($lastTurn),
	));
});

$app->run();