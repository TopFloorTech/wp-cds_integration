<?php
/**
 * CDS Catalog keyword search results page
 *
 * Copyright Catalog Data Solutions, Inc.  All Rights Reserved.
 */
function cds_getKeysHTML($cds) {
    $html = "            <div id='cds-content' class='cds-content'>\n".
            "                <h1>Keyword Search Results</h1>\n".
            "                <div class='info'>Only the product name and first matching attribute is shown. Only the first 50\n".
            "                    products found are shown.</div>\n".
            "                <div id='cds-keys-results'>\n".
            "                    <h4>\n".
            "                        <img src='http://".htmlspecialchars($cds->host)."/catalog3/images/progress_animation.gif'>\n".
            "                        Loading results please wait...\n".
            "                    </h4>\n".
            "                </div>\n".
            "                <script>\n".
            "                    cds.textLabels['keyword_search_results.attribute_column_label'] = 'Attribute';\n".
            "                    cds.textLabels['keyword_search_results.value_column_label'] = 'Value';\n".
            "                    cds.keys.containerElementId = 'cds-keys-results';\n".
            "                    cds.keys.productURLTemplate = '".$cds->getCatalogURL("?page=product&id=%PRODUCT%")."';\n".
            "                    cds.keys.categoryURLTemplate = '".$cds->getCatalogURL("?page=search&cid=%CATEGORY%")."';\n".
            "                    cds.keys.load();\n".
            "                </script>\n".
            "            </div>\n";
    return $html;
}
?>
