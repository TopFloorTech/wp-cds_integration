<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:58 PM
 */

namespace Cds\CdsCommands;

class KeysCdsCommand extends CdsCommand {
	public function initialize() {
		$dependencies = $this->getDependencies();

		$urlHandler = $this->service->getUrlHandler();

		$productUrlTemplate = $urlHandler->construct(array(
			'page' => 'product',
			'id' => '%PRODUCT%',
		));

		$categoryUrlTemplate = $urlHandler->construct(array(
			'page' => 'search',
			'cid' => '%CATEGORY%',
		));

		$dependencies->setting('Keys', array(
			'productUrlTemplate' => $productUrlTemplate,
			'categoryUrlTemplate' => $categoryUrlTemplate,
			'containerId' => 'cds-keys-result',
			'attributeLabel' => 'Attribute',
			'valueLabel' => 'Value',
		));
	}

	public function execute() {
		$output = '';

		$output .= 'TopFloor.Cds.Keys.initialize();';

		return $output;
	}
}