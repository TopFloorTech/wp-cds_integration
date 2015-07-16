<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 10:34 AM
 */

namespace TopFloor\Cds;

use TopFloor\Cds\RequestHandlers\RequestHandler;
use TopFloor\Cds\ResponseParsers\ResponseParser;

class CdsRequest {
	protected $service;

	protected $handler;

	protected $parser;

	protected $resource = '/';

	public function __construct(CdsService $service, RequestHandler $handler, ResponseParser $parser) {
		$this->service = $service;
		$this->handler = $handler;
		$this->parser = $parser;
	}

	public function getResource() {
		return $this->resource;
	}

	public function setResource($resource) {
		$this->resource = $resource;
	}

	public function getResponseParser() {
		return $this->parser;
	}

	public function setResponseParser(ResponseParser $parser) {
		$this->parser = $parser;
	}

	public function process() {
		$result = $this->handler->send($this);

		return $result;
	}
}