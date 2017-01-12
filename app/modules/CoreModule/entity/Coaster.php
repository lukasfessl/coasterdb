<?php 

namespace App\Modules\CorenModule\Entity;


class CoasterEntity extends Nette\Object 
{
	public $userId;
	
	public $bravery;
	public $braveryId;
	
	public $coaster;
	
	public $imageFront;
	public $imageFrontFileType;
	
	public $imageBack;
	public $imageBackFileType;
	
	public function map($values) {
		$this->userId = $this->userId;
		$this->bravery = $values->bravery;
		$this->$founded = $values->founded; 
	}
}