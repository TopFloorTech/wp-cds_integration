<?php
    $category = 'root';
    if (isset($_REQUEST['cid'])) {
        $category = $_REQUEST['cid'];
    }

    $cds_service = new CDSWebService($host, $unitSystem);
    $category_info = $cds_service->sendCategoryRequest($domain, $category, $error);
    if ($error !== false) {
        header('Location: catalog-error.html');
        return;
    }

    $keywords = null;
    if (isset($_REQUEST['q'])) {
        $keywords = $_REQUEST['q'];
    }
    $load_products = sizeof($category_info['children']) === 0 || isset($_REQUEST['filter']);
?>
            <script src="http://<?php echo htmlspecialchars($host) ?>/catalog3/js/cds-faceted-search2.js"></script>
            <script>
                cds.setDomain(<?php echo json_encode($domain) ?>);
                cds.setRemoteServerBaseURL(<?php echo json_encode("http://$host/catalog3") ?>);

                window.onload = function () {
                    "use strict";
                    var i = -1;
                    jQuery(".cds-browse-list img").each(function (index, element) {
                        if (jQuery(this).width() > i) {
                            i = jQuery(this).width();
                        }
                    });
                    if (i > -1) {
                        cds.makeSameWidth(jQuery(".cds-browse-list").children(), null, i);
                        cds.makeSameHeight(jQuery(".cds-browse-list").children());
                    }
                };
            </script>
            <div id="cds-content" class="cds-content">
                <div id="cds-search-left-container">
                </div> <!-- cds-search-left-container -->
                <script>
                    cds.facetedSearch.searchURLTemplate = "?page=search&cid=%CATEGORY%";
                    cds.facetedSearch.productURLTemplate = "?page=product&id=%PRODUCT%&cid=%CATEGORY%";
                    cds.facetedSearch.categoryId = <?php echo json_encode($category) ?>;
                    cds.facetedSearch.displayPowerGrid = true;
                    cds.facetedSearch.renderProductsListType = "list";
                    cds.facetedSearch.showUnitToggle = false;
                    cds.facetedSearch.appendUnitToProductURL = true;
                    cds.facetedSearch.loadProducts = <?php echo json_encode($load_products) ?>;
                    cds.facetedSearch.init();

                    cds.facetedSearch.compareCart = new cds.ProductCompareCart();
                    cds.facetedSearch.compareCart.setComparePageURL("?page=compare");
                    cds.facetedSearch.compareCart.setMaxProducts(6);
                </script>
                <div id="cds-search-right-container" class="cds-browse-container">
                    <ul class="cds-crumbs">
<?php   foreach ($category_info['crumbs'] as $c) { ?>
<?php       if ($c['id'] === 'root') { ?>
                        <li><a href="?page=search"><?php echo $c['label'] ?></a></li>
                        <li><span>&gt;</span></li>
<?php       } else { ?>
                        <li><a href="?page=search&cid=<?php echo urlencode($c['id']) ?>"><?php echo $c['label'] ?></a></li>
                        <li><span>&gt;</span></li>
<?php       } ?>
<?php   } ?>
                        <li><?php echo $category_info['label'] ?></li>
                    </ul>

<?php   if (sizeof($category_info['crumbs']) === 0 && (!isset($category_info['searchImageURL']) || $load_products)) { ?>
                    <h2>Select Category</h2>
<?php   } else { ?>
<?php       if (isset($_REQUEST['q'])) { ?>
                    <h2><?php echo ucwords($_REQUEST['q']).": ".$category_info['label'] ?></h2>
<?php       } else { ?>
                    <h2><?php echo $category_info['label'] ?></h2>
<?php       } ?>
<?php   } ?>
<?php   if (isset($category_info['searchImageURL']) || isset($category_info['searchHeaderHTML'])
                || isset($category_info['description'])) { ?>
                    <div class="head">
<?php       if (isset($category_info['searchImageURL'])) { ?>
                        <img src="<?php echo htmlspecialchars($category_info['searchImageURL']) ?>">
<?php       } ?>
<?php       if (isset($category_info['searchHeaderHTML'])) { ?>
                        <p><?php echo $category_info['searchHeaderHTML'] ?></p>
<?php       } elseif (isset($category_info['description'])) { ?>
                        <p><?php echo $category_info['description'] ?></p>
<?php       } ?>
                    </div>
<?php   } ?>
<?php   if (!$load_products) { ?>
                    <ul class="cds-browse-list">
<?php       foreach ($category_info['children'] as $c) { ?>
                        <li>
                            <a href="?cid=<?php echo urlencode($c['id']) ?>">
<?php           if (isset($c['browseImageURL'])) { ?>
                                <img src="<?php echo htmlspecialchars($c['browseImageURL']) ?>"
<?php               if (isset($c['imageTitle'])) { ?>
                                        title="<?php echo htmlspecialchars($c['imageTitle']) ?>"
<?php               } ?>
<?php               if (isset($c['imageAlt'])) { ?>
                                        alt="<?php echo htmlspecialchars($c['imageAlt']) ?>"
<?php               } ?>
                                >
<?php           } ?>
                                <h3><?php echo $c['label'] ?></h3>
<?php           if (isset($c['description'])) { ?>
                                <p><?php echo $c['description'] ?></p>
<?php           } ?>
                            </a>
                        </li>
<?php       } ?>
                    </ul>

<?php   } else { ?>
                    <div id="cds-product-container">
                        <div id="cds-product-loading-container">
                            <img src="http://<?php echo htmlspecialchars($host) ?>/catalog3/images/progress_animation_large.gif">
                            <div>Loading products...</div>
                        </div>
                    </div>
<?php   } ?>
                </div> <!-- faceted-search-right-container -->
            </div>
