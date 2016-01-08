<?php
/**
 * CDS Catalog product page
 *
 * Copyright Catalog Data Solutions, Inc.  All Rights Reserved.
 */
function cds_getProductHTML($cds) {
    // seperate attribute into different sections
    $attributes = array();
    $configurableAttributes = array();
    $attachments = array();
    $imageAttributes = array();
    $htmlAttributes = array();
    $footnotes = array();
    $notices = array();
    $attributeValues = array();
    $listPrice = null;
    $quantityDiscountSchedule = null;

    $html = "        <script>\n".
            "            cds.catalogCommand = 'products';\n".
            "            cds.productID = ".json_encode($cds->productID).";\n".
            "            cds.productAttributes = {\n";

    foreach ($cds->product['attributes'] as $i => $a) {
        if ($a['id'] === 'list_price') {
            $listPrice = preg_replace('/[^0-9\.]+/', '', $cds->product['attributeValues'][$i]);
        } else if ($a['id'] === 'quantity_discount_schedule') {
            $qds = explode('|', $cds->product['attributeValues'][$i]);
            foreach ($qds as $j =>$q) {
                if (preg_match('/(?<min>[0-9\.]*),(?<max>[0-9\.]*):(?<price>.*)/', $q, $m)) {
                    if ($quantityDiscountSchedule === null) {
                        $quantityDiscountSchedule = array();
                    }
                    $quantityDiscountSchedule[] = $m;
                }
            }
        } else if (isset($cds->product['attributeValues'][$i])) {
            $type = $a['dataType'];
            $value =  $cds->product['attributeValues'][$i];
            if ($a['visible'] !== false) {
                if ($type === 'list' || $type === 'range' || $type === 'multilist') {
                    $configurableAttributes[] = $a;
                    $attributeValues[$a['id']] = explode('|', $value);
                } elseif ($type === 'text') {
                    $configurableAttributes[] = $a;
                    $attributeValues[$a['id']] = $value;
                } elseif ($type === 'attachment') {
                    $attachments[] = $a;
                    $attributeValues[$a['id']] = $value;
                } elseif ($type === 'image') {
                    $imageAttributes[] = $a;
                    $attributeValues[$a['id']] = $value;
                } elseif ($type === 'html') {
                    $htmlAttributes[] = $a;
                    $attributeValues[$a['id']] = $value;
                } elseif ($type === 'footnote') {
                    $footnotes[] = $a;
                    $attributeValues[$a['id']] = $value;
                } elseif ($type === 'notice') {
                    $notices[] = $a;
                    $attributeValues[$a['id']] = $value;
                } else {
                    $attributes[] = $a;
                    $attributeValues[$a['id']] = $value;
                }
            }

            $html .= "                ".json_encode($a['id']).":{'dataType':".json_encode($a['dataType']);
            if (isset($a['label']) && strlen($a['label']) > 0) { $html .= ",'label':".json_encode($a['label']); }
            if (isset($a['toolTip']) && strlen($a['toolTip']) > 0) { $html .= ",'toolTip':".json_encode($a['toolTip']); }
            if (isset($a['imageURL']) && strlen($a['imageURL']) > 0) { $html .= ",'imageURL':".json_encode($a['imageURL']); }
            if (isset($a['precision'])) { $html .= ",'precision':".json_encode($a['precision']); }
            if (isset($a['step'])) { $html .= ",'step':".json_encode($a['step']); }
            if (isset($a['persistedUnit']) && strlen($a['persistedUnit']) > 0) { $html .= ",'unit':".json_encode($a['persistedUnit']); }
            if ($a['searchable']) { $html .= ",'searchable':true"; }
            if ($a['visible']) { $html .= ",'visible':true"; }
            if ($a['multiValue']) { $html .= ",'multiValue':true"; }
            if ($a['rangeSearchable']) { $html .= ",'rangeSearchable':true"; }
            if ($a['selectLTE']) { $html .= ",'selectLTE':true"; }
            if ($a['selectGTE']) { $html .= ",'selectGTE':true"; }
            if (isset($a['sortOrder'])) { $html .= ",'sortOrder':".json_encode($a['sortOrder']); }
            if (isset($a['cadDataType']) && strlen($a['cadDataType']) > 0) { $html .= ",'cadDataType':".json_encode($a['cadDataType']); }
            if (isset($a['cadParameterName']) && strlen($a['cadParameterName']) > 0) { $html .= ",'cadParameterName':".json_encode($a['cadParameterName']); }
            if ($a['dataType'] === 'list' || $a['dataType'] === 'range' || $a['dataType'] === 'multilist') {
                $html .= ",'value':[";
                if (is_array($value)) {
                    foreach ($value as $j => $v) {
                        if ($j > 0) {
                            $html .= ",";
                        }
                        $html .= json_encode($v);
                    }
                } else {
                    foreach (explode("|", $value) as $j => $v) {
                        if ($j > 0) {
                            $html .= ",";
                        }
                        $html .= json_encode($v);
                    }
                }
                $html .= "]";
            } elseif ($a['dataType'] === 'fraction') {
                $html .= ",'value':".json_encode(CDSWebService::toFraction($value));
            } else {
                $html .= ",'value':".json_encode($value);
            }
            $html .= "}";
            if ($i < sizeof($cds->product['attributes']) - 1) {
                $html .= ",";
            }
            $html .= "\n";
        }
    }

    $html .= "            };\n";
    if ($listPrice !== null) {
        $html .= "            cds.listPrice = ".json_encode($listPrice).";\n";
    }
    if ($quantityDiscountSchedule !== null) {
        $html .= "            cds.quantityDiscountSchedule = [";
        foreach ($quantityDiscountSchedule as $i => $qds) {
            if ($i > 0) {
                $html .= ",";
            }
            $html .= "\n                {'min':".(empty($qds['min']) ? 'null, ' : "'".$qds['min']."', ").
                                      "'max':".(empty($qds['max']) ? 'null, ' : "'".$qds['max']."', ").
                                      "'price':".(empty($qds['price']) ? 'null}' : "'".$qds['price']."'}");
        }
        $html .= "\n            ];\n";
    }
    $html .= "        </script>\n";
    $html .= "        <div id='cds-content' class='cds-content cds-product-page'>\n";
    $html .= "            <div class='cds-product-icons'>\n";
    $html .= "                <a href='javascript:window.print();'><img src='//".htmlspecialchars($cds->host)."/catalog3/images/print_page.png' /></a>\n";
    $html .= "                <a href='javascript:cds.emailPage()'><img src='//".htmlspecialchars($cds->host)."/catalog3/images/email_go.png' /></a>\n";
    $html .= "            </div>\n";
    $html .= "            <ul class='cds-crumbs'>\n";

    foreach ($cds->category['crumbs'] as $c) {
        if ($c['id'] === 'root') {
            $html .= "                <li><a href='".$cds->getCatalogURL("?page=search")."'>".$c['label']."</a></li>\n";
            $html .= "                <li><span>&gt;</span></li>\n";
        } else {
            $html .= "                <li><a href='".$cds->getCatalogURL("?page=search&cid=".urlencode($c['id']))."'>".$c['label']."</a></li>\n";
            $html .= "                <li><span>&gt;</span></li>\n";
        }
    }
    $html .= "                <li><a href='".$cds->getCatalogURL("?page=search&cid=".urlencode($cds->category['id']))."'>".$cds->category['label']."</a></li>\n";
    $html .= "                <li><span>&gt;</span></li>\n";
    $html .= "                <li>".$cds->product['label']."</li>\n";
    $html .= "            </ul>\n";

    if (isset($cds->product['headerHTML'])) {
        $html .= "            <div class='cds-product-custom-header'>".$cds->product['headerHTML']."</div>\n";
    }
    $html .= "            <div class='cds-product-header'>\n";
    if (isset($cds->product['imageURL'])) {
        $html .= "                <div id='cds-product-image-container' class='cds-product-image-container'>\n";
        $html .= "                    <div class='cds-product-image' id='cds-product-image'>\n";
        $html .= "                        <img src='".htmlspecialchars($cds->product['imageURL'])."'\n";
        if (isset($cds->product['imageAlt'])) {
            $html .= "                            alt='".htmlspecialchars($cds->product['imageAlt'])."'\n";
        }
        if (isset($cds->product['imageTitle'])) {
            $html .= "                            title='".htmlspecialchars($cds->product['imageTitle'])."'\n";
        }
        $html .= "                            />\n";
        $html .= "                    </div>\n";
        $html .= "                </div>\n";
    }
    $html .= "                <div class='cds-product-controls'>\n";
    if (isset($cds->product['description'])) {
        $html .= "                    <h1>".$cds->product['description']."</h1>\n";
        $html .= "                    <h2>Model: <span id='cds-product-number' name='cds-product-number'>".$cds->product['label']."</span></h2>\n";
    } else {
        $html .= "                    <h1 name='cds-product-number'>".$cds->product['label']."</h1>\n";
        $html .= "                    <h2>Category: ".$cds->category['label']."</h2>\n";
    }
    if ($cds->enableRFQCart) {
        $html .= "                    <div class='cds-product-cart'>\n";
        $html .= "                        <table>\n";
        $html .= "                            <tbody>\n";
        $html .= "                                <tr>\n";
        $html .= "                                    <td id='cds-product-price'>\n";
        $html .= "                                        Request Quote\n";
        if ($listPrice !== null) {
            $html .= "                                        <div id='cds-product-list-price'>\n";
            $html .= "                                            List Price: <span>".$listPrice."</span>\n";
            $html .= "                                        </div>\n";
        }
        $html .= "                                    </td>\n";
        $html .= "                                    <td>Quantity\n";
        $html .= "                                        <input type='text' size='3' value='1' id='cds-add-to-cart-quantity' />\n";
        $html .= "                                    </td>\n";
        $html .= "                                    <td><button id='cds-add-to-cart-button'>".$cds->textLabelAddToCart."</button></td>\n";
        $html .= "                                </tr>\n";
        $html .= "                            </tbody>\n";
        $html .= "                        </table>\n";
        $html .= "                    </div>\n";
        $html .= "                    <script>\n";
        $html .= "                        jQuery('#cds-add-to-cart-button').on('click', function () {\n";
        $html .= "                            'use strict';\n";
        $html .= "                            var pid, plabel, pdesc, pimg, purl, i, a, v, e, first,\n";
        $html .= "                                q = jQuery('#cds-add-to-cart-quantity').val();\n";
        $html .= "                            if (parseInt(q) > 0) {\n";
        $html .= "                                pid = ".json_encode($cds->product['id']).";\n";
        $html .= "                                plabel = ".json_encode($cds->product['label']).";\n";
        if (isset($cds->product['description'])) {
            $html .= "                                pdesc = ".json_encode($cds->product['description']).";\n";
        } else {
            $html .= "                                pdesc = ".json_encode($cds->category['label']).";\n";
        }
        $html .= "                                var first = true;\n";
        $html .= "                                for (i in cds.productAttributes) {\n";
        $html .= "                                    a = cds.productAttributes[i];\n";
        $html .= "                                    v = null;\n";
        $html .= "                                    if (a.dataType === 'range') {\n";
        $html .= "                                        e = document.getElementById('cds-dv-' + i);\n";
        $html .= "                                        if (e) {\n";
        $html .= "                                            v = e.value;\n";
        $html .= "                                            if (isNaN(parseFloat(v))) {\n";
        $html .= "                                                v = null;\n";
        $html .= "                                            }\n";
        $html .= "                                        }\n";
        $html .= "                                    } else if (a.dataType === 'list') {\n";
        $html .= "                                        var e = document.getElementById('cds-dv-' + i);\n";
        $html .= "                                        if (e) {\n";
        $html .= "                                            v = e.options[e.selectedIndex].value;\n";
        $html .= "                                            if (v !== null && v.toLowerCase() === 'none') {\n";
        $html .= "                                                v = null;\n";
        $html .= "                                            }\n";
        $html .= "                                        }\n";
        $html .= "                                    } else if (a.dataType === 'multilist') {\n";
        $html .= "                                        v = '';\n";
        $html .= "                                        for (var j = 0; j < 100; j++) {\n";
        $html .= "                                            e = document.getElementById('cds-dv-' + i + '-' + j);\n";
        $html .= "                                            if (e) {\n";
        $html .= "                                                if (e.checked) {\n";
        $html .= "                                                    if (v.length) {\n";
        $html .= "                                                        v += ',';\n";
        $html .= "                                                    }\n";
        $html .= "                                                    v += e.value;\n";
        $html .= "                                                }\n";
        $html .= "                                            } else {\n";
        $html .= "                                                break;\n";
        $html .= "                                            }\n";
        $html .= "                                        }\n";
        $html .= "                                        if (!v.length) {\n";
        $html .= "                                            v = null;\n";
        $html .= "                                        }\n";
        $html .= "                                    } else if (a.dataType === 'text') {\n";
        $html .= "                                        var e = document.getElementById('cds-dv-' + i);\n";
        $html .= "                                        if (e) {\n";
        $html .= "                                            v = e.value;\n";
        $html .= "                                            if (v && !v.length) {\n";
        $html .= "                                                v = null;\n";
        $html .= "                                            }\n";
        $html .= "                                        }\n";
        $html .= "                                    }\n";
        $html .= "                                    if (v) {\n";
        $html .= "                                        if (first) {\n";
        $html .= "                                            pdesc += '\\r\\n';\n";
        $html .= "                                            first = false;\n";
        $html .= "                                        }\n";
        $html .= "                                        pdesc += '\\r\\n' + a.label + ': ' + v;\n";
        $html .= "                                    }\n";
        $html .= "                                }\n";

        if (isset($cds->product['imageURL'])) {
            $html .= "                                pimg = ".json_encode($cds->product['imageURL']).";\n";
        } else {
            $html .= "                                pimg = null;\n";
        }
        $html .= "                                purl = location.href;\n";
        $html .= "                                cds.cart.addProduct(pid, plabel, pdesc, q, purl, pimg, true, '".$cds->getCatalogURL("?page=cart")."');\n";
        $html .= "                            } else {\n";
        $html .= "                                alert('Please specify a valid quantity.');\n";
        $html .= "                            }\n";
        $html .= "                        });\n";
        $html .= "                        jQuery('#cds-add-to-cart-quantity').focus(function () { this.select(); });\n";
        $html .= "                    </script>\n";
    }

    if ($cds->enableEmbeddedViewer) {
        $html .= "                    <link href='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/default.css' rel='stylesheet' type='text/css'>\n";
        $html .= "                    <link href='//".htmlspecialchars($cds->host)."/catalog3/css/cds-viewer.css' rel='stylesheet' type='text/css'>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/cds-viewer.js'></script>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/threejs/three.min.js'></script>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/threejs/libs/Detector.js'></script>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/threejs/libs/THREEx.FullScreen.js'></script>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/threejs/loaders/MTLLoader.js'></script>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/threejs/loaders/OBJMTLLoader.js'></script>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/threejs/controls/TrackballControls.js'></script>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/threejs/postprocessing/RenderPass.js'></script>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/threejs/postprocessing/ShaderPass.js'></script>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/threejs/postprocessing/MaskPass.js'></script>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/threejs/postprocessing/EffectComposer.js'></script>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/threejs/shaders/EdgeShader.js'></script>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/threejs/shaders/CopyShader.js'></script>\n";
        $html .= "                    <script src='//".htmlspecialchars($cds->host)."/catalog3/js/viewer/myedgeshelper.js'></script>\n";
        $html .= "                    <script>\n";
        $html .= "                        cds.CADRequester.objViewerElementId = 'cds-product-image-container';\n";
        $html .= "                    </script>\n";
    }
    $html .= "                    <div class='cds-product-cad-container' id='cds-product-cad-container'>\n";
    $html .= "                        <button id='cds-cad-download-button' type='button'>Download CAD</button>\n";
    $html .= "                        <select id='cds-cad-download-formats'>\n";
    $html .= "                            <option value='none' selected='selected'>Choose a CAD format</option>\n";
    $html .= "                        </select>\n";
    $html .= "                        <br>\n";
    $html .= "                        <button id='cds-cad-view-3D-button' type='button'>View 3D Model</button>\n";
    $html .= "                        <a id='cds-product-cad-view-disclaimer' target='cds-help'\n";
    $html .= "                                href='//www.product-config.net/catalog3/help/3dviewerhelp.html'>\n";
    $html .= "                            Adobe 3D PDF help\n";
    $html .= "                        </a>\n";
    $html .= "                        <button id='cds-cad-view-2D-button'>View 2D Model</button>\n";
    $html .= "                    </div>\n";
    $html .= "                    <script>\n";
    $html .= "                        cds.CADRequester.setProduct(".json_encode($cds->product['id']).");\n";
    $html .= "                        cds.CADRequester.setContainerElementId('cds-product-cad-container');\n";
    $html .= "                        cds.CADRequester.setFormatSelectElementId('cds-cad-download-formats');\n";
    $html .= "                        cds.CADRequester.setDownloadButtonElementId('cds-cad-download-button');\n";
    $html .= "                        cds.CADRequester.setView2DButtonElementId('cds-cad-view-2D-button');\n";
    $html .= "                        cds.CADRequester.setView3DButtonElementId('cds-cad-view-3D-button');\n";
    $html .= "                        cds.CADRequester.load();\n";
    $html .= "                    </script>\n";
    if ($cds->enableSpecSheet) {
        $html .= "                    <div class='cds-product-spec-sheet'>\n";
        $html .= "                        <button id='cds-product-spec-sheet-submit'>View Spec Sheet</button>\n";
        $html .= "                        <script>\n";
        $html .= "                            cds.specSheet.unit = ".json_encode($cds->unitSystem).";\n";
        $html .= "                            cds.specSheet.params = {id: ".json_encode($cds->product['id']).", cid: ".json_encode($cds->category['id'])."};\n";
        $html .= "                            cds.specSheet.load('cds-product-spec-sheet-submit');\n";
        $html .= "                        </script>\n";
        $html .= "                    </div>\n";
    }
    $html .= "                </div>\n";
    $html .= "            </div>\n";

    if (sizeof($imageAttributes) > 0) {
        $html .= "                <div class='cds-product-additional-images'>\n";
        foreach ($imageAttributes as $i => $a) {
            $html .= "                    <a href='' onclick='return onClickExpandImage(\"".htmlspecialchars($attributeValues[$a['id']])."\")'>\n";
            $html .= "                        <img src='".htmlspecialchars($attributeValues[$a['id']])."'\n";
            $html .= "                                alt='".htmlspecialchars($a['label'])."'\n";
            $html .= "                                title='".htmlspecialchars($a['label'])."' />\n";
            $html .= "                    </a>\n";
        }
        $html .= "                </div>\n";
        $html .= "                <p class='cds-product-additional-images-label'>Line Drawing - Click to Enlarge</p>\n";
        $html .= "                <div id='cds-product-additional-images-expanded' style='display: none'>\n";
        $html .= "                    <img id='cds-product-additional-images-expanded-img' src='' />\n";
        $html .= "                </div>\n";
        $html .= "                <script>\n";
        $html .= "                    var expandedSchematic = null;\n";
        $html .= "                    function onClickExpandImage(src) {\n";
        $html .= "                        'use strict';\n";
        $html .= "                        if (expandedSchematic && expandedSchematic === src) {\n";
        $html .= "                            document.getElementById('cds-product-additional-images-expanded-img').setAttribute('src', '');\n";
        $html .= "                            document.getElementById('cds-product-additional-images-expanded').style.display = 'none';\n";
        $html .= "                            expandedSchematic = null;\n";
        $html .= "                        } else {\n";
        $html .= "                            document.getElementById('cds-product-additional-images-expanded-img').setAttribute('src', src);\n";
        $html .= "                            document.getElementById('cds-product-additional-images-expanded').style.display = '';\n";
        $html .= "                            expandedSchematic = src;\n";
        $html .= "                        }\n";
        $html .= "                        return false;\n";
        $html .= "                    }\n";
        $html .= "                </script>\n";
    }

    $html .= "            <h3>\n";
    $html .= "                Product Details\n";
    if ($cds->enableUnitToggle) {
        $html .= "                <div id='cds-unit-toggle-container'></div>\n";
        $html .= "                <script>\n";
        $html .= "                    cds.addUnitSystemToggle('cds-unit-toggle-container', '".$cds->unitSystem."');\n";
        $html .= "                </script>\n";
    }
    $html .= "            </h3>\n";

        $html .= "            <div class='cds-product-details'>\n";
    if (isset($cds->product['longDescription'])) {
        $html .= "                <p>".$cds->product['longDescription']."</p>\n";
    }
        $html .= "                <div class='cds-product-details-left'>\n";

    if (sizeof($configurableAttributes) > 0) {
        $html .= "                    <table id='cds-product-dynamic-attribute-table' class='cds-product-details-container cds-attribute-table'>\n";
        $html .= "                        <thead>\n";
        $html .= "                            <tr>\n";
        $html .= "                                <td colspan='2' id='cds-product-dynamic-attribute-table-label'>Configuration</td>\n";
        $html .= "                            </tr>\n";
        $html .= "                        </thead>\n";
        $html .= "                        <tbody>\n";
        foreach ($configurableAttributes as $i => $a) {
            $l = $a['label'];
            if (isset($a['persistedUnit'])) {
                $l .= ' ('.$a['persistedUnit'].')';
            }
            if ($a['dataType'] === 'range') {
                $l .= ' ('.number_format($attributeValues[$a['id']][0], $a['precision']).' to '.number_format($attributeValues[$a['id']][1], $a['precision']).')';
            }
            $html .= "                            <tr class='".($i % 2 !== 0 ? 'cds-odd' : 'cds-even')."'>\n";
            $html .= "                                <td id='cds-al-".htmlspecialchars($a['id'])."' class='label'>".$l."</td>\n";
            $html .= "                                <td id='cds-av-".htmlspecialchars($a['id'])."'>\n";
            if ($a['dataType'] === 'multilist') {
                $html .= "                                    <ul id='cds-dv-".htmlspecialchars($a['id'])."' class='cds-attribute-multilist'>\n";
                foreach ($attributeValues[$a['id']] as $j => $v) {
                    $html .= "                                        <li><input type='checkbox'\n";
                    $html .= "                                                    value='".htmlspecialchars($v)."'\n";
                    $html .= "                                                    name='cds-dv-".htmlspecialchars($a['id'])."'\n";
                    $html .= "                                                    onchange='cds.handleChangeDynamicAttribute(".json_encode($a['id']).")'\n";
                    $html .= "                                                    id='cds-dv-".htmlspecialchars($a['id'])."-".$j."' />\n";
                    $html .= "                                            <label for='cds-dv-".htmlspecialchars($a['id'])."-".$j."'>".$v."</label>\n";
                    $html .= "                                        </li>\n";
                }
                $html .= "                                    </ul>\n";
            } elseif ($a['dataType'] === 'list') {
                $v = $attributeValues[$a['id']];
                $defaultOption = 0;
                $optionLength = sizeof($v);
                for ($j = 0; $j < sizeof($v) - 1; $j++) {
                    if ($v[$j] === $v[sizeof($v) - 1]) {
                        $defaultOption = $j;
                        $optionLength -= 1;
                        break;
                    }
                }
                $html .= "                                    <select id='cds-dv-".htmlspecialchars($a['id'])."' onchange='cds.handleChangeDynamicAttribute(".json_encode($a['id']).")'>\n";
                for ($j = 0; $j < $optionLength; $j++) {
                    $html .= "                                        <option value='".htmlspecialchars($v[$j])."'\n";
                    if ($j === $defaultOption) {
                        $html .= "                                                selected='selected'\n";
                    }
                    $html .= "                                                >".$v[$j]."</option>\n";
                }
                $html .= "                                    </select>\n";
            } elseif ($a['dataType'] === 'range') {
                $v = '';
                if (sizeof($attributeValues[$a['id']]) > 2) {
                    $v = number_format($attributeValues[$a['id']][2], $a['precision']);
                }
                $html .= "                                    <input id='cds-dv-".htmlspecialchars($a['id'])."'\n";
                $html .= "                                            onchange='cds.handleChangeDynamicAttribute(".json_encode($a['id']).")'\n";
                $html .= "                                            onfocus='select()' size='10' value='".htmlspecialchars($v)."' type='text' />\n";
                $html .= "                                    <button>Set</button>\n";
            } elseif ($a['dataType'] === 'text') {
                $v = $attributeValues[$a['id']];
                $html .= "                                    <input id='cds-dv-".htmlspecialchars($a['id'])."'\n";
                $html .= "                                            onchange='cds.handleChangeDynamicAttribute(".json_encode($a['id']).")'\n";
                $html .= "                                            onfocus='select()' size='10' value='".htmlspecialchars($v)."' type='text' />\n";
                $html .= "                                    <button>Set</button>\n";
            }
            $html .= "                                </td>\n";
            $html .= "                            </tr>\n";
        }
        $html .= "                        </tbody>\n";
        $html .= "                    </table>\n";
        $html .= "                    <script>\n";
        $html .= "                        cds.handleChangeDynamicAttribute = function (id) {\n";
        $html .= "                            var i, e, v, min, max, a = cds.productAttributes[id];\n";
        $html .= "                            v = null;\n";
        $html .= "                            if (a) {\n";
        $html .= "                                if (a.dataType === 'range') {\n";
        $html .= "                                    min = a.value && parseFloat(a.value[0]);\n";
        $html .= "                                    max = a.value && parseFloat(a.value[1]);\n";
        $html .= "                                    if (min && max) {\n";
        $html .= "                                        e = document.getElementById('cds-dv-' + id);\n";
        $html .= "                                        if (e) {\n";
        $html .= "                                            v = parseFloat(e.value);\n";
        $html .= "                                            if (isNaN(v) || v < min || v > max) {\n";
        $html .= "                                                alert('Please enter a value between ' + min + ' and ' + max + '.');\n";
        $html .= "                                                // can't use a.value.default on IE9 compat view for some reason\n";
        $html .= "                                                v = a['value']['default'] || '';\n";
        $html .= "                                                e.value = v;\n";
        $html .= "                                                e.focus();\n";
        $html .= "                                            } else {\n";
        $html .= "                                                v = (Math.round(v * Math.pow(10, a.precision)) /\n";
        $html .= "                                                        Math.pow(10, a.precision)).toFixed(a.precision);\n";
        $html .= "                                                e.value = v;\n";
        $html .= "                                            }\n";
        $html .= "                                            if (a.cadParameterName) {\n";
        $html .= "                                                cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);\n";
        $html .= "                                            }\n";
        $html .= "                                            cds.cart.addCustomAttribute(a.label, v);\n";
        $html .= "                                        }\n";
        $html .= "                                    }\n";
        $html .= "                                } else if (a.dataType === 'list') {\n";
        $html .= "                                    e = document.getElementById('cds-dv-' + id);\n";
        $html .= "                                    if (e) {\n";
        $html .= "                                        v = e.options[e.selectedIndex].value;\n";
        $html .= "                                        if (v) {\n";
        $html .= "                                            if (a.cadParameterName) {\n";
        $html .= "                                                cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);\n";
        $html .= "                                            }\n";
        $html .= "                                            cds.cart.addCustomAttribute(a.label, v);\n";
        $html .= "                                        }\n";
        $html .= "                                    }\n";
        $html .= "                                } else if (a.dataType === 'multilist') {\n";
        $html .= "                                    v = '';\n";
        $html .= "                                    for (i = 0; i < 100; i++) {\n";
        $html .= "                                        e = document.getElementById('cds-dv-' + id + '-' + i);\n";
        $html .= "                                        if (e) {\n";
        $html .= "                                            if (e.checked) {\n";
        $html .= "                                                if (v.length) {\n";
        $html .= "                                                    v += ',';\n";
        $html .= "                                                }\n";
        $html .= "                                                v += e.value;\n";
        $html .= "                                            }\n";
        $html .= "                                        } else {\n";
        $html .= "                                            break;\n";
        $html .= "                                        }\n";
        $html .= "                                    }\n";
        $html .= "                                    if (v.length) {\n";
        $html .= "                                        if (a.cadParameterName) {\n";
        $html .= "                                            cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);\n";
        $html .= "                                        }\n";
        $html .= "                                        cds.cart.addCustomAttribute(a.label, v);\n";
        $html .= "                                    }\n";
        $html .= "                                } else if (a.dataType === 'text') {\n";
        $html .= "                                    e = document.getElementById('cds-dv-' + id);\n";
        $html .= "                                    if (e) {\n";
        $html .= "                                        v = e.value;\n";
        $html .= "                                        if (v) {\n";
        $html .= "                                            if (a.cadParameterName) {\n";
        $html .= "                                                cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);\n";
        $html .= "                                            }\n";
        $html .= "                                            cds.cart.addCustomAttribute(a.label, v);\n";
        $html .= "                                        }\n";
        $html .= "                                    }\n";
        $html .= "                                }\n";
        $html .= "                                if (typeof cdsHandleChangeDynamicAttribute === 'function') {\n";
        $html .= "                                    cdsHandleChangeDynamicAttribute(id, a, v);\n";
        $html .= "                                }\n";
        $html .= "                            }\n";
        $html .= "                            _cdsSetCustomProductLabel();\n";
        $html .= "                        }\n";
        $html .= "                    </script>\n";
    }

    if (sizeof($attributes) > 0) {
        $html .= "                    <table class='cds-product-details-container cds-attribute-table' id='cds-product-attribute-table'>\n";
        $html .= "                        <tbody>\n";
        foreach ($attributes as $i => $a) {
            $l = $a['label'];
            if (isset($a['persistedUnit'])) {
                $u = $a['persistedUnit'];
                if (isset($a['metricDefaultUnit']) && $cds->unitSystem === 'metric') {
                    $u = $a['metricDefaultUnit'];
                } elseif (isset($a['englishDefaultUnit']) && $cds->unitSystem === 'english') {
                    $u = $a['englishDefaultUnit'];
                }
                $l .= ' ('.$u.')';
            }
            $html .= "                            <tr class='".($i % 2 !== 0 ? 'cds-odd' : 'cds-even')."'>\n";
            $html .= "                                <td id='cds-al-".htmlspecialchars($a['id'])."'>".$l."</td>\n";
            if ($a['dataType'] === 'fraction') {
                $html .= "                                <td id='cds-av-".htmlspecialchars($a['id'])."'>".CDSWebService::toFraction($attributeValues[$a['id']])."</td>\n";
            } else {
                $html .= "                                <td id='cds-av-".htmlspecialchars($a['id'])."'>".$attributeValues[$a['id']]."</td>\n";
            }
            $html .= "                            </tr>\n";
        }
        $html .= "                        </tbody>\n";
        $html .= "                    </table>\n";
    }
    $html .= "                </div> <!-- cds-product-details-left -->\n";
    $html .= "                <div class='cds-product-details-right'>\n";
    if (sizeof($attachments) > 0) {
        $html .= "                    <table class='cds-product-details-container cds-attribute-table' id='cds-product-attachment-table'>\n";
        $html .= "                        <thead>\n";
        $html .= "                            <tr><td colspan='2'>Documentation</td></tr>\n";
        $html .= "                        </thead>\n";
        $html .= "                        <tbody>\n";
        foreach ($attachments as $i => $a) {
            $v = $attributeValues[$a['id']];
            $pos = strripos($v, '/');
            if ($pos !== FALSE) {
                $v = substr($v, $pos + 1);
            }
            if (strlen($v) > 37) {
                $v = substr($v, 0, 37).'...';
            }
            $html .= "                            <tr class='".($i % 2 !== 0 ? 'cds-odd' : 'cds-even')."'>\n";
            $html .= "                                <td id='cds-al-".htmlspecialchars($a['id'])."'>".$a['label']."</td>\n";
            $html .= "                                <td id='cds-av-".htmlspecialchars($a['id'])."'>\n";
            $html .= "                                    <a href='".$attributeValues[$a['id']]."' target='cds-catalog-attachment'>\n";
            if (isset($a['imageURL'])) {
                $html .= "                                        <img src='".$a['imageURL']."'>\n";
            } else {
                $html .= "                                        $v\n";
            }
            $html .= "                                    </a>\n";
            $html .= "                                </td>\n";
            $html .= "                            </tr>\n";
        }
        $html .= "                        </tbody>\n";
        $html .= "                    </table>\n";
    }
    $html .= "                </div> <!-- cds-product-details-right -->\n";
    $html .= "            </div>\n";
    $html .= "            <script>\n";
    $html .= "                function _cdsHandleChangeDynamicAttribute(id, doNotCallCustom) {\n";
    $html .= "                    var i, e, v, min, max, a = cds.productAttributes[id];\n";
    $html .= "                    if (a) {\n";
    $html .= "                        if (a.dataType === 'range') {\n";
    $html .= "                            min = a.value && a.value.min;\n";
    $html .= "                            max = a.value && a.value.max;\n";
    $html .= "                            if (min && max) {\n";
    $html .= "                                e = document.getElementById('cds-dv-' + id);\n";
    $html .= "                                if (e) {\n";
    $html .= "                                    v = parseFloat(e.value);\n";
    $html .= "                                    if (isNaN(v) || v < min || v > max) {\n";
    $html .= "                                        alert('Please enter a value between ' + min + ' and ' + max + '.');\n";
    $html .= "                                        // can't use a.value.default on IE9 compat view for some reason\n";
    $html .= "                                        v = a['value']['default'] || '';\n";
    $html .= "                                        e.value = v;\n";
    $html .= "                                        e.focus();\n";
    $html .= "                                    } else {\n";
    $html .= "                                        v = (Math.round(v * Math.pow(10, a.precision)) / Math.pow(10, a.precision))\n";
    $html .= "                                                .toFixed(a.precision);\n";
    $html .= "                                        e.value = v;\n";
    $html .= "                                    }\n";
    $html .= "                                    if (a.cadParameterName) {\n";
    $html .= "                                        cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);\n";
    $html .= "                                    }\n";
    $html .= "                                    if (true) {\n";
    $html .= "                                        cds.cart.addCustomAttribute(a.label, v);\n";
    $html .= "                                    }\n";
    $html .= "                                }\n";
    $html .= "                            }\n";
    $html .= "                        } else if (a.dataType === 'list') {\n";
    $html .= "                            e = document.getElementById('cds-dv-' + id);\n";
    $html .= "                            if (e) {\n";
    $html .= "                                v = e.options[e.selectedIndex].value;\n";
    $html .= "                                if (v) {\n";
    $html .= "                                    if (a.cadParameterName) {\n";
    $html .= "                                        cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);\n";
    $html .= "                                    }\n";
    $html .= "                                    if (true) {\n";
    $html .= "                                        cds.cart.addCustomAttribute(a.label, v);\n";
    $html .= "                                    }\n";
    $html .= "                                }\n";
    $html .= "                            }\n";
    $html .= "                        } else if (a.dataType === 'multilist') {\n";
    $html .= "                            v = '';\n";
    $html .= "                            for (i = 0; i < 100; i++) {\n";
    $html .= "                                e = document.getElementById('cds-dv-' + id + '-' + i);\n";
    $html .= "                                if (e) {\n";
    $html .= "                                    if (e.checked) {\n";
    $html .= "                                        if (v.length) {\n";
    $html .= "                                            v += ',';\n";
    $html .= "                                        }\n";
    $html .= "                                        v += e.value;\n";
    $html .= "                                    }\n";
    $html .= "                                } else {\n";
    $html .= "                                    break;\n";
    $html .= "                                }\n";
    $html .= "                            }\n";
    $html .= "                            if (v.length) {\n";
    $html .= "                                if (a.cadParameterName) {\n";
    $html .= "                                    cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);\n";
    $html .= "                                }\n";
    $html .= "                                if (true) {\n";
    $html .= "                                    cds.cart.addCustomAttribute(a.label, v);\n";
    $html .= "                                }\n";
    $html .= "                            }\n";
    $html .= "                        } else if (a.dataType === 'text') {\n";
    $html .= "                            e = document.getElementById('cds-dv-' + id);\n";
    $html .= "                            if (e) {\n";
    $html .= "                                v = e.value;\n";
    $html .= "                                if (v) {\n";
    $html .= "                                    if (a.cadParameterName) {\n";
    $html .= "                                        cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);\n";
    $html .= "                                    }\n";
    $html .= "                                    if (true) {\n";
    $html .= "                                        cds.cart.addCustomAttribute(a.label, v);\n";
    $html .= "                                    }\n";
    $html .= "                                }\n";
    $html .= "                            }\n";
    $html .= "                        }\n";
    $html .= "                        if (typeof cdsHandleChangeDynamicAttribute === 'function'\n";
    $html .= "                                && !doNotCallCustom) {\n";
    $html .= "                            cdsHandleChangeDynamicAttribute(id, a, v);\n";
    $html .= "                        }\n";
    $html .= "                        _cdsSetCustomProductLabel();\n";
    $html .= "                    }\n";
    $html .= "                }\n";
    $html .= "                // run once to make sure we deal with initial dynamic attributes\n";
    $html .= "                if (typeof cdsHandleChangeDynamicAttribute === 'function') {\n";
    $html .= "                    cdsHandleChangeDynamicAttribute();\n";
    $html .= "                }\n";
    $html .= "            </script>\n";
    $html .= "        </div>\n";

    return $html;
}
?>
