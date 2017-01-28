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
		$updateData = array('last_updated' => $this->getDate());
		if (isset($imageFrontName)) {
			$updateData['front_image'] = $imageFrontName;
		}
		if (isset($imageBackName)) {
			$updateData['back_image'] = $imageBackName;
		}
		if (isset($amount)) {
			$updateData['amount'] = $amount;
		}
		if (isset($braveryId)) {
			$updateData['bravery_id'] = $braveryId;
		}
		
		$this->connection->table('coaster')->where('id = ? ', $coasterId)->update($updateData);
	}

	public function removeCoaster($coasterId, $userId = null) {
		$result =  $this->connection->table('coaster')->where('id', $coasterId)->delete();
		return $result;
	}

	public function getCoasters($userId, $limit, $offset, $filtrParams) {
		$like = $filtrParams->getLike();	
		$arg = array($userId);
		$sql = "SELECT coaster.*, bravery.name as bravery_name, bravery.founded as bravery_founded FROM coaster
				INNER JOIN bravery
				ON bravery.id = coaster.bravery_id
				WHERE coaster.user_id = ? ";
		// like params
		if ($like) {
			$sql .= " AND (bravery.name LIKE ? OR bravery.founded LIKE ?)";
			array_push($arg, "%" . $like . "%", "%" . $like . "%");
		}
		// add sort by
		$sql .= $this->getSortString($filtrParams);
		if ($limit && $offset >= 0) {
			$sql .= "  LIMIT ? OFFSET ?";
			array_push($arg, $limit, $offset);
		}
		
		$result = $this->connection->queryArgs($sql, $arg)->fetchAll();
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
		$arg = array($userId);
		$sql = "SELECT COUNT(*) as count FROM coaster
				INNER JOIN bravery on coaster.bravery_id = bravery.id
				WHERE coaster.user_id = ?";
		if ($like) {
			$sql .= " AND (bravery.name LIKE ? OR bravery.founded LIKE ?)";
			array_push($arg, "%" . $like . "%", "%" . $like . "%");
		}
		
		$result = $this->connection->queryArgs($sql, $arg)->fetch();
		return $result[0];
	}
	
	public function getCoaster($coasterId, $userId = null) {
		$arg = array($coasterId);
		$sql = "SELECT coaster.*, bravery.name as bravery_name, bravery.founded as bravery_founded FROM coaster
				INNER JOIN bravery
				ON bravery.id = coaster.bravery_id
				WHERE coaster.id = ?";
		if($userId) {
			$sql .= " AND coaster.user_id = ?";
			array_push($arg, $userId);
		}
		
		$result = $this->connection->queryArgs($sql, $arg)->fetch();
		return $result;
	}

	public function countUniqueCoasters($userId) {
		$result = $this->connection->table('coaster')->where('user_id = ?', $userId)->count('*');
		return $result;
	}

	public function countTotalCoasters($userId) {
		$result = $this->connection->table('coaster')->where('user_id = ?', $userId)->sum('amount');
		return $result;
	}
	
}