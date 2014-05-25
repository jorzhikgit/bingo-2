<?php
/**
 * Created by PhpStorm.
 * User: aretmy
 * Date: 24.05.14
 * Time: 18:40
 */

namespace Bingo;


use Slim\Slim;

class Bingo
{

	private static $instance;

	public function createGame($cardsNumber)
	{
		return new BingoGame($this->request(array(
			'cardsNumber' => $cardsNumber,
		)));
	}

	public function getGame($gameId)
	{
		return Slim::getInstance()->gamePersister->load($gameId);
	}

	private function request($params)
	{
		try {
			\Unirest::verifyPeer(false);
			$result = \Unirest::get("https://bingo.p.mashape.com/index.php?cards_number={$params['cardsNumber']}", array(
					"X-Mashape-Authorization" => "nCBTs11usEAO3VtfDUqz6Hd9FQtaBH82",
				), false);
			return json_decode($result->raw_body, true);
		} catch(\Exception $ex) {
			return array(
				'error' => true,
				'message' => 'Could not make request.',
				'cards' => array(),
				'numbers_drawn' => array(),
			);
		}
	}

	public static function getInstance()
	{
		if(is_null(self::$instance)) {
			self::$instance = new static();
		}
		return self::$instance;
	}
} 