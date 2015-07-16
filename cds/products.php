<?php
    $product = null;
    if (isset($_REQUEST['id'])) {
        $product = $_REQUEST['id'];
    }
    $category = null;
    if (isset($_REQUEST['cid'])) {
        $category = $_REQUEST['cid'];
    }
    $unitSystem = null;
    if (isset($_REQUEST['unit'])) {
        $unitSystem = $_REQUEST['unit'];
    } elseif (isset($_COOKIE["cds.catalog.unit"])) {
        $unitSystem = $_COOKIE["cds.catalog.unit"];
    }

    $cds_service = new CDSWebService($host, $unitSystem);
    $product_info = $cds_service->sendProductRequest($domain, $product, $error, $category);
    if ($error !== false) {
        header('Location: catalog-error.html');
        return;
    }

    $category_info = $product_info['category'];
    $useRFQCart = true;

    // seperate attribute into different sections
    $attributes = array();
    $configurableAttributes = array();
    $attachments = array();
    $imageAttributes = array();
    $htmlAttributes = array();
    $footnotes = array();
    $notices = array();
    $attributeValues = array();
?>
            <script>
                cds.setDomain(<?php echo json_encode($domain) ?>);
                cds.catalogCommand = "products";
                cds.setRemoteServerBaseURL(<?php echo json_encode("http://$host/catalog3") ?>);
                cds.productAttributes = {
<?php
    foreach ($product_info['attributes'] as $i => $a) {
        if ($a['visible'] !== false && isset($product_info['attributeValues'][$i])) {
            $type = $a['dataType'];
            if ($type === "list" || $type === "range" || $type === "multilist") {
                $configurableAttributes[] = $a;
                $attributeValues[$a['id']] = explode('|', $product_info['attributeValues'][$i]);
            } elseif ($type === "attachment") {
                $attachments[] = $a;
                $attributeValues[$a['id']] = $product_info['attributeValues'][$i];
            } elseif ($type === "image") {
                $imageAttributes[] = $a;
                $attributeValues[$a['id']] = $product_info['attributeValues'][$i];
            } elseif ($type === "html") {
                $htmlAttributes[] = $a;
                $attributeValues[$a['id']] = $product_info['attributeValues'][$i];
            } elseif ($type === "footnote") {
                $footnotes[] = $a;
                $attributeValues[$a['id']] = $product_info['attributeValues'][$i];
            } elseif ($type === "notice") {
                $notices[] = $a;
                $attributeValues[$a['id']] = $product_info['attributeValues'][$i];
            } else {
                $attributes[] = $a;
                $attributeValues[$a['id']] = $product_info['attributeValues'][$i];
            }

            $s = "{\"dataType\":".json_encode($a['dataType']);
            if (isset($a['label']) && strlen($a['label']) > 0) { $s .= ",\"label\":".json_encode($a['label']); }
            if (isset($a['toolTip']) && strlen($a['toolTip']) > 0) { $s .= ",\"toolTip\":".json_encode($a['toolTip']); }
            if (isset($a['imageURL']) && strlen($a['imageURL']) > 0) { $s .= ",\"imageURL\":".json_encode($a['imageURL']); }
            if (isset($a['precision'])) { $s .= ",\"precision\":".json_encode($a['precision']); }
            if (isset($a['step'])) { $s .= ",\"step\":".json_encode($a['step']); }
            if (isset($a['persistedUnit']) && strlen($a['persistedUnit']) > 0) { $s .= ",\"unit\":".json_encode($a['persistedUnit']); }
            if ($a['searchable']) { $s .= ",\"searchable\":true"; }
            if ($a['visible']) { $s .= ",\"visible\":true"; }
            if ($a['multiValue']) { $s .= ",\"multiValue\":true"; }
            if ($a['rangeSearchable']) { $s .= ",\"rangeSearchable\":true"; }
            if ($a['selectLTE']) { $s .= ",\"selectLTE\":true"; }
            if ($a['selectGTE']) { $s .= ",\"selectGTE\":true"; }
            if (isset($a['sortOrder'])) { $s .= ",\"sortOrder\":".json_encode($a['sortOrder']); }
            if (isset($a['cadDataType']) && strlen($a['cadDataType']) > 0) { $s .= ",\"cadDataType\":".json_encode($a['cadDataType']); }
            if (isset($a['cadParameterName']) && strlen($a['cadParameterName']) > 0) { $s .= ",\"cadParameterName\":".json_encode($a['cadParameterName']); }
            if ($a['dataType'] === "list" || $a['dataType'] === "range" || $a['dataType'] === "multilist") {
                $s .= ",\"value\":[";
                foreach ($product_info['attributeValues'][$i] as $j => $v) {
                    if ($j > 0) {
                        $s .= ",";
                    }
                    $s .= json_encode($v);
                }
                $s .= "]";
            } else {
                $s .= ",\"value\":".json_encode($attributeValues[$a['id']]);
            }
            $s .= "}";
            if ($i < sizeof($product_info['attributes']) - 1) {
                $s .= ",";
            }
            $s .= "\n";
?>
                    <?php echo json_encode($a['id']) ?>:<?php echo $s ?>
<?php
        }
    }
?>
                };
            </script>
            <div id="cds-content" class="cds-content cds-product-page">
                <div class="cds-product-icons">
                    <a href="javascript:window.print();"><img src="http://<?php echo htmlspecialchars($host) ?>/catalog3/images/print_page.png" /></a>
                    <a href="javascript:cds.emailPage()"><img src="http://<?php echo htmlspecialchars($host) ?>/catalog3/images/email_go.png" /></a>
                </div>
                <ul class="cds-crumbs">
