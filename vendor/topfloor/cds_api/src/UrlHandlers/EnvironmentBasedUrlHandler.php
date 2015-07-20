<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/19/2015
 * Time: 10:36 PM
 */

namespace TopFloor\Cds\UrlHandlers;

abstract class EnvironmentBasedUrlHandler extends PrettyUrlHandler
{
    public function construct($parameters = array())
    {
        $parameters = $this->buildParameters($parameters);

        $url = $this->getUriForPage($parameters['page']);

        if (!empty($parameters['cid'])) {
            $url .= '/' . $parameters['cid'];
        }

        if (!empty($parameters['id'])) {
            $url .= '/' . $parameters['id'];
        }

        return $url;
    }

    public function deconstruct($url)
    {
        if (substr($url, 0, 1) == '/') {
            $url = substr($url, 1);
        }

        $basePath = false;

        foreach ($this->getEnvironments() as $envBasePath => $envCategoryId) {
            if ($url == $envBasePath || $url == $envBasePath . '/') {
                $basePath = $envBasePath;

                break;
            }

            if (preg_match('|^' . $envBasePath . '/|', $url) !== false) {
                $basePath = $envBasePath;

                break;
            }
        }

        $parameters = array();

        if ($basePath) {
            $parameters['page'] = $this->getPageFromUri($url, $basePath);
            $parameters['cid'] = $this->getBaseCategoryId($basePath);

            $url = substr($url, strlen($basePath));

            if (substr($url, 0, 1) == '/') {
                $url = substr($url, 1);
            }

            if (substr($url, strlen($url)) == '/') {
                $url = substr($url, 0, strlen($url) - 1);
            }

            $pathParts = explode('/', $url);

            if (count($pathParts) > 0) {
                if ($pathParts[0] == $parameters['page']) {
                    array_shift($pathParts);
                }
            }

            if (!empty($pathParts[0])) {
                $parameters['cid'] = $pathParts[0];
            }

            if (!empty($pathParts[1])) {
                $parameters['id'] = $pathParts[1];
            }
        }

        return $this->buildParameters($parameters);
    }

    protected abstract function getEnvironments();

    public function getPageFromUri($uri = null, $baseUri = null) {
        if (is_null($baseUri)) {
            $baseUri = $this->getCurrentEnvironment();
        }

        return parent::getPageFromUri($uri, $baseUri);
    }

    public function getUriForPage($page, $basePath = null) {
        if (is_null($basePath)) {
            $basePath = $this->getCurrentEnvironment();
        }

        return parent::getUriForPage($page, $basePath);
    }

    public function buildParameters($parameters = array()) {
        $basePath = $this->getCurrentEnvironment();

        if ($basePath !== false) {
            $baseCategoryId = $this->getBaseCategoryId($basePath);

            if (!isset($parameters['cid'])) {
                $parameters['cid'] = $baseCategoryId;
            }
        }

        return parent::buildParameters($parameters);
    }

    protected function environmentIsActive($basePath)
    {
        $requestUri = $this->getCurrentUri();

        if ($requestUri == '/' . $basePath) {
            return true;
        }

        if ($requestUri == '/' . $basePath . '/') {
            return true;
        }

        return (preg_match('|^/' . $basePath . '/|', $requestUri) !== false);
    }

    protected function getCurrentEnvironment()
    {
        foreach ($this->getEnvironments() as $basePath => $categoryId) {
            if ($this->environmentIsActive($basePath)) {
                return $basePath;
            }
        }

        return false;
    }

    protected function getBaseCategoryId($uri = null)
    {
        if (is_null($uri)) {
            $uri = $this->getCurrentEnvironment();
        }

        $environments = $this->getEnvironments();

        if (!empty($environments[$uri] )) {
            return $environments[$uri];
        }

        return '';
    }
}