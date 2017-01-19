<?php

namespace App\Modules\FrontModule\Presenters;

use App\Modules\FrontModule\Presenters\BasePresenter;



class HomepagePresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->redirect(":Admin:Auth:default");
	}


}
