<?php
/**
 * Created by PhpStorm.
 * User: aretmy
 * Date: 24.05.14
 * Time: 18:50
 */

namespace Bingo;


class FileGamePersister implements IGamePersister
{

	private $storagePath;

	public function __construct($config)
	{
		$this->storagePath = isset($config['storagePath']) ? $config['storagePath'] : null;
	}

	/**
	 * @param BingoGame $game
	 * @return bool
	 */
	public function save(BingoGame $game)
	{
		$data = $game->getData();
		$id = $data['id'];

		return file_put_contents($this->getFilepath($id), json_encode($data)) !== false;
	}

	private function getFilepath($id)
	{
		if(!$this->storagePath) {
			throw new \Exception('Component ' . __CLASS__ . ' misconfigured. "storagePath" has not been passed.');
		}
		return $this->storagePath . '/game' . $id;
	}

	/**
	 * @param $gameId
	 * @return BingoGame
	 */
	public function load($gameId)
	{
		$filepath = $this->getFilepath($gameId);

		if(!file_exists($filepath)) {
			return null;
		}

		$data = json_decode(file_get_contents($filepath), true);

		if($data === false) {
			return null;
		}

		return new BingoGame($data['data'], $data['id']);
	}
} 