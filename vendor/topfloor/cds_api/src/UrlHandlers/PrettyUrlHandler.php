<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/19/2015
 * Time: 10:08 PM
 */

namespace TopFloor\Cds\UrlHandlers;

abstract class PrettyUrlHandler extends UrlHandler
{
    protected $defaultPage = 'search';

    protected $pagePrefixes = array(
        'cart' => 'cart',
        'compare' => 'compare',
        'keys' => 'keys',
        'products' => 'products',
        'search' => 'search',
    );

    public function getPageFromUri($uri = null, $baseUri = null) {
        if (is_null($uri)) {
            $uri = $this->getCurrentUri();
        }

        if (substr($uri, 0, 1) == '/') {
            $uri = substr($uri, 1);
        }

        if (!is_null($baseUri)) {
            if (substr($uri, 0, strlen($baseUri)) === $baseUri) {
                $uri = substr($uri, strlen($baseUri));
            }
        }

        $pathParts = explode('/', $uri);

        if (isset($pathParts[0]) && array_key_exists($pathParts[0], $this->pagePrefixes)) {
            return $this->pagePrefixes[$pathParts[0]];
        }

        return $this->defaultPage;
    }

    public function getUriForPage($page, $basePath = null) {
        $uri = '/';

        if (!is_null($basePath)) {
            if (substr($basePath, 0, 1) == '/') {
                $basePath = substr($basePath, 1);
            }

            $uri .= $basePath;
        }

        if ($page == $this->defaultPage) {
            return $uri;
        }

        if ($page != $this->defaultPage) {
            if (isset($this->pagePrefixes[$page])) {
                $uri .= '/' . $this->pagePrefixes[$page];
            } else {
                $uri .= '/' . $page;
            }
        }

        return $uri;
    }
}