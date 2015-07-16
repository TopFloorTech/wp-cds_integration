<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 11:09 AM
 */

namespace TopFloor\Cds\RequestHandlers;

use TopFloor\Cds\CdsRequest;
use TopFloor\Cds\CdsService;

abstract class RequestHandler {
	/** @var CdsService $service */
	protected $service;

	public function __construct($service) {
		$this->service = $service;
	}

	public abstract function send(CdsRequest $request);
}