<?php   foreach ($category_info['crumbs'] as $c) { ?>
<?php       if ($c['id'] === 'root') { ?>
                    <li><a href="?page=search">Home</a></li>
                    <li><span>&gt;</span></li>
<?php       } else { ?>
                    <li><a href="?page=search&cid=<?php echo urlencode($c['id']) ?>"><?php echo $c['label'] ?></a></li>
                    <li><span>&gt;</span></li>
<?php       } ?>
<?php   } ?>
                    <li><a href="?page=search&cid=<?php echo urlencode($category_info['id']) ?>"><?php echo $category_info['label'] ?></a></li>
                    <li><span>&gt;</span></li>
                    <li><?php echo $product_info['label'] ?></li>
                </ul>

<?php   if (isset($product_info['headerHTML'])) { ?>
                <div class="cds-product-custom-header"><?php echo $product_info['headerHTML'] ?></div>
<?php   } ?>
                <div class="cds-product-header">
<?php   if (isset($product_info['imageURL'])) { ?>
                    <div class="cds-product-image-container">
                        <div class="cds-product-image" id="cds-product-image">
                            <img src="<?php echo htmlspecialchars($product_info['imageURL']) ?>"
<?php       if (isset($product_info['imageAlt'])) { ?>
                                alt="<?php echo htmlspecialchars($product_info['imageAlt']) ?>"
<?php       } ?>
<?php       if (isset($product_info['imageTitle'])) { ?>
                                title="<?php echo htmlspecialchars($product_info['imageTitle']) ?>"
<?php       } ?>
                                />
                        </div>
                    </div>
<?php   } ?>
                    <div class="cds-product-controls">
