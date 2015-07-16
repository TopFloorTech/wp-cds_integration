<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 11:39 AM
 */

namespace TopFloor\Cds\RequestHandlers;


use TopFloor\Cds\CdsRequest;
use TopFloor\Cds\Exceptions\CdsServiceException;

class SocketCreateRequestHandler extends RequestHandler {

	public function send(CdsRequest $request) {
		$host = $this->service->getHost();
		$parser = $request->getResponseParser();
		$resource = $request->getResource();
		$ip = gethostbyname($host);
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

		if ($socket === false) {
			throw new CdsServiceException('Could not connect to the catalog server.');
		}

		$result = socket_connect($socket, $ip, 80);

		if ($result === false) {
			throw new CdsServiceException('Could not connect to the catalog server.');
		}

		$in = "GET $resource HTTP/1.1\r\n";
		$in .= "Host: $host\r\n";
		$in .= "User-Agent: PHP CDS reference web service implementation\r\n";
		$in .= "Cache-Control: no-cache\r\n";
		$in .= "Pragma: no-cache\r\n";
		$in .= "Connection: Close\r\n\r\n";
		socket_write($socket, $in, strlen($in));

		$out = '';
		while ($buf = socket_read($socket, 2048)) {
			$out .= $buf;
		}

		socket_close($socket);

		return $parser->parse($out);
	}
}