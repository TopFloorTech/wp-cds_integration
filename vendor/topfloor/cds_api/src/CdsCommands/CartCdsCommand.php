<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:58 PM
 */

namespace TopFloor\Cds\CdsCommands;

class CartCdsCommand extends CdsCommand {
	public function initialize() {
		$dependencies = $this->getDependencies();

		$dependencies->setting('Cart', array(
			'containerId' => 'cds-cart-container',
		));
	}

	public function execute() {
		$output = '';

		$output .= 'TopFloor.Cds.Cart.initialize();';

		return $output;
	}
}
