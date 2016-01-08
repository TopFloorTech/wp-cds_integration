<?php
/**
 * CDS Catalog cart page
 *
 * Copyright Catalog Data Solutions, Inc.  All Rights Reserved.
 */
function cds_getCartHTML($cds) {
    $html = "        <div id='cds-content' class='cds-content'>\n".
            "            <h1>RFQ Cart</h1>\n".
            "            <div>Click <a href='".$cds->getCatalogURL("?page=search")."'>here</a> to continue product selection</div>\n".
            "            <div id='cds-cart-container'></div>\n".
            "            <script>\n".
            "                cds.cart.setParentElementId('cds-cart-container');\n".
            "                cds.cart.load();\n".
            "            </script>\n".
            "        </div>\n".
            "\n";
    return $html;
}
?>
