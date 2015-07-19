<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 11:08 AM
 */

namespace TopFloor\Cds\RequestHandlers;

use TopFloor\Cds\CdsRequest;
use TopFloor\Cds\Exceptions\CdsServiceException;

class FsockopenRequestHandler extends RequestHandler {
	public function send(CdsRequest $request) {
		$host = $this->service->getHost();
		$parser = $request->getResponseParser();
		$resource = $request->getResource();

		$fp = fsockopen($host, 80);

		if (!$fp) {
			throw new CdsServiceException('Could not connect to the catalog server.');
		}

		$in = "GET $resource HTTP/1.1\r\n";
		$in .= "Host: $host\r\n";
		$in .= "User-Agent: PHP CDS reference web service implementation\r\n";
		$in .= "Cache-Control: no-cache\r\n";
		$in .= "Pragma: no-cache\r\n";
		$in .= "Connection: Close\r\n\r\n";

		fwrite($fp, $in);

		$out = '';
		while (!feof($fp)) {
			$out .= fgets($fp, 2048);
		}

		fclose($fp);

		return $parser->parse($out);
	}
}