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

	public function getBraveries() {
		$result = $this->connection->query("SELECT * FROM bravery ORDER BY id DESC")->fetchAll();
		if ($result == null) {
			return array();
		}
		return $result;
	}

// 	public function getBraveryByNameAndFounded($bravery, $founded) {
// 		$result = $this->connection->query("SELECT * FROM bravery WHERE name='$bravery' AND founded='$founded'")->fetch();
// 		return $result;
// 	}
	
	public function getBraveryByNameAndFounded($bravery, $founded) {
		$result = $this->connection->query("SELECT * FROM bravery WHERE", array(
				'name' => $bravery,
				'founded' => $founded)
				)->fetch();
		return $result;
	}
	
	public function addBravery($bravery, $founded) {
		$result = $this->connection->table("bravery")->insert(array(
				'name' => $bravery,
				'founded' => $founded,
				'date_create' => $this->getDate(),
				'last_updated' => $this->getDate(),
		));
		return $result;
	}
}