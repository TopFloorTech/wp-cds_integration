<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 11:38 PM
 */

namespace TopFloor\Cds\Helpers;

use TopFloor\Cds\CdsService;

class CdsBreadcrumbsHelper {
	/** @var CdsService $service */
	private $service;

	public function __construct(CdsService $service) {
		$this->service = $service;
	}

	public function getCategoryBreadcrumbs($categoryId, $addCurrentCategory = true) {
		$request = $this->service->getCategoryInfo()->categoryRequest($categoryId);
		$categoryInfo = $request->process();
		$urlHandler = $this->service->getUrlHandler();

		$breadcrumbs = array();

		foreach ($categoryInfo['crumbs'] as $crumb) {
			if ($crumb['id'] == 'root') {
				$url = $urlHandler->construct(array('page' => 'search'));
			} else {
				$url = $urlHandler->construct(array(
					'page' => 'search',
					'cid' => urlencode($crumb['id'])
				));
			}

			$breadcrumbs[] = array(
				'url' => $url,
				'label' => $crumb['label'],
			);
		}

		if ($addCurrentCategory) {
            $breadcrumbs[] = array(
                'url' => null,
                'label' => $categoryInfo['label'],
            );
		}

		return $breadcrumbs;
	}
}