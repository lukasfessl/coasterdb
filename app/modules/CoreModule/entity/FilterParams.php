<?php

namespace App\Modules\Core\Entity;

class FilterParams {
	
	private $page;
	private $order;
	private $sort;
	
	private $params;
	
	private $orderParams;
	private $sortParams;
	
	public function __construct() {
		$this->orderParams = array('desc' => 'Descending', 'asc' => 'Ascending');
		$this->sortParams = array('bravery' => 'Bravery name', 'founded' => 'Bravery founded', 'inserted' => 'Item inserted');
		$this->params = array();
	}
	
	
	
	public function setPage($page) {
		if (is_numeric($page)) {
			$this->params['page'] = $page;
			$this->page = $page;
		}
	}
	
	public function getPage() {
		return $this->page;
	}
	
	
	
	public function setOrder($order) {
		if (array_key_exists($order, $this->orderParams)) {
			$this->params['order'] = $order;
			$this->order = $order;
		}
	}
	
	public function getOrder() {
		return $this->order;
	}
	
	
	
	public function setSort($sort) {
		if (array_key_exists($sort, $this->sortParams)) {
			$this->params['sort'] = $sort;
			$this->sort = $sort;
		}
	}
	
	public function getSort() {
		return $this->sort;
	}
	
	
	
	public function getParams() {
		return $this->params;
	}
	
	
	
	public function getOrderParams() {
		return $this->orderParams;
	}
	
	public function setOrderParams(array $orderParams) {
		$this->orderParams = $orderParams;
	}
	
	
	public function getSortParams() 
	{
		return $this->sortParams;
	}
	
	public function setSortParams(array $sortParams) {
		$this->sortParams = $sortParams;
	}
}