<?php   if (isset($product_info['description'])) { ?>
                        <h1><?php echo $product_info['description'] ?></h1>
                        <h2>Model: <span id="cds-product-number"><?php echo $product_info['label'] ?></span></h2>
<?php   } else { ?>
                        <h1><?php echo $product_info['label'] ?></h1>
                        <h2>Category: <span id="cds-product-number"><?php echo $category_info['label'] ?></span></h2>
<?php   } ?>
<?php   if ($useRFQCart) { ?>
                        <div class="cds-product-cart">
                            <table>
                                <tbody>
                                    <tr>
                                        <td id="cds-product-price">Request Quote</td>
                                        <td>Quantity
                                            <input type="text" size="3" value="1" id="cds-add-to-cart-quantity" />
                                        </td>
                                        <td><button id="cds-add-to-cart-button">Add to Cart</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <script>
                            jQuery("#cds-add-to-cart-button").on("click", function () {
                                "use strict";
                                var pid, plabel, pdesc, pimg, purl, i, a, v, e, first,
                                    q = jQuery("#cds-add-to-cart-quantity").val();

                                if (parseInt(q) > 0) {
                                    pid = <?php echo json_encode($product_info['id']) ?>;
                                    plabel = <?php echo json_encode($product_info['label']) ?>;
<?php       if (isset($product_info['description'])) { ?>
                                    pdesc = <?php echo json_encode($product_info['description']) ?>;
<?php       } else { ?>
                                    pdesc = <?php echo json_encode($category_info['label']) ?>;
<?php       } ?>
                                    var first = true;
                                    for (i in cds.productAttributes) {
                                        a = cds.productAttributes[i];
                                        if (a.dataType === "range") {
                                            e = document.getElementById("cds-dv-" + i);
                                            if (e) {
                                                v = e.value;
                                                if (isNaN(parseFloat(v))) {
                                                    v = null;
                                                }
                                            }
                                        } else if (a.dataType === "list") {
                                            var e = document.getElementById("cds-dv-" + i);
                                            if (e) {
                                                v = e.options[e.selectedIndex].value;
                                                if (v !== null && v.toLowerCase() === "none") {
                                                    v = null;
                                                }
                                            }
                                        } else if (a.dataType === "multilist") {
                                            v = "";
                                            for (var j = 0; j < 100; j++) {
                                                e = document.getElementById("cds-dv-" + i + "-" + j);
                                                if (e) {
                                                    if (e.checked) {
                                                        if (v.length) {
                                                            v += ",";
                                                        }
                                                        v += e.value;
                                                    }
                                                } else {
                                                    break;
                                                }
                                            }
                                            if (!v.length) {
                                                v = null;
                                            }
                                        } else if (a.dataType === "text") {
                                            var e = document.getElementById("cds-dv-" + i);
                                            if (e) {
                                                v = e.value;
                                                if (v && !v.length) {
                                                    v = null;
                                                }
                                            }
                                        }

                                        if (v) {
                                            if (first) {
                                                pdesc += "\r\n";
                                                first = false;
                                            }
                                            pdesc += "\r\n" + a.label + ": " + v;
                                        }
                                    }

<?php       if (isset($product_info['imageURL'])) { ?>
                                    pimg = <?php echo json_encode($product_info['imageURL']) ?>;
<?php       } else { ?>
                                    pimg = null;
<?php       } ?>
                                    purl = location.href;
                                    cds.cart.addProduct(pid, plabel, pdesc, q, purl, pimg, true, "?page=cart");
                                } else {
                                    alert('Please specify a valid quantity.');
                                }
                            });
                            jQuery("#cds-add-to-cart-quantity").focus(function () { this.select(); });
                        </script>
<?php   } ?>

                        <div class="cds-product-cad-container" id="cds-product-cad-container">
                            <button id="cds-cad-download-button" type="button">Download CAD</button>
                            <select id="cds-cad-download-formats">
                                <option value="none" selected="selected">Choose a CAD format</option>
                            </select>
                            <br>
                            <button id="cds-cad-view-3D-button" type="button">View 3D Model</button>
                            <a id="cds-product-cad-view-disclaimer" target="cds-help"
                                    href="http://www.product-config.net/catalog3/help/3dviewerhelp.html">
                                Adobe 3D PDF help
                            </a>
                            <button id="cds-cad-view-2D-button">View 2D Model</button>
                        </div>
                        <script>
                            cds.CADRequester.setProduct(<?php echo json_encode($product_info['id']) ?>);
                            cds.CADRequester.setContainerElementId("cds-product-cad-container");
                            cds.CADRequester.setFormatSelectElementId("cds-cad-download-formats");
                            cds.CADRequester.setDownloadButtonElementId("cds-cad-download-button");
                            cds.CADRequester.setView2DButtonElementId("cds-cad-view-2D-button");
                            cds.CADRequester.setView3DButtonElementId("cds-cad-view-3D-button");
                            cds.CADRequester.load();
                        </script>
