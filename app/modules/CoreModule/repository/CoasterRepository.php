<?php

namespace App\Modules\Core\Repository;

use App\Modules\Core\Repository\BaseRespository;


class CoasterRepository extends BaseRespository
{
	/** @var Nette\Database\Context */
	private $connection;

	public function __construct(\Nette\Database\Context $connection)
	{
		$this->connection = $connection;
	}
	
	public function addCoaster($braveryId, $amount, $userId, $imageFrontName, $imageBackName) {
		$result = $this->connection->query("INSERT INTO coaster", array(
				'bravery_id' => $braveryId,
				'amount' => $amount,
				'user_id' => $userId,
				'front_image' => $imageFrontName,
				'back_image' => $imageBackName,
				'date_create' => $this->getDate(),
				'last_updated' => $this->getDate())
				);
		return $result;
	}

	public function updateCoaster($coasterId, $braveryId, $amount, $userId, $imageFrontName, $imageBackName) {
		$sql = "UPDATE coaster SET bravery_id = $braveryId,
		user_id = $userId,
		amount = $amount,
		#front_image#
		#back_image#
		last_updated = '" . $this->getDate() . "' WHERE id=$coasterId";

		if (isset($imageFrontName)) {
			$sql = str_replace("#front_image#", "front_image = '$imageFrontName',", $sql);
		}  else {
			$sql =str_replace("#front_image#", "", $sql);
		}

		if (isset($imageBackName)) {
			$sql = str_replace("#back_image#", "back_image = '$imageBackName',", $sql);
		}  else {
			$sql = str_replace("#back_image#", "", $sql);
		}
		$this->connection->query($sql);
	}

	public function removeCoaster($coasterId, $userId = null) {
		$result =  $this->connection->table('coaster')->where('id', $coasterId)->delete();
		return $result;
	}

	public function getCoasters($userId, $limit, $offset, $filtrParams) {
		$like = $filtrParams->getLike();

		if (!$like) {
			$result = $this->connection->query("SELECT coaster.*, bravery.name as bravery_name, bravery.founded as bravery_founded FROM coaster
					INNER JOIN bravery
					ON bravery.id = coaster.bravery_id
					WHERE coaster.user_id = '$userId'" .
					$this->getSortString($filtrParams) .
					($limit && $offset >= 0 ? "LIMIT $limit OFFSET $offset" : ''))->fetchAll();
		} else {
			$result = $this->connection->query("SELECT coaster.*, bravery.name as bravery_name, bravery.founded as bravery_founded FROM coaster
					INNER JOIN bravery
					ON bravery.id = coaster.bravery_id
					WHERE coaster.user_id = '$userId'
					AND (bravery.name LIKE ? OR bravery.founded LIKE ?) " .
					$this->getSortString($filtrParams) .
					($limit && $offset >= 0 ? "LIMIT $limit OFFSET $offset" : ''), '%'. $like .'%', '%'. $like .'%')->fetchAll();
		}
					
		return $result;
	}


	private function getSortString($filtrParams) {
		$sql = " ORDER BY ";
		if ($filtrParams->getSort() == 'bravery') {
			$sql .= "bravery.name";
		} else if ($filtrParams->getSort() == 'founded') {
			$sql .= "bravery.founded";
		} else {
			$sql .= "coaster.date_create";
		}

		if ($filtrParams->getOrder() == null) {
			$sql .= " DESC ";
		} else {
			$sql .= " " . $filtrParams->getOrder() ." ";
		}

		return $sql;
	}
	
	public function countCoasters($userId, $like = NULL) {
		$result = $this->connection->query("SELECT COUNT(*) as count FROM coaster 
				INNER JOIN bravery on coaster.bravery_id = bravery.id
				WHERE coaster.user_id='$userId' AND (bravery.name LIKE ? OR bravery.founded LIKE ?)", $like, $like)->fetch();
		return $result[0];
	}
	
	public function getCoaster($coasterId, $userId = null) {
		$result = $this->connection->query("SELECT coaster.*, bravery.name as bravery_name, bravery.founded as bravery_founded FROM coaster
				INNER JOIN bravery
				ON bravery.id = coaster.bravery_id
				WHERE coaster.id = '$coasterId'" . ($userId ? "AND coaster.user_id = '$userId'" : ''))->fetch();

		return $result;
	}

	public function countUniqueCoasters($userId) {
		$result = $this->connection->query("SELECT COUNT(*) as count FROM coaster WHERE user_id=?", $userId)->fetch();
		return $result[0];
	}

	public function countTotalCoasters($userId) {
		$result = $this->connection->query("SELECT sum(amount) as count FROM coaster WHERE user_id=?", $userId)->fetch();
		return $result[0] == NULL ? 0 : $result[0];
	}
	
}