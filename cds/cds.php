<?php
/**
 * CDS catalog reference implementation
 *
 * Copyright Catalog Data Solutions, Inc.  All Rights Reserved.
 *
 * Under normal circumstances, implementations should just need to
 * edit the constants in this file and, possibly, change the HTML
 * in the cds_getXXXHTML() functions: cds_getSearchHTML(),
 * cds_getProductHTML(), etc.
 *
 * Character encoding guidelines to prevent security problems:
 * do all encoding inline, do not pre-encode anything
 * encode in the following order:
 *   1. encode url query string items with urlencode()
 *   2. encode all javascript/json variables with json_encode()
 *   3. encode all HTML attributes with htmlspecialchars()
 *   4. HTML inner text should be encoded with htmlspecialchars() if
 *      coming from page URL
 *   HTML inner text should not be encoded if coming directly from
 *   CDS web service.  This should be used carefully as HTML inner
 *   text is not secure by itself.
 */
class CDS {
    // constants
    public $domain = 'gilman';
    public $host = 'www.product-config.net';
    public $unitSystem = 'english';
    public $enableUnitToggle = false;
    public $enableSearchWithinResults = false;
    public $enablePowerGrid = true;
    public $enableRFQCart = true;
    public $enableSpecSheet = true;
    public $enableEmbeddedViewer = false;
    public $rootCategoryID = 'root';
    public $customCDSStyle = null;
    public $customCDSScript = "cds.js";
    public $cdsNeedsOnLoad = true;
    public $pageTitlePrefix = '';
    public $pageTitleSuffix = ' | Gilman Precision, USA';
    public $shouldRewriteLinks = false;
    public $shouldParseURLs = false;
    public $catalogURLPrefix = '/catalog';

    public $textLabelAddToCart = 'Add to Cart';

    public $request = null;
    public $page = null;
    public $productID = null;
    public $product = null;
    public $categoryID = null;
    public $category = null;
    public $isCDSHosted = true;

    public function __construct() {
        $this->request = $_REQUEST;
        $this->isCDSHosted = strrpos($_SERVER['HTTP_HOST'], 'product-config.net') !== FALSE;
        // $this->isCDSHosted = false;

        // try to get unit system from request then cookie
        if (isset($this->request['unit'])) {
            $this->unitSystem = $this->request['unit'];
        } else if (isset($_COOKIE['cds_catalog_unit'])) {
            $this->unitSystem = $_COOKIE['cds_catalog_unit'];
        }
        setcookie('cds.catalog.unit', $this->unitSystem, time() + (86400 * 365));

        // determine which catalog page, and load page specific resources
        // from web services
        $this->page = 'search';

        if (isset($this->request['page'])) {
            $this->page = $this->request['page'];
        }
        if (isset($this->request['cid'])) {
            $this->categoryID = $this->request['cid'];
        }
        if (isset($this->request['id'])) {
            $this->productID = $this->request['id'];
        }

        if ($this->shouldParseURLs && !$this->isCDSHosted) {
            if ($this->categoryID === null && preg_match('%^/([^/\.]+)\.html%', $_SERVER['REQUEST_URI'], $m)) {
                $this->categoryID = $m[1];
            } elseif ($this->productID === null
                        && preg_match('%^/([^/\.]+)/([^/]+)/?%', $_SERVER['REQUEST_URI'], $m)) {
                $this->page = 'product';
                $this->categoryID = $m[1];
                $this->productID = $m[2];
            }
        }

        if ($this->page !== 'product' && $this->categoryID === null) {
            $this->categoryID = $this->rootCategoryID;
        }

        // product page
        if ($this->page === 'product') {
            require_once('CDSWebService.php');
            $cds_service = new CDSWebService($this->host, $this->unitSystem);
            $this->product = $cds_service->sendProductRequest($this->domain, $this->productID, $error,
                    $this->categoryID);
            if ($error !== false) {
                header('Location: catalog-error.html');
                return;
            }
            $this->category = $this->product['category'];
            $this->categoryID = isset($this->category['id']) ? $this->category['id'] : $this->rootCategoryID;

        // search page
        } elseif ($this->page === 'search') {
            require_once('CDSWebService.php');
            $cds_service = new CDSWebService($this->host, $this->unitSystem);
            $this->category = $cds_service->sendCategoryRequest($this->domain, $this->categoryID, $error);
            if ($error !== false) {
                header('Location: catalog-error.html');
                return;
            }
        }
    }

    public function __toString() {
        require_once('CDSWebService.php');
        $cws = new CDSWebService();
        return 'CDS catalog version '.CDSWebService::VERSION.' [page: '.$this->page.', category ID: '.$this->categoryID
                .', product ID: '.$this->productID.']';
    }

