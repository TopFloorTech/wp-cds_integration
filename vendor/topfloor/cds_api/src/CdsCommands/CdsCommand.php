<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:56 PM
 */

namespace Cds\CdsCommands;

use Cds\CdsDependencyCollection;
use TopFloor\Cds\CdsService;

abstract class CdsCommand implements CdsCommandInterface {
	protected $service;

	protected $dependencies;

	protected $parameters = array();

	protected $defaultParameters = array();

	public function __construct(CdsService $service) {
		$this->service = $service;
		$this->dependencies = new CdsDependencyCollection();

		$this->initialize();
	}

	protected function initialize() {
		// Override this if there are any dependencies to declare.
	}

	public function getDependencies() {
		return $this->dependencies;
	}

	public function setParameters($parameters = array()) {
		$this->parameters = $parameters;
	}

	public function getParameters($encode = false) {
		$parameters = $this->parameters + $this->defaultParameters;

		if ($encode) {
			$parameters = json_encode($parameters);
		}

		return $parameters;
	}

	public abstract function execute();
}
