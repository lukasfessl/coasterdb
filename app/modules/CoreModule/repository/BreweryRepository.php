<?php

namespace App\Modules\Core\Repository;

use App\Modules\Core\Repository\BaseRespository;


class BreweryRepository extends BaseRespository
{
	/** @var Nette\Database\Context */
	private $connection;

	public function __construct(\Nette\Database\Context $connection)
	{
		$this->connection = $connection;
	}

	public function getBraveries()
	{
		$result = $this->connection->table('brewery')->order('name ASC')->fetchAll();
		return $result;
	}
	
	public function getBreweryByNameAndFounded($brewery, $founded)
	{
		$result = $this->connection->table('brewery')->where('name = ? AND founded = ?', array($brewery, $founded))->fetch();
		return $result;
	}
	
	public function getBreweryByName($name) 
	{
		$result = $this->connection->table('brewery')->where('name = ?', $name)->fetchAll();
		return $result;
	}
	
	public function getBrewery($breweryId)
	{
		$result = $this->connection->query("SELECT * FROM brewery WHERE id = ?", $breweryId)->fetch();
		return $result;
	}
	
	public function addBrewery($brewery, $founded)
	{
		$result = $this->connection->table("brewery")->insert(array(
				'name' => $brewery,
				'founded' => $founded,
				'date_create' => $this->getDate(),
				'last_updated' => $this->getDate(),
		));
		return $result;
	}
	
	public function updateBrewery($breweryId, $name, $founded) {
		$this->connection->table('brewery')->where('id = ?', $breweryId)->update(array('name' => $name, 'founded' => $founded));
	}
	
	public function getBreweryCoasterCount($user) {
		$result = $this->connection->query("SELECT brewery.name, COUNT(coaster.brewery_id) as count FROM coaster
				INNER JOIN brewery ON brewery.id = coaster.brewery_id 
				WHERE coaster.user_id = '$user'
				GROUP BY brewery_id
				ORDER BY brewery.name ASC")->fetchAll();
		return $result;
	}
}