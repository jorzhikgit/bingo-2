<?php
/**
 * Created by PhpStorm.
 * User: aretmy
 * Date: 24.05.14
 * Time: 18:40
 */

namespace Bingo;


use Slim\Slim;

class BingoGame
{

	private $id;

	private $data = array();

	public function __construct($data, $id = null)
	{
		if(!$id) {
			$id = $this->generateId();
		}

		$this->id = $id;
		$this->data = $data;
	}

	private function generateId()
	{
		return uniqid() . uniqid();
	}

	public function getData()
	{
		return array(
			'data' => $this->data,
			'id' => $this->id,
		);
	}

	public function get($field)
	{
		if(isset($this->data[$field])) {
			return $this->data[$field];
		}
		return null;
	}

	public function getId()
	{
		return $this->id;
	}

	public function save()
	{
		Slim::getInstance()->gamePersister->save($this);
	}

	public function nextNumber()
	{

	}

	public function getNumber($order)
	{
		return $this->data['numbers_drawn'][$order];
	}

	public function getNumbersFrom($lastOrder)
	{
		return array_slice($this->data['numbers_drawn'], $lastOrder + 1);
	}
} 