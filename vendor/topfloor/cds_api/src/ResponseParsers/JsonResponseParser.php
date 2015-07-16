<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 10:26 AM
 */

namespace TopFloor\Cds\ResponseParsers;

use TopFloor\Cds\Exceptions\CdsServiceException;

class JsonResponseParser extends ResponseParser {
	public function parse($response, $containsHeaders = true) {
		$contentOnly = !$containsHeaders;

		$json = '';
		$inContent = $contentOnly;
		$isChunked = false;
		$isOk = $contentOnly;
		$chunkedOddLine = false;

		foreach (explode("\r\n", $response) as $line) {
			if (!$inContent) {
				if (strpos($line, '200 OK') !== false) {
					$isOk = true;
				} elseif ($line === 'Transfer-Encoding: chunked') {
					$isChunked = true;
				} elseif ($line === '') {
					$inContent = true;
				}
			} else {
				if (!$isOk) {
					throw new CdsServiceException('Could not connect to the catalog server.');
				}

				if ($isChunked) {
					if ($line === '0') {
						break;
					}

					$chunkedOddLine = !$chunkedOddLine;

					if ($chunkedOddLine) {
						continue;
					}
				}

				$json .= $line;
			}
		}

		return json_decode($json, true);
	}
}