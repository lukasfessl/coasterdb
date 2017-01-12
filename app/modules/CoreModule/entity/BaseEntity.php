<?php 

namespace App\Modules\CorenModule\Entity;


class BaseEntity extends Nette\Object 
{
	private $id;
	
	private $dateCreation;
	
	private $lastUpdated;
	
	
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getId() {
		return $this->id;
	}
	
	
	
	public function setDateCreation($dateCreation) {
		$this->dateCreation = $dateCreation;
	}
	
	public function getDateCreation() {
		return $this->dateCreation;
	}
	
	
	
	public function setLastUpdatedn($lastUpdated) {
		$this->lastUpdated = $lastUpdated;
	}
	
	public function getLastUpdated() {
		return $this->lastUpdated;
	}
}