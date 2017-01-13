<?php

namespace App\Modules\AdminModule\Service;

use App\Modules\Core\Repository\UserRepository;
use App\Modules\Core\Repository\CoasterRepository;
use App\Modules\Core\Repository\BraveryRepository;
use App\Modules\Core\Utils\Password;

class ManagementService {

	private $userRepository;
	private $coasterRepository;
	private $braveryRepository;
	private $params;


	public function __construct(UserRepository $userRepository, CoasterRepository $coasterRepository, BraveryRepository $braveryRepository, array $params) {
		$this->userRepository = $userRepository;
		$this->coasterRepository = $coasterRepository;
		$this->braveryRepository = $braveryRepository;
		$this->params = $params;
	}


	// ~ Coasters

	public function addCoaster($braveryName, $founded, $amount, $userId, $imageFront, $imageBack, $fileType) {
		$bravery = $this->braveryRepository->getBraveryByNameAndFounded($braveryName, $founded);
		if ($bravery == null) {
			$bravery = $this->braveryRepository->addBravery($braveryName, $founded);
		}
		$img = $this->processCoasterFiles($userId, $imageFront, $imageBack, $fileType);
		$this->coasterRepository->addCoaster($bravery['id'], $amount, $userId, $img['imageFrontName'], $img['imageBackName']);
	}


	public function updateCoaster($coasterId, $braveryName, $founded, $amount, $userId, $imageFront, $imageBack, $fileType) {;
		$bravery = $this->braveryRepository->getBraveryByNameAndFounded($braveryName, $founded);
		if ($bravery == null) {
			$bravery = $this->braveryRepository->addBravery($braveryName, $founded);
		}
		$img = $this->processCoasterFiles($userId, $imageFront, $imageBack, $fileType);
		$this->coasterRepository->updateCoaster($coasterId, $bravery['id'], $amount, $userId, $img['imageFrontName'], $img['imageBackName']);
	}


	private function processCoasterFiles($userId, $imageFront, $imageBack, $fileType) {

		$folder = "upload/" . $userId ."/";
		if (!file_exists($folder)) {
			mkdir($folder);
		}

		if ($imageFront !== NULL) {
			$imageFrontName = time() . "_0" . $fileType;
			$imageFrontThumbName = "thumb_" . time() . "_0" . $fileType;
			if ($imageFront->width > 1200) {
				$imageFront->resize(1200, 1200);
			}
			$imageFront->save($folder . $imageFrontName);
			$imageFront->resize(120, 120);
			$imageFront->save($folder . $imageFrontThumbName);
		}

		if ($imageBack !== NULL) {
			$imageBackName = time() . "_1" . $fileType;
			$imageBackThumbName = "thumb_" . time() . "_1" . $fileType;
			if ($imageBack->width > 1200) {
				$imageBack->resize(1200, 1200);
			}
			$imageBack->save($folder . $imageBackName);
			$imageBack->resize(120, 120);
			$imageBack->save($folder . $imageBackThumbName);
		}
		return array('imageFrontName' => isset($imageFrontName) ? $imageFrontName : NULL,
				'imageBackName' => isset($imageBackName) ? $imageBackName : NULL);
	}


	public function removeCoaster($coaster, $userId) {
		$dir = "upload/$userId/";

		if (file_exists($dir . $coaster['front_image'])) {
			unlink($dir . $coaster['front_image']);
		}
		if (file_exists($dir . $coaster['back_image'])) {
			unlink($dir . $coaster['back_image']);
		}
		if (file_exists($dir . "thumb_" . $coaster['front_image'])) {
			unlink($dir . "thumb_" . $coaster['front_image']);
		}
		if (file_exists($dir . "thumb_" . $coaster['back_image'])) {
			unlink($dir . "thumb_" . $coaster['back_image']);
		}

		return $this->coasterRepository->removeCoaster($coaster['id'], $userId);
	}


	public function getCoasters($userId, $limit = NULL, $offset = NULL, $filtrParams = NULL) {
		return $this->getCoasterRepository()->getCoasters($userId, $limit, $offset, $filtrParams);
	}


	public function getCoaster($coasterId, $userId) {
		return $this->getCoasterRepository()->getCoaster($coasterId, $userId);
	}


	public function countCoasters($userId) {
		return $this->getCoasterRepository()->countCoasters($userId);
	}

	public function getDashboardData($userId) {
		$data = array('coastersUniqueCount' => $this->getCoasterRepository()->countUniqueCoasters($userId),
					'coastersTotalCount' => $this->getCoasterRepository()->countTotalCoasters($userId)		
		);
		return $data;
	}



	// ~ Braveries

	public function getBraveries() {
		return $this->getBraveryRepository()->getBraveries();
	}



	// ~ Users

	public function getUser($id) {
		return $this->userRepository->getUser($id);
	}

	public function createUser($nick, $email, $password, $role) {
		$this->userRepository->createUser($nick, $email, Password::hash($password, $this->params['salt']), $role);
	}

	public function getUserByEmail($email) {
		return $this->userRepository->getUserByEmail($email);
	}

	public function updatePassword($userId, $password) {
		return $this->userRepository->updatePassword($userId, Password::hash($password, $this->params['salt']));
	}



	// ~ Repositories

	public function getUserRepository() {
		return $this->userRepository;
	}

	public function getCoasterRepository() {
		return $this->coasterRepository;
	}

	public function getBraveryRepository() {
		return $this->braveryRepository;
	}
}