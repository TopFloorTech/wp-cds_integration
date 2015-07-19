<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 4:57 PM
 */

namespace TopFloor\Cds\UrlHandlers;

class DefaultUrlHandler extends UrlHandler {

	public function construct($parameters = array()) {
		$url = '';

		foreach ($parameters as $key => $value) {
			$url .= '&' . urlencode($key) . '=' . urlencode($value);
		}

		return $url;
	}

	public function deconstruct($url) {
		$parameters = array();

		parse_str($url, $parameters);

		return $parameters;
	}
}
