<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:58 PM
 */

namespace Cds\CdsCommands;

class SearchCdsCommand extends CdsCommand {
	protected function initialize() {
		$host = htmlspecialchars($this->service->getHost());

		$dependencies = $this->getDependencies();
		$urlHandler = $this->service->getUrlHandler();
		$categoryId = $this->service->getCategoryInfo()->categoryId();
		$categoryInfo = $this->service->getCategoryInfo()->categoryInfo($categoryId);
		$loadProducts = $this->service->getCategoryInfo()->loadProducts($categoryInfo);

		$productUrlTemplate = $urlHandler->construct(array(
			'page' => 'product',
			'id' => '%PRODUCT%',
			'cid' => '%CATEGORY%',
		));

		$searchUrlTemplate = $urlHandler->construct(array(
			'page' => 'search',
			'cid' => '%CATEGORY%',
		));

		$comparePageUrl = $urlHandler->construct(array('page' => 'compare'));

		$dependencies->js('cds-faceted-search', 'http://' . $host . '/catalog3/js/cds-faceted-search2.js');

		$dependencies->setting('Keys', array(
			'productUrlTemplate' => $productUrlTemplate,
			'searchUrlTemplate' => $searchUrlTemplate,
			'comparePageUrl' => $comparePageUrl,
			'containerId' => 'cds-keys-result',
			'attributeLabel' => 'Attribute',
			'valueLabel' => 'Value',
			'compareMaxProducts' => 6,
		));

		$this->defaultParameters = array(
			'categoryId' => $categoryId,
			'displayPowerGrid' => true,
			'renderProductsListType' => 'list',
			'showUnitToggle' => false,
			'appendUnitToProductUrl' => true,
			'loadProducts' => $loadProducts,
		);
	}

	public function execute() {
		$parameters = $this->getParameters();

		$output = '';

		$output .= "TopFloor.Cds.Search.initialize($parameters);\n";

		return $output;
	}
}