<?php   /* ?>
                        <div class="cds-product-spec-sheet">
                            <button id="cds-product-spec-sheet-submit">View Spec Sheet</button>
                            <script>
                                cds.specSheet.params = {id: <?php echo json_encode($product_info['id']) ?>, cid: <?php echo json_encode($category_info['id']) ?>};
                                cds.specSheet.load("cds-product-spec-sheet-submit");
                            </script>
                        </div>
<?php   */ ?>
                    </div>
                </div>
<?php   if (sizeof($imageAttributes) > 0) { ?>
                <div class="cds-product-additional-images">
<?php       foreach ($imageAttributes as $i => $a) { ?>
                    <a href="" onclick="return onClickExpandImage('<?php echo htmlspecialchars($attributeValues[$a['id']]) ?>')">
                        <img src="<?php echo htmlspecialchars($attributeValues[$a['id']]) ?>"
                                alt="<?php echo htmlspecialchars($a['label']) ?>"
                                title="<?php echo htmlspecialchars($a['label']) ?>" />
                    </a>
<?php       } ?>
                </div>
                <p class="cds-product-additional-images-label">Line Drawing - Click to Enlarge</p>
                <div id="cds-product-additional-images-expanded" style="display: none">
                    <img id="cds-product-additional-images-expanded-img" src="" />
                </div>
                <script>
                    var expandedSchematic = null;
                    function onClickExpandImage(src) {
                        "use strict";

                        if (expandedSchematic && expandedSchematic === src) {
                            document.getElementById("cds-product-additional-images-expanded-img").setAttribute("src", "");
                            document.getElementById("cds-product-additional-images-expanded").style.display = "none";
                            expandedSchematic = null;
                        } else {
                            document.getElementById("cds-product-additional-images-expanded-img").setAttribute("src", src);
                            document.getElementById("cds-product-additional-images-expanded").style.display = "";
                            expandedSchematic = src;
                        }

                        return false;
                    }
                </script>
<?php   } ?>

                <h3>
                    Product Details
<?php   /* ?>
                    <div id="cds-unit-toggle-container"></div>
                    <script>
                        cds.addUnitSystemToggle("cds-unit-toggle-container", "<?php echo $unitSystem ?>");
                    </script>
<?php   */ ?>
                </h3>
                <div class="cds-product-details">
<?php   if (isset($product_info['longDescription'])) { ?>
                    <p><?php echo $product_info['longDescription'] ?></p>
<?php   } ?>
                    <div class="cds-product-details-left">

