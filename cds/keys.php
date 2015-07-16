            <script>
                cds.setDomain(<?php echo json_encode($domain) ?>);
                cds.setRemoteServerBaseURL(<?php echo json_encode("http://$host/catalog3") ?>);
            </script>
            <div id="cds-content" class="cds-content">
                <h1>Keyword Search Results</h1>
                <div class="info">Only the product name and first matching attribute is shown. Only the first 100
                    products found are shown.</div>
                <div id="cds-keys-results">
                    <h4>
                        <img src="http://<?php echo htmlspecialchars($host) ?>/catalog3/images/progress_animation.gif">
                        Loading results please wait...
                    </h4>
                </div>
                <script>
                    cds.textLabels["keyword_search_results.attribute_column_label"] = "Attribute";
                    cds.textLabels["keyword_search_results.value_column_label"] = "Value";
                    cds.keys.containerElementId = "cds-keys-results";
                    cds.keys.productURLTemplate = "?page=product&id=%PRODUCT%";
                    cds.keys.categoryURLTemplate = "?page=search&cid=%CATEGORY%";
                    cds.keys.load();
                </script>
            </div>
