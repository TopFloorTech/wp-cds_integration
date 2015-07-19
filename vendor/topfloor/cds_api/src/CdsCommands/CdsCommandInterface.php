<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:55 PM
 */

namespace TopFloor\Cds\CdsCommands;


interface CdsCommandInterface {
	public function execute();

	public function setParameters($parameters = array());

	public function getParameters($encode = false);

	public function getDependencies();
}