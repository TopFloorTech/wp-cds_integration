<?php
/**
 * CDS Catalog search page
 *
 * Copyright Catalog Data Solutions, Inc.  All Rights Reserved.
 */
function cds_getSearchHTML($cds) {
    $keywords = null;
    if (isset($cds->request['cdskeys'])) {
        $keywords = $cds->request['cdskeys'];
    }

    $load_products = sizeof($cds->category['children']) === 0 || isset($cds->request['filter'])
            || isset($cds->request['sort']) || $keywords !== null;

    $html = "        <script src='//".htmlspecialchars($cds->host)."/catalog3/js/cds-faceted-search2.js'></script>\n".
            "        <script>\n".
            "            window.onload = function () {\n".
            "                'use strict';\n".
            "                var i = -1;\n".
            "                jQuery('.cds-browse-list img').each(function (index, element) {\n".
            "                    if (jQuery(this).width() > i) {\n".
            "                        i = jQuery(this).width();\n".
            "                    }\n".
            "                });\n".
            "                if (i > -1) {\n".
            "                    cds.makeSameWidth(jQuery('.cds-browse-list').children(), null, i);\n".
            "                    cds.makeSameHeight(jQuery('.cds-browse-list').children());\n".
            "                }\n".
            "            };\n".
            "        </script>\n".
            "        <div id='cds-content' class='cds-content'>\n".
            "            <div id='cds-search-left-container'>\n".
            "            </div> <!-- cds-search-left-container -->\n".
            "            <script>\n".
            "                cds.facetedSearch.searchURLTemplate = '".$cds->getCatalogURL("?page=search&cid=%CATEGORY%")."';\n".
            "                cds.facetedSearch.productURLTemplate = '".$cds->getCatalogURL("?page=product&cid=%CATEGORY%&id=%PRODUCT%")."';\n".
            "                cds.facetedSearch.categoryId = ".json_encode($cds->categoryID).";\n".
            "                cds.facetedSearch.displayPowerGrid = ".json_encode($cds->enablePowerGrid).";\n".
            "                cds.facetedSearch.renderProductsListType = 'table';\n".
            "                cds.facetedSearch.showUnitToggle = ".json_encode($cds->enableUnitToggle).";\n".
            "                cds.facetedSearch.showKeywordSearch = ".json_encode($cds->enableSearchWithinResults).";\n".
            "                cds.facetedSearch.appendUnitToProductURL = false;\n".
            "                cds.facetedSearch.loadProducts = ".json_encode($load_products).";\n".
            "                cds.facetedSearch.init();\n".
            "                cds.facetedSearch.compareCart = new cds.ProductCompareCart();\n".
            "                cds.facetedSearch.compareCart.setComparePageURL('".$cds->getCatalogURL("?page=compare")."');\n".
            "                cds.facetedSearch.compareCart.setMaxProducts(6);\n".
            "            </script>\n".
            "            <div id='cds-search-right-container' class='cds-browse-container'>\n".
            "                <ul class='cds-crumbs'>\n";

    foreach ($cds->category['crumbs'] as $c) {
        if ($c['id'] === $cds->rootCategoryID) {
            $html .= "                    <li><a href='".$cds->getCatalogURL("?page=search")."'>".$c['label']."</a></li>\n";
            $html .= "                    <li><span>&gt;</span></li>\n";
        } else {
            $html .= "                    <li><a href='".$cds->getCatalogURL("?page=search&cid=".urlencode($c['id']))."'>".$c['label']."</a></li>\n";
            $html .= "                    <li><span>&gt;</span></li>\n";
        }
    }
    $html .= "                    <li>".$cds->category['label']."</li>\n";
    $html .= "                </ul>\n";

    if (sizeof($cds->category['crumbs']) === 0 && !(isset($cds->category['searchImageURL']) || $load_products)) {
        $html .= "                <h2>Select Category</h2>\n";
    } else {
        if ($keywords !== null) {
            $html .= "                <h2>".ucwords($keywords).": ".$cds->category['label']."</h2>\n";
        } else {
            $html .= "                <h2>".$cds->category['label']."</h2>\n";
        }
    }

    if (isset($cds->category['searchImageURL']) || isset($cds->category['searchHeaderHTML'])
            || isset($cds->category['description'])) {
        $html .= "                <div class='head'>\n";
        if (isset($cds->category['searchImageURL'])) {
            $html .= "                    <img src='".htmlspecialchars($cds->category['searchImageURL'])."'>\n";
        }
        if (isset($cds->category['searchHeaderHTML'])) {
            $html .= "                    <p>".$cds->category['searchHeaderHTML']."</p>\n";
        } elseif (isset($cds->category['description'])) {
            $html .= "                    <p>".$cds->category['description']."</p>\n";
        }
            $html .= "                </div>\n";
    }
    if (!$load_products) {
        $html .= "                <ul class='cds-browse-list'>\n";
        foreach ($cds->category['children'] as $c) {
            $html .= "                    <li>\n";
            $html .= "                        <a href='".$cds->getCatalogURL("?page=search&cid=".urlencode($c['id']))."'>\n";
            if (isset($c['browseImageURL'])) {
                $html .= "                            <img src='".htmlspecialchars($c['browseImageURL'])."'\n";
                if (isset($c['imageTitle'])) {
                    $html .= "                                    title='".htmlspecialchars($c['imageTitle'])."'\n";
                }
                if (isset($c['imageAlt'])) {
                    $html .= "                                    alt='".htmlspecialchars($c['imageAlt'])."'\n";
                }
                $html .= "                            >\n";
            }
            $html .= "                            <h3>".$c['label']."</h3>\n";
            if (isset($c['description'])) {
                $html .= "                            <p>".$c['description']."</p>\n";
            }
            $html .= "                        </a>\n";
            $html .= "                    </li>\n";
        }
        $html .= "                </ul>\n";

    } else {
        $html .= "                <div id='cds-product-container'>\n";
        $html .= "                    <div id='cds-product-loading-container'>\n";
        $html .= "                        <img src='//".htmlspecialchars($cds->host)."/catalog3/images/progress_animation_large.gif'>\n";
        $html .= "                        <div>Loading products...</div>\n";
        $html .= "                    </div>\n";
        $html .= "                </div>\n";
    }
    $html .= "            </div> <!-- faceted-search-right-container -->\n";
    $html .= "        </div>\n";

    return $html;
}
?>
