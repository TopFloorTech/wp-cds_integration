            <script>
                cds.setDomain(<?php echo json_encode($domain) ?>);
                cds.setRemoteServerBaseURL(<?php echo json_encode("http://$host/catalog3") ?>);
            </script>
            <div id="cds-content" class="cds-content">
                <div id="cds-product-compare-container"></div>
                <script>
                    cds.productCompareTable.setProductURLTemplate("?page=product&id=%PRODUCT%&cid=%CATEGORY%");
                    cds.productCompareTable.setParentElementId("cds-product-compare-container");
                    cds.productCompareTable.load();
                </script>
            </div>
