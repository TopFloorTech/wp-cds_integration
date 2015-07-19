<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 10:28 AM
 */

namespace TopFloor\Cds\ResponseParsers;

abstract class ResponseParser implements ResponseParserInterface {
	public abstract function parse($response, $containsHeaders = true);
}