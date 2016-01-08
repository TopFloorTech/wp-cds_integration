<?php
/**
 * CDS Catalog page header.
 *
 * Copyright Catalog Data Solutions, Inc.  All Rights Reserved.
 *
 * This needs to be called in any page which would use the CDS
 * JavaScript library.  Must be called after loading jQuery.
 */
function cds_getHeadHTML($cds) {
    $html = "        <script src='//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js'></script>\n".
            "        <script src='//".htmlspecialchars($cds->host)."/catalog3/js/cds-catalog-min.js'></script>\n".
            "        <link href='//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/smoothness/jquery-ui.min.css' type='text/css' rel='stylesheet'>\n".
            "        <link href='//".htmlspecialchars($cds->host)."/catalog3/css/catalog-3.1.css' rel='stylesheet' />\n".
            "        <script>\n".
            "            cds.setDomain(".json_encode($cds->domain).");\n".
            "            cds.setRemoteServerBaseURL(".json_encode("//$cds->host/catalog3").");\n";
    if ($cds->cdsNeedsOnLoad) {
        $html .= "            window.onload = function () {\n".
                 "                if (typeof cdsHandleWindowOnLoad === 'function') {\n".
                 "                    cdsHandleWindowOnLoad();\n".
                 "                }\n";
    if ($cds->page === "product") {
        $html .= "                _cdsSetCustomProductLabel()\n";
     }
        $html .= "            };\n";
    }
    if ($cds->page === "product") {
        $html .= "            function _cdsSetCustomProductLabel() {\n".
                 "                'use strict';\n".
                 "                var i, j, attributes, a, e, pn, v;\n".
                 "                if (typeof cdsGetCustomProductNumber === 'function') {\n".
                 "                    attributes = {};\n".
                 "                    for (i in cds.productAttributes) {\n".
                 "                        a = {};\n".
                 "                        for (j in cds.productAttributes[i]) {\n".
                 "                            a[j] = cds.productAttributes[i][j];\n".
                 "                        }\n".
                 "                        attributes[i] = a;\n".
                 "                    }\n".
                 "                    for (i in attributes) {\n".
                 "                        a = attributes[i];\n".
                 "                        if (a.dataType === 'range') {\n".
                 "                            a.value = document.getElementById('cds-dv-' + i).value;\n".
                 "                        } else if (a.dataType === 'list') {\n".
                 "                            e = document.getElementById('cds-dv-' + i);\n".
                 "                            a.value = e.options[e.selectedIndex].value;\n".
                 "                        } else if (a.dataType === 'multilist') {\n".
                 "                            v = [];\n".
                 "                            for (j = 0; j < 100; j++) {\n".
                 "                                e = document.getElementById('cds-dv-' + i + '-' + j);\n".
                 "                                if (e) {\n".
                 "                                    if (e.checked) {\n".
                 "                                        v.push(e.value);\n".
                 "                                    }\n".
                 "                                } else {\n".
                 "                                    break;\n".
                 "                                }\n".
                 "                            }\n".
                 "                            a.value = v;\n".
                 "                        } else if (a.dataType === 'text') {\n".
                 "                            a.value = document.getElementById('cds-dv-' + i).value;\n".
                 "                        }\n".
                 "                    }\n".
                 "                    pn = cdsGetCustomProductNumber(".json_encode($cds->productID).", ".json_encode($cds->categoryID).", attributes);\n".
                 "                    if (pn) {\n".
                 "                        e = document.getElementsByName('cds-product-number');\n".
                 "                        for (i = 0; i < e.length; i++) {\n".
                 "                            e[i].innerHTML = pn;\n".
                 "                        }\n".
                 "                        if (cds.CADRequester) {\n".
                 "                            cds.CADRequester.setCADResultFileName(pn);\n".
                 "                        }\n".
                 "                    }\n".
                 "                }\n".
                 "            }\n";
    }

    $html .= "        </script>\n";
    if ($cds->customCDSStyle != null) {
        $html .= "        <link href='//".htmlspecialchars($cds->host)."/catalog3/d/".$cds->domain."/php/".$cds->customCDSStyle."' rel='stylesheet' />\n";
    }
    if ($cds->customCDSScript != null) {
        $html .= "        <script src='//".htmlspecialchars($cds->host)."/catalog3/d/".$cds->domain."/".$cds->customCDSScript."'></script>\n";
    }

    return $html;
}
?>
