<?php

namespace App\Modules\FrontModule\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;



class BasePresenter extends Nette\Application\UI\Presenter
{
	protected $managementService;

	protected function startup() {
		parent::startup();
		$this->managementService = $this->context->getService('managementService');
	}	
}
