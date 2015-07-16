            <script>
                cds.setDomain(<?php echo json_encode($domain) ?>);
                cds.setRemoteServerBaseURL(<?php echo json_encode("http://$host/catalog3") ?>);
            </script>
            <div id="cds-content" class="cds-content">
                <h1>RFQ Cart</h1>
                <div>Click <a href="?page=search">here</a> to continue product selection</div>
                <div id="cds-cart-container"></div>
                <script>
                    cds.cart.setParentElementId("cds-cart-container");
                    cds.cart.load();
                </script>
            </div>
