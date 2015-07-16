<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:50 PM
 */

namespace Cds\UrlHandlers;


interface UrlHandlerInterface {
	public function construct($parameters = array());

	public function deconstruct($url);

	public function get($parameter);

	public function getCurrentUri();

	public function parameterIsSet($parameter);
}