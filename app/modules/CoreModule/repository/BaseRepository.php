<?php

namespace App\Modules\Core\Repository;

abstract class BaseRespository extends \Nette\Object
{
	
	protected function getDate() {
		return Date('Y-m-d H:i:s');
	}
	
}