<?php   if (sizeof($configurableAttributes) > 0) { ?>
                        <table id="cds-product-dynamic-attribute-table" class="cds-product-details-container cds-attribute-table">
                            <thead>
                                <tr>
                                    <td colspan="2" id="cds-product-dynamic-attribute-table-label">Configuration</td>
                                </tr>
                            </thead>
                            <tbody>
<?php       foreach ($configurableAttributes as $i => $a) { ?>
<?php
                $l = $a['label'];
                if ($a['persistedUnit'] !== null) {
                    $l .= ' ('.$a['persistedUnit'].')';
                }
                if ($a['dataType'] === "range") {
                    $l .= ' ('.number_format($attributeValues[$a['id']][0], $a['precision']).' to '.number_format($attributeValues[$a['id']][1], $a['precision']).')';
                }
?>
                                <tr class="<?php echo ($i % 2 !== 0 ? 'cds-odd' : 'cds-even') ?>">
                                    <td id="cds-al=<?php echo htmlspecialchars($a['id']) ?>" class="label"><?php echo $l ?></td>
                                    <td id="cds-av=<?php echo htmlspecialchars($a['id']) ?>">
<?php           if ($a['dataType'] === "multilist") { ?>
                                        <ul id="cds-dv-<?php echo htmlspecialchars($a['id']) ?>" class="cds-attribute-multilist">
<?php               foreach ($attributeValues[$a['id']] as $j => $v) { ?>
                                            <li><input type="checkbox"
                                                        value="<?php echo htmlspecialchars($v) ?>"
                                                        name="cds-dv-<?php echo htmlspecialchars($a['id']) ?>"
                                                        onchange='cds.handleChangeDynamicAttribute(<?php echo json_encode($a['id']) ?>)'
                                                        id="cds-dv-<?php echo htmlspecialchars($a['id']) ?>-<?php echo $j ?>" />
                                                <label for="cds-dv-<?php echo htmlspecialchars($a['id']) ?>-<?php echo $j ?>"><?php echo $v ?></label>
                                            </li>
<?php               } ?>
                                        </ul>
<?php           } elseif ($a['dataType'] === "list") { ?>
                                        <select id="cds-dv-<?php echo htmlspecialchars($a['id']) ?>" onchange='cds.handleChangeDynamicAttribute(<?php echo json_encode($a['id']) ?>)'>
<?php               for ($j = 0; $j < sizeof($attributeValues[$a['id']]) - 1; $j++) { ?>
                                            <option value="<?php echo htmlspecialchars($attributeValues[$a['id']][$j]) ?>"
<?php                   if ($attributeValues[$a['id']][$j] === $attributeValues[$a['id']][sizeof($attributeValues[$a['id']]) - 1]) { ?>
                                                    selected="selected"
<?php                   } ?>
                                                    ><?php echo $attributeValues[$a['id']][$j] ?></option>
<?php               } ?>
                                        </select>
<?php           } elseif ($a['dataType'] === "range") { ?>
<?php
                    $v = '';
                    if (sizeof($attributeValues[$a['id']]) > 2) {
                        $v = number_format($attributeValues[$a['id']][2], $a['precision']);
                    }
?>
                                        <input id="cds-dv-<?php echo htmlspecialchars($a['id']) ?>"
                                                onchange='cds.handleChangeDynamicAttribute(<?php echo json_encode($a['id']) ?>)'
                                                onfocus="select()" size="10" value="<?php echo htmlspecialchars($v) ?>" type="text" />
                                        <button>Set</button>
<?php           } ?>
                                    </td>
                                </tr>
<?php       } ?>
                            </tbody>
                        </table>
                        <script>
                            cds.handleChangeDynamicAttribute = function (id) {
                                var i, e, v, min, max, a = cds.productAttributes[id];

                                if (a) {
                                    if (a.dataType === "range") {
                                        min = a.value && parseFloat(a.value[0]);
                                        max = a.value && parseFloat(a.value[1]);
                                        if (min && max) {
                                            e = document.getElementById("cds-dv-" + id);
                                            if (e) {
                                                v = parseFloat(e.value);
                                                if (isNaN(v) || v < min || v > max) {
                                                    alert("Please enter a value between " + min + " and " + max + ".");
                                                    // can't use a.value.default on IE9 compat view for some reason
                                                    v = a["value"]["default"] || "";
                                                    e.value = v;
                                                    e.focus();
                                                } else {
                                                    v = (Math.round(v * Math.pow(10, a.precision)) /
                                                            Math.pow(10, a.precision)).toFixed(a.precision);
                                                    e.value = v;
                                                }
                                                if (a.cadParameterName) {
                                                    cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                                                }
                                                cds.cart.addCustomAttribute(a.label, v);
                                            }
                                        }
                                    } else if (a.dataType === "list") {
                                        e = document.getElementById("cds-dv-" + id);
                                        if (e) {
                                            v = e.options[e.selectedIndex].value;
                                            if (v) {
                                                if (a.cadParameterName) {
                                                    cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                                                }
                                                cds.cart.addCustomAttribute(a.label, v);
                                            }
                                        }
                                    } else if (a.dataType === "multilist") {
                                        v = "";
                                        for (i = 0; i < 100; i++) {
                                            e = document.getElementById("cds-dv-" + id + "-" + i);
                                            if (e) {
                                                if (e.checked) {
                                                    if (v.length) {
                                                        v += ",";
                                                    }
                                                    v += e.value;
                                                }
                                            } else {
                                                break;
                                            }
                                        }
                                        if (v.length) {
                                            if (a.cadParameterName) {
                                                cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                                            }
                                            cds.cart.addCustomAttribute(a.label, v);
                                        }
                                    } else if (a.dataType === "text") {
                                        e = document.getElementById("cds-dv-" + id);
                                        if (e) {
                                            v = e.value;
                                            if (v) {
                                                if (a.cadParameterName) {
                                                    cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                                                }
                                                cds.cart.addCustomAttribute(a.label, v);
                                            }
                                        }
                                    }
                                }
                            }
                        </script>
<?php   } ?>

