<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 11:07 AM
 */

namespace TopFloor\Cds\RequestHandlers;

use TopFloor\Cds\CdsRequest;

interface RequestHandlerInterface {
	public function send(CdsRequest $request);
}