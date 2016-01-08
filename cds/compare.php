<?php
/**
 * CDS Catalog compare page
 *
 * Copyright Catalog Data Solutions, Inc.  All Rights Reserved.
 */
function cds_getCompareHTML($cds) {
    $html = "        <div id='cds-content' class='cds-content'>\n".
            "            <div id='cds-product-compare-container'></div>\n".
            "            <script>\n".
            "                cds.productCompareTable.setProductURLTemplate('".$cds->getCatalogURL("?page=product&id=%PRODUCT%")."');\n".
            "                cds.productCompareTable.setParentElementId('cds-product-compare-container');\n".
            "                cds.productCompareTable.load();\n".
            "            </script>\n".
            "        </div>\n";
    return $html;
}
?>
