<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 4:57 PM
 */

namespace TopFloor\Cds\UrlHandlers;

class DefaultUrlHandler extends UrlHandler {
	protected $defaultPage = 'search';

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

	public function getPageFromUri($uri = null, $basePath = null)
	{
		// TODO: Implement getPageFromUri() method.
	}

	public function getUriForPage($page, $basePath = null)
	{
		// TODO: Implement getUriForPage() method.
	}
}
