<?php

namespace App\Modules\AdminModule\Service;

use App\Modules\Core\Repository\UserRepository;
use App\Modules\Core\Repository\CoasterRepository;
use App\Modules\Core\Repository\BreweryRepository;
use App\Modules\Core\Utils\Hash;
use Nette\Utils\Strings;

class ManagementService {

	private $userRepository;
	private $coasterRepository;
	private $breweryRepository;
	private $params;


	public function __construct(UserRepository $userRepository, CoasterRepository $coasterRepository, BreweryRepository $breweryRepository, array $params) {
		$this->userRepository = $userRepository;
		$this->coasterRepository = $coasterRepository;
		$this->breweryRepository = $breweryRepository;
		$this->params = $params;
	}


	// ~ Coasters

	public function addCoaster($breweryName, $founded, $amount, $userId, $imageFront, $imageBack, $fileType) {
		$brewery = $this->breweryRepository->getBreweryByNameAndFounded($breweryName, $founded);
		if ($brewery == null) {
			$brewery = $this->breweryRepository->addBrewery($breweryName, $founded);
		}
		$img = $this->processCoasterFiles($userId, $imageFront, $imageBack, $fileType);
		$this->coasterRepository->addCoaster($brewery['id'], $amount, $userId, $img['imageFrontName'], $img['imageBackName']);
	}


	public function updateCoaster($coasterId, $breweryName, $founded, $amount, $userId, $imageFront, $imageBack, $fileType) {;
		$brewery = $this->breweryRepository->getBreweryByNameAndFounded($breweryName, $founded);
		if ($brewery == null) {
			$brewery = $this->breweryRepository->addBrewery($breweryName, $founded);
		}
		$img = $this->processCoasterFiles($userId, $imageFront, $imageBack, $fileType);
		$this->coasterRepository->updateCoaster($coasterId, $brewery['id'], $amount, $userId, $img['imageFrontName'], $img['imageBackName']);
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


	public function countCoasters($userId, $like = NULL) {
		return $this->getCoasterRepository()->countCoasters($userId, $like);
	}

	public function getDashboardData($userId) {
		$data = array('coastersUniqueCount' => $this->getCoasterRepository()->countUniqueCoasters($userId),
					'coastersTotalCount' => $this->getCoasterRepository()->countTotalCoasters($userId),
					'breweryCoasterCount' => $this->getBreweryRepository()->getBreweryCoasterCount($userId),
		);
		return $data;
	}



	// ~ Braveries

	public function getBraveries() {
		return $this->getBreweryRepository()->getBraveries();
	}

	public function getBreweryByName($name) {
		return $this->getBreweryRepository()->getBreweryByName($name);
	}
	
	public function getBrewery($breweryId) {
		return $this->getBreweryRepository()->getBrewery($breweryId);
	}

	public function saveBrewery($name, $founded) {
		return $this->getBreweryRepository()->addBrewery($name, $founded);
	}
	
	public function updateBrewery($breweryId, $name, $founded) {
		return $this->getBreweryRepository()->updateBrewery($breweryId, $name, $founded);
	}

	
	
	// ~ Users

	public function getUser($id) {
		return $this->userRepository->getUser($id);
	}

	public function createUser($nick, $email, $password, $role) {
		return $this->userRepository->createUser($nick, $email, Hash::sha1($password, $this->params['salt']), $role);
	}

	public function getUserByEmail($email) {
		return $this->userRepository->getUserByEmail($email);
	}

	public function updatePassword($userId, $password) {
		return $this->userRepository->updatePassword($userId, Hash::sha1($password, $this->params['salt']));
	}
	
	public function updatePublicLink($user, $publicLinkActive) {
		$publicLink = $user['id'] . "-" .$user['date_create']->getTimestamp();
		$publicLink = Hash::sha1($publicLink);
		$this->userRepository->updatePublicLink($user['id'], $publicLink, $publicLinkActive);
	}
	
	public function getUserByPublicLink($publicLink) {
		$publicLink = preg_replace("/[^A-Za-z0-9 ]/", '', $publicLink);
		return $this->userRepository->getUserByPublicLink($publicLink);
	}



	// ~ Repositories

	public function getUserRepository() {
		return $this->userRepository;
	}

	public function getCoasterRepository() {
		return $this->coasterRepository;
	}

	public function getBreweryRepository() {
		return $this->breweryRepository;
	}
}