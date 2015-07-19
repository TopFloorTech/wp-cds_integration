<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 11:35 AM
 */

namespace TopFloor\Cds\RequestHandlers;

use TopFloor\Cds\CdsRequest;
use TopFloor\Cds\Exceptions\CdsServiceException;

class CurlRequestHandler extends RequestHandler {

	public function send(CdsRequest $request) {
		$host = $this->service->getHost();
		$parser = $request->getResponseParser();
		$resource = $request->getResource();

		$ch = curl_init("http://$host$resource");

		if (!$ch) {
			throw new CdsServiceException('Could not connect to the catalog server.');
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"GET $resource HTTP/1.1",
			'User-Agent: PHP CDS reference web service implementation',
			'Cache-Control: no-cache',
			'Pragma: no-cache',
			'Connection: Close'
		));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$out = curl_exec($ch);
		if (!$out) {
			throw new CdsServiceException('Could not connect to the catalog server.');
		}

		curl_close($ch);

		return $parser->parse($out, false);
	}
}