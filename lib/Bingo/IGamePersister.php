<?php
/**
 * Created by PhpStorm.
 * User: aretmy
 * Date: 24.05.14
 * Time: 19:07
 */

namespace Bingo;


interface IGamePersister
{

	/**
	 * @param BingoGame $game
	 * @return mixed
	 */
	public function save(BingoGame $game);

	/**
	 * @param $gameId
	 * @return BingoGame
	 */
	public function load($gameId);

} 