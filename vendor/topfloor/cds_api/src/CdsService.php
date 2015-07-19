<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 10:14 AM
 */

namespace TopFloor\Cds;

use TopFloor\Cds\CdsCommands\CdsCommand;
use TopFloor\Cds\Helpers\CdsBreadcrumbsHelper;
use TopFloor\Cds\Helpers\CdsOutputHelper;
use TopFloor\Cds\UrlHandlers\DefaultUrlHandler;
use TopFloor\Cds\RequestHandlers\CurlRequestHandler;
use TopFloor\Cds\RequestHandlers\FsockopenRequestHandler;
use TopFloor\Cds\RequestHandlers\RequestHandler;
use TopFloor\Cds\ResponseParsers\JsonResponseParser;
use TopFloor\Cds\ResponseParsers\ResponseParser;

class CdsService {
	private $host;
	private $domain;
	private $unitSystem = 'english';
	private $responseParser;
	private $requestHandler;
	private $commands;
	private $urlHandler;
	private $breadcrumbs;
	private $dependencies;
	private $categoryInfo;

	public function __construct($host, $domain) {
		$this->host = $host;
		$this->domain = $domain;
		$this->responseParser = new JsonResponseParser();
		$this->commands = new CdsCommandCollection();
		$this->urlHandler = new DefaultUrlHandler($this);
		$this->breadcrumbs = new CdsBreadcrumbsHelper($this);
		$this->output = new CdsOutputHelper($this);
		$this->categoryInfo = new CdsCategoryInfo($this);

		$this->dependencies = new CdsDependencyCollection(array(
			'js' => array('cds-catalog' => $this->baseUrl() . '/js/cds-catalog-min.js',),
			'css' => array('cds-catalog' => $this->baseUrl() . '/css/catalog-3.1.css',),
			'settings' => array(
				'host' => $this->getHost(),
				'domain' => $this->getDomain(),
				'unitSystem' => $this->getUnitSystem(),
				'baseUrl' => $this->baseUrl(),
			),
		));

		// Use cURL if it is available, or fall back to Fsockopen
		if (function_exists('curl_version')) {
			$this->requestHandler = new CurlRequestHandler($this);
		} else {
			$this->requestHandler = new FsockopenRequestHandler($this);
		}
	}

    public function baseUrl() {
        $host = htmlspecialchars($this->getHost());

        return 'http://' . $host . '/catalog3';
    }

	public function getHost() {
		return $this->host;
	}

	public function getDomain() {
		return $this->domain;
	}

	public function getCategoryInfo() {
		return $this->categoryInfo;
	}

	public function getUrlHandler() {
		return $this->urlHandler;
	}

	public function getBreadcrumbsHelper() {
		return $this->breadcrumbs;
	}

	public function setBreadcrumbsHelper(CdsBreadcrumbsHelper $breadcrumbsHelper) {
		$this->breadcrumbs = $breadcrumbsHelper;
	}

	public function getOutputHelper() {
		return $this->output;
	}

	public function setOutputHelper($outputHelper) {
		$this->output = $outputHelper;
	}

	public function setUrlHandler($urlHandler) {
		$this->urlHandler = $urlHandler;
	}

	public function getRequestHandler() {
		return $this->requestHandler;
	}

	public function setRequestHandler(RequestHandler $requestHandler) {
		$this->requestHandler = $requestHandler;
	}

	public function getResponseParser() {
		return $this->responseParser;
	}

	public function setResponseParser(ResponseParser $parser) {
		$this->responseParser = $parser;
	}

	public function getUnitSystem() {
		return $this->unitSystem;
	}

	public function loadingGraphic() {
		$url = 'http://' . htmlspecialchars($this->getHost())
		       . '/catalog3/images/progress_animation_large.gif';

		return $url;
	}

	public function setUnitSystem($unitSystem) {
		$this->unitSystem = $unitSystem;
	}

	public function request($resource) {
		$request = new CdsRequest($this, $this->requestHandler, $this->responseParser);
		$request->setResource($resource);

		return $request;
	}

	public function productRequest($id, $category = null) {
		$resourceTemplate = '/catalog3/service?o=product&d=%s&id=%s&unit=%s';
		$categoryTemplate = '&cid=%s';

		$resource = sprintf($resourceTemplate, $this->getDomain(), $id, $this->getUnitSystem());

		if (!is_null($category)) {
			$resource .= sprintf($categoryTemplate, $category);
		}

		return $this->request($resource);
	}

	public function jsSettings() {
		$dependencies = $this->getDependencies();
        $settings = $dependencies->settings();

		$output = "window.TopFloor = window.TopFloor || {};\n";
		$output .= "TopFloor.Cds = TopFloor.Cds || {};\n";
		$output .= "TopFloor.Cds.Settings = '" . json_encode($settings) . "';";

		return $output;
	}

	public function getDependencies() {
		$dependencies = new CdsDependencyCollection();
		$dependencies->addDependencies($this->dependencies);
		$dependencies->addDependencies($this->commands->getDependencies());

		return $dependencies;
	}

	public function command(CdsCommand $command) {
		$this->commands->addCommand($command);
	}

	/**
	 * Return all executable JS defined by this service.
	 *
	 * @return string
	 */
	public function execute() {
		$output = '';

		$output .= 'TopFloor.Cds.initialize();' . "\n";

		$output .= $this->commands->execute();

		return $output;
	}
}
