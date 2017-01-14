<?php

namespace App\Modules\FrontModule\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;



class HomepagePresenter extends Nette\Application\UI\Presenter
{

	public function renderDefault()
	{
		$this->redirect(":Admin:Auth:default");
	}


}
