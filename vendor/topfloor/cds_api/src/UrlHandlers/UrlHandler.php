<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:51 PM
 */

namespace Cds\UrlHandlers;


use TopFloor\Cds\CdsService;

abstract class UrlHandler implements UrlHandlerInterface {
	private $service;

	public function __construct(CdsService $service) {
		$this->service = $service;
	}

	public abstract function construct($parameters = array());

	public abstract function deconstruct($url);

	public function getCurrentUri() {
		return $_SERVER['REQUEST_URI'];
	}

	public function get($parameter) {
		$parts = $this->deconstruct($this->getCurrentUri());

		if (!isset($parts[$parameter])) {
			return '';
		}

		return $parts[$parameter];
	}

	public function parameterIsSet($parameter) {
		$parts = $this->deconstruct($this->getCurrentUri());

		return isset($parts[$parameter]);
	}
}