<?php   if (sizeof($attributes) > 0) { ?>
                        <table class="cds-product-details-container cds-attribute-table" id="cds-product-attribute-table">
                            <tbody>
<?php       foreach ($attributes as $i => $a) { ?>
<?php
                $l = $a["label"];
                if ($a["persistedUnit"] !== null) {
                    $u = $a["persistedUnit"];
                    if ($unitSystem === "metric" && $a["metricDefaultUnit"]) {
                        $u = $a["metricDefaultUnit"];
                    } elseif ($unitSystem === "english" && $a["englishDefaultUnit"]) {
                        $u = $a["englishDefaultUnit"];
                    }
                    $l .= " (".$u.")";
                }
?>
                                <tr class="<?php echo ($i % 2 !== 0 ? 'cds-odd' : 'cds-even') ?>">
                                    <td id="cds-al-<?php echo htmlspecialchars($a['id']) ?>"><?php echo $l ?></td>
                                    <td id="cds-av-<?php echo htmlspecialchars($a['id']) ?>"><?php echo $attributeValues[$a['id']] ?></td>
                                </tr>
<?php       } ?>
                            </tbody>
                        </table>
<?php   } ?>
                    </div> <!-- cds-product-details-left -->

                    <div class="cds-product-details-right">
<?php   if (sizeof($attachments) > 0) { ?>
                        <table class="cds-product-details-container cds-attribute-table" id="cds-product-attachment-table">
                            <thead>
                                <tr><td colspan="2">Documentation</td></tr>
                            </thead>
                            <tbody>
<?php       foreach ($attachments as $i => $a) { ?>
<?php
                $v = $attributeValues[$a['id']];
                $pos = strripos($v, '/');
                if ($pos !== FALSE) {
                    $v = substr($v, $pos + 1);
                }
                if (strlen($v) > 37) {
                    $v = substr($v, 0, 37)."...";
                }
?>
                                <tr class="<?php echo ($i % 2 !== 0 ? 'cds-odd' : 'cds-even') ?>">
                                    <td id="cds-al-<?php echo htmlspecialchars($a['id']) ?>"><?php echo $a['label'] ?></td>
                                    <td id="cds-av-<?php echo htmlspecialchars($a['id']) ?>">
                                        <a href="<?php echo $attributeValues[$a['id']] ?>" target="cds-catalog-attachment">
<?php           if (isset($a['imageURL'])) { ?>
                                            <img src="<?php echo $a['imageURL'] ?>">
<?php           } else { ?>
                                            <?php echo $v ?>
<?php           } ?>
                                        </a>
                                    </td>
                                </tr>
<?php       } ?>
                            </tbody>
                        </table>
<?php   } ?>

                        <div class="cds-product-details-container cds-product-company">
                            <div class="cds-company-logo">
                                <a href="http://wwww.catalogdatasolutions.com/">
                                    <img src="http://www.product-config.net/catalog3/images/go-cds-logo.jpg">
                                </a>
                            </div>
                            <div class="cds-company-name">Catalog Data Solutions</div>
                            <div class="cds-company-address">6050 Hellyer Avenue, Suite 175</div>
                            <div class="cds-company-address">San Jose, CA 95138</div>
                            <div class="cds-company-phone">Phone: (408) 550-8820</div>
                            <div class="cds-company-website">
                                <a href="http://www.catalogdatasolutions.com/">www.catalogdatasolutions.com</a>
                            </div>
                        </div>
                    </div> <!-- cds-product-details-right -->
                </div>
                <script>
                    function _cdsHandleChangeDynamicAttribute(id, doNotCallCustom) {
                        var i, e, v, min, max, a = cds.productAttributes[id];

                        if (a) {
                            if (a.dataType === "range") {
                                min = a.value && a.value.min;
                                max = a.value && a.value.max;
                                if (min && max) {
                                    e = document.getElementById("cds-dv-" + id);
                                    if (e) {
                                        v = parseFloat(e.value);
                                        if (isNaN(v) || v < min || v > max) {
                                            alert("Please enter a value between " + min + " and " + max + ".");
                                            // can't use a.value.default on IE9 compat view for some reason
                                            v = a["value"]["default"] || "";
                                            e.value = v;
                                            e.focus();
                                        } else {
                                            v = (Math.round(v * Math.pow(10, a.precision)) / Math.pow(10, a.precision))
                                                    .toFixed(a.precision);
                                            e.value = v;
                                        }
                                        if (a.cadParameterName) {
                                            cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                                        }
                                        if (true) {
                                            cds.cart.addCustomAttribute(a.label, v);
                                        }
                                    }
                                }
                            } else if (a.dataType === "list") {
                                e = document.getElementById("cds-dv-" + id);
                                if (e) {
                                    v = e.options[e.selectedIndex].value;
                                    if (v) {
                                        if (a.cadParameterName) {
                                            cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                                        }
                                        if (true) {
                                            cds.cart.addCustomAttribute(a.label, v);
                                        }
                                    }
                                }
                            } else if (a.dataType === "multilist") {
                                v = "";
                                for (i = 0; i < 100; i++) {
                                    e = document.getElementById("cds-dv-" + id + "-" + i);
                                    if (e) {
                                        if (e.checked) {
                                            if (v.length) {
                                                v += ",";
                                            }
                                            v += e.value;
                                        }
                                    } else {
                                        break;
                                    }
                                }
                                if (v.length) {
                                    if (a.cadParameterName) {
                                        cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                                    }
                                    if (true) {
                                        cds.cart.addCustomAttribute(a.label, v);
                                    }
                                }
                            } else if (a.dataType === "text") {
                                e = document.getElementById("cds-dv-" + id);
                                if (e) {
                                    v = e.value;
                                    if (v) {
                                        if (a.cadParameterName) {
                                            cds.CADRequester.addParameter(a.cadParameterName, v, a.cadDataType);
                                        }
                                        if (true) {
                                            cds.cart.addCustomAttribute(a.label, v);
                                        }
                                    }
                                }
                            }

                            if (typeof cdsHandleChangeDynamicAttribute === "function"
                                    && !doNotCallCustom) {
                                cdsHandleChangeDynamicAttribute(id, a, v);
                            }
                            _cdsSetCustomProductLabel();
                        }
                    }
                    // run once to make sure we deal with initial dynamic attributes
                    if (typeof cdsHandleChangeDynamicAttribute === "function") {
                        cdsHandleChangeDynamicAttribute();
                    }
                </script>
            </div>
