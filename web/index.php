<?php

define('WEB_PATH', __DIR__ . '/');
define('INDEX_PATH', __DIR__ . '/../');

require INDEX_PATH . 'vendor/autoload.php';


use Slim\Slim;

$app = new Slim(array(
	'debug' => true,
	'templates.path' => INDEX_PATH . 'views'
));

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

	echo json_encode(array(
		'id' => $game->getId(),
		'cards' => $game->get('cards'),
	));
});

$app->get('/game/fetch/:gameId', function($gameId) {
	$game = Slim::getInstance()->bingo->getGame($gameId);
	echo json_encode(array(
		'cards' => $game->get('cards'),
	));
});

$app->get('/game/:gameId/turn/:order', function($gameId, $order) {
	echo json_encode(array(
		'number' => Slim::getInstance()->bingo->getGame($gameId)->getNumber($order),
	));
});

$app->get('/game/:gameId/playAll/:lastTurn', function($gameId, $lastTurn) {
	echo json_encode(array(
		'numbers' => Slim::getInstance()->bingo->getGame($gameId)->getNumbersFrom($lastTurn),
	));
});

$app->run();