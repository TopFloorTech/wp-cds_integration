<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:58 PM
 */

namespace Cds\CdsCommands;

class ProductsCdsCommand extends CdsCommand {
	public function initialize() {
		$dependencies = $this->getDependencies();

		$dependencies->setting('Cart', array(
			'containerId' => 'cds-cart-container',
		));
	}

	public function execute() {
		// TODO: Implement request() method.
	}
}