    public function getCatalogURL($catalogURL) {
        if ($this->shouldRewriteLinks && !$this->isCDSHosted) {
            # compare, keys
            if ($catalogURL === '?page=product&id=%PRODUCT%') {
                return $this->catalogURLPrefix.'/'.$this->rootCategoryID.'/%PRODUCT%';
            # search
            } elseif ($catalogURL === '?page=product&cid=%CATEGORY%&id=%PRODUCT%') {
                return $this->catalogURLPrefix.'/%CATEGORY%/%PRODUCT%';
            # cart, product
            } elseif ($catalogURL === '?page=search') {
                return $this->catalogURLPrefix.'/'.$this->rootCategoryID.'.html';
            # compare, search
            } elseif ($catalogURL === '?page=search&cid=%CATEGORY%') {
                return $this->catalogURLPrefix.'/%CATEGORY%.html';
            # search, product
            } elseif (strrpos($catalogURL, '?page=search&cid=') === 0) {
                return $this->catalogURLPrefix.'/'.substr($catalogURL, strlen('?page=search&cid=')).'.html';
            # product - related
            } elseif (strrpos($catalogURL, '?c=fsearch&cid=') === 0) {
                return $this->catalogURLPrefix.'/'.substr($catalogURL, strlen('?c=fsearch&cid=')).'.html';
            # search
            } elseif ($catalogURL === '?page=compare') {
                return $this->catalogURLPrefix.'/'.$this->rootCategoryID.'.html?page=compare';
            # product
            } elseif ($catalogURL === '?page=cart') {
                return $this->catalogURLPrefix.'/'.$this->rootCategoryID.'.html?page=cart';
            # product
            } elseif ($catalogURL === '?page=keys') {
                return $this->catalogURLPrefix.'/'.$this->rootCategoryID.'.html?page=keys';
            }
        }

        return $catalogURL;
    }

    public function getPageTitle() {
        if ($this->product !== null) {
            if (isset($this->product['pageTitle'])) {
                return $this->product['pageTitle'];
            } else {
                return $this->pageTitlePrefix.$this->product['label'].$this->pageTitleSuffix;
            }
        } elseif ($this->category !== null) {
            if (isset($this->category['searchPageTitle'])) {
                return $this->category['searchPageTitle'];
            } else {
                return $this->pageTitlePrefix.$this->category['label'].$this->pageTitleSuffix;
            }
        } elseif ($this->page === 'compare') {
            return $this->pageTitlePrefix.'Product Compare'.$this->pageTitleSuffix;
        } elseif ($this->page === 'cart') {
            return $this->pageTitlePrefix.'Cart'.$this->pageTitleSuffix;
        }

        return $this->pageTitlePrefix.'Product Catalog'.$this->pageTitleSuffix;
    }

    public function getMetaDescription() {
        if ($this->product !== null && isset($this->product['metaDescription'])) {
            return $this->product['metaDescription'];
        } elseif ($this->category !== null && isset($this->category['metaDescription'])) {
            return $this->category['metaDescription'];
        }

        return '';
    }

    public function getMetaKeywords() {
        if ($this->product !== null && isset($this->product['metaKeywords'])) {
            return $this->product['metaKeywords'];
        } elseif ($this->category !== null && isset($this->category['metaKeywords'])) {
            return $this->category['metaKeywords'];
        }

        return '';
    }

    public function getPageName() {
        return $this->page;
    }

    public function getCatalogHTML() {
        if ($this->page === 'search') {
            return $this->getSearchHTML();
        } elseif ($this->page === 'product') {
            return $this->getProductHTML();
        } elseif ($this->page === 'keys') {
            return $this->getKeysHTML();
        } elseif ($this->page === 'compare') {
            return $this->getCompareHTML();
        } elseif ($this->page === 'cart') {
            return $this->getCartHTML();
        }
    }

    public function setHost($host) {
        $this->host = $host;
    }

    public function getHeadHTML() {
        require_once('head.php');
        return cds_getHeadHTML($this);
    }

    public function getSearchHTML() {
        require_once('search.php');
        return cds_getSearchHTML($this);
    }

    public function getProductHTML() {
        require_once('products.php');
        return cds_getProductHTML($this);
    }

    public function getKeysHTML() {
        require_once('keys.php');
        return cds_getKeysHTML($this);
    }

    public function getCompareHTML() {
        require_once('compare.php');
        return cds_getCompareHTML($this);
    }

    public function getCartHTML() {
        require_once('cart.php');
        return cds_getCartHTML($this);
    }
}
?>
