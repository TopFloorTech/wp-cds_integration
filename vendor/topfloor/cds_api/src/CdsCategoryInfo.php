<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 11:55 PM
 */

namespace Cds;


use TopFloor\Cds\CdsService;

class CdsCategoryInfo {
	/** @var CdsService $service */
	private $service;

	public function __construct(CdsService $service) {
		$this->service = $service;
	}

	public function categoryId() {
		$urlHandler = $this->service->getUrlHandler();

		$categoryId = $urlHandler->get('cid');
		if (empty($categoryId)) {
			$categoryId = 'root';
		}

		return $categoryId;
	}

	public function categoryRequest($category = null) {
		if (is_null($category)) {
			$category = $this->categoryId();
		}

		$resourceTemplate = '/catalog3/service?o=category&d=%s&cid=%s&unit=%s';

		$resource = sprintf($resourceTemplate, $this->service->getDomain(), $category,
			$this->service->getUnitSystem());

		return $this->service->request($resource);
	}

	public function categoryInfo($category = null) {
		return $this->categoryRequest($category)->process();
	}

	public function loadProducts($categoryInfo = null) {
		if (is_null($categoryInfo)) {
			$categoryInfo = $this->categoryInfo();
		}

		$urlHandler = $this->service->getUrlHandler();

		if (!isset($categoryInfo['children']) || $categoryInfo['children'] === 0) {
			return false;
		}

		return $urlHandler->parameterIsSet('filter');
	}

	public function categoryTitle($category = null) {
		$info = $this->categoryInfo($category);

		if (!isset($info['crumbs'])
		    && sizeof($info['crumbs']) === 0
		    && (!isset($info['searchImageURL']) || $this->loadProducts($info))) {
			return 'Select Category';
		}

		if ($this->service->getUrlHandler()->parameterIsSet('q')) {
			$q = $this->service->getUrlHandler()->get('q');
			return ucwords($q).": ".$info['label'];
		}

		return $info['label'];
	}

	public function categoryHeaderIsEmpty($category = null) {
		$info = $this->categoryInfo($category);

		return (!isset($info['searchImageURL']) && !isset($info['searchHeaderHTML'])
		        && !isset($info['description']));
	}

	public function imageUrl($category = null) {
		$info = $this->categoryInfo($category);

		if (!isset($info['searchImageURL'])) {
			return '';
		}

		return htmlspecialchars($info['searchImageURL']);
	}

	public function headerHtml($category = null) {
		$info = $this->categoryInfo($category);

		if (!isset($info['searchHeaderHTML'])) {
			return '';
		}

		return $info['searchHeaderHtml'];
	}

	public function description($category = null) {
		$info = $this->categoryInfo($category);

		if (!isset($info['description'])) {
			return '';
		}

		return $info['description'];
	}

	public function children($category = null) {
		$info = $this->categoryInfo($category);

		if (!isset($info['children'])) {
			return array();
		}

		return $info['children'];
	}
}
