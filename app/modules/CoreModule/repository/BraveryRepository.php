<?php

namespace App\Modules\Core\Repository;

use App\Modules\Core\Repository\BaseRespository;


class BraveryRepository extends BaseRespository
{
	/** @var Nette\Database\Context */
	private $connection;

	public function __construct(\Nette\Database\Context $connection)
	{
		$this->connection = $connection;
	}

	public function getBraveries()
	{
		$result = $this->connection->table('bravery')->order('id DESC')->fetchAll();
		return $result;
	}
	
	public function getBraveryByNameAndFounded($bravery, $founded)
	{
		$result = $this->connection->table('bravery')->where('name = ? AND founded = ?', array($bravery, $founded))->fetch();
		return $result;
	}
	
	public function addBravery($bravery, $founded)
	{
		$result = $this->connection->table("bravery")->insert(array(
				'name' => $bravery,
				'founded' => $founded,
				'date_create' => $this->getDate(),
				'last_updated' => $this->getDate(),
		));
		return $result;
	}
	
	public function getBraveryCoasterCount($user) {
		$result = $this->connection->query("SELECT bravery.name, COUNT(coaster.bravery_id) as count FROM coaster
				INNER JOIN bravery ON bravery.id = coaster.bravery_id 
				WHERE coaster.user_id = '$user'
				GROUP BY bravery_id
				ORDER BY bravery.name ASC")->fetchAll();
		return $result;
	}
}