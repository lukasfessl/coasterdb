<?php 

namespace App\Modules\AdminModule\Components;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\Image;
use Nette\Utils\Strings;
use Nette\Application\ApplicationException;

class CoasterForm extends Control
{
	private $managementService;
	private $userId;
	private $coaster;
	
	public function __construct($managementService, $userId)
	{
		$this->managementService = $managementService;
		$this->userId = $userId;
		
	}
	
	public function createComponentForm()
	{
		$coasterId = $this->presenter->getParameter('id');
		if ($coasterId != null && !is_numeric($coasterId)) {
			throw new ApplicationException();
		}
		$this->coaster = $this->managementService->getCoaster($coasterId, $this->userId);
		
		$form = new Form();
		if ($coasterId) {
			$form->addUpload('imageFront')->addCondition(Form::REQUIRED)->addRule(Form::IMAGE, 'Image has to be JPEG, PNG or GIF.');
		} else {
			$form->addUpload('imageFront')->addRule(Form::IMAGE, 'Image has to be JPEG, PNG or GIF.');
		}
		$form->addHidden('x1', 'x1:', 10 )->setHtmlId('image-front-x1');
		$form->addHidden('y1', 'y1:', 10 )->setHtmlId('image-front-y1');
		$form->addHidden('w1', 'w:', 10 )->setHtmlId('image-front-w');
		$form->addHidden('h1', 'h:', 10 )->setHtmlId('image-front-h');
		$form->addHidden('iw1', 'iw:', 10 )->setHtmlId('image-front-iw');
		$form->addHidden('ih1', 'ih:', 10 )->setHtmlId('image-front-ih');
		
		if ($coasterId) {
			$form->addUpload('imageBack')->addCondition(Form::REQUIRED)->addRule(Form::IMAGE, 'Image has to be JPEG, PNG or GIF.');
		} else {
			$form->addUpload('imageBack')->addRule(Form::IMAGE, 'Image has to be JPEG, PNG or GIF.');
		}
		$form->addHidden('x2', 'x1:', 10 )->setHtmlId('image-back-x1');
		$form->addHidden('y2', 'y1:', 10 )->setHtmlId('image-back-y1');
		$form->addHidden('w2', 'w:', 10 )->setHtmlId('image-back-w');
		$form->addHidden('h2', 'h:', 10 )->setHtmlId('image-back-h');
		$form->addHidden('iw2', 'iw:', 10 )->setHtmlId('image-back-iw');
		$form->addHidden('ih2', 'ih:', 10 )->setHtmlId('image-back-ih');

		$form->addText('brewery', 'Brewery', 30)->setValue($this->coaster['brewery_name'])->addRule(Form::MIN_LENGTH, "Brewery have to be fill", 1);
		$form->addText('founded', 'Founded', 30)->setValue($this->coaster['brewery_founded'])->addCondition(Form::REQUIRED)->addRule(Form::NUMERIC, "Value has to be number.");
		$form->addText('amount', 'Amount', 30)->setValue($this->coaster['amount'])->addCondition(Form::REQUIRED)->addRule(Form::NUMERIC, "Value has to be number.");
		$form->addHidden('breweryData')->setValue($this->formateBraveries($this->managementService->getBraveries()));
		$form->addSubmit('send', $coasterId ? 'Update' : 'Save')->setAttribute('class', 'uk-button');
		$form->onSuccess[] = $this->process;
		
		return $form;
	}

	public function process($form)
	{
		$values = $form->getValues();

		$fileType = Strings::split($values->imageFront->name ? $values->imageFront->name : $values->imageBack->name, '~\\.~');
		$fileType = Strings::lower(".".$fileType[count($fileType)-1]);

		$imageFront = NULL;
		if ($values->imageFront->name !== NULL) {
			$imageFront = Image::fromFile($values->imageFront);
			$imageSize = getimagesize($values->imageFront);
			$rw = $imageSize[0]/$values->iw1;
			$rh = $imageSize[1]/$values->ih1;
			$imageFront->crop($values->x1*$rw, $values->y1*$rw,$values->w1*$rw, $values->h1*$rh);
		}
		
		$imageBack = NULL;
		if ($values->imageBack->name !== NULL) {
			$imageBack = Image::fromFile($values->imageBack);
			$imageSize = getimagesize($values->imageBack);
			$rw = $imageSize[0]/$values->iw2;
			$rh = $imageSize[1]/$values->ih2;
			$imageBack->crop($values->x2*$rw, $values->y2*$rw,$values->w2*$rw, $values->h2*$rh);
		}
		
		$coasterId = $this->presenter->getParameter('id');
		if ($coasterId != null && !is_numeric($coasterId)) {
			throw new ApplicationException();
		}
		if (!$coasterId) {
			$this->managementService->addCoaster($values->brewery, $values->founded, $values->amount, $this->userId, $imageFront, $imageBack, $fileType);
			$this->presenter->flashMessage('Coaster was added');
			$this->presenter->redirect('Coaster:list');
		} else {
			$this->managementService->updateCoaster($coasterId, $values->brewery, $values->founded, $values->amount, $this->userId, $imageFront, $imageBack, $fileType);
			$this->presenter->flashMessage('Coaster was updated');
		}
	}

	public function render()
	{
		$coaster = $this->managementService->getCoaster($this->presenter->getParameter('id'), $this->userId);
		$this->template->setFile(__DIR__.'/CoasterForm.latte');
		$this->template->userId = $this->userId;
		$this->template->coaster = $this->coaster;
		$this->template->imgFrontPlaceholder = $this->presenter->getParameter('id') ? "/upload/" . $this->userId . "/" . $coaster['front_image'] : "/images/placeholder_250x250.png";
		$this->template->imgBackPlaceholder = $this->presenter->getParameter('id') ? "/upload/" . $this->userId . "/" . $coaster['back_image'] : "/images/placeholder_250x250.png";
		$this->template->render();
	}
	
	private function formateBraveries($braveries)
	{
		$braveriesArray = array();
		foreach ($braveries as $brewery) {
			array_push($braveriesArray, array('brewery' => $brewery['name'], 'founded' => $brewery['founded']));
		}
		
		return json_encode($braveriesArray);
	}
}
