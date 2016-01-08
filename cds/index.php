<?php
/**
 * CDS Catalog example index.php
 *
 * Copyright Catalog Data Solutions, Inc.  All Rights Reserved.
 *
 * This page should be used as an example for creating a CDS catalog
 * template.
 */
header('Content-Type: text/html; charset=utf-8');
// CDS class must be instantiated before any output for CDS cookies to work
require_once('cds.php');
$cds = new CDS();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $cds->getPageTitle() ?></title>
        <meta name="description" content="<?php echo $cds->getMetaDescription() ?>">
        <meta name="keywords" content="<?php echo $cds->getMetaKeywords() ?>">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<?php
echo $cds->getHeadHTML();
?>
        <style>
        body {
            background-color: #fff;
            font: 13px/1.231 arial,helvetica,clean,sans-serif;
            padding: 2em;
        }

        .cds-content {
            background-color: #fff;
            min-height: 50em;
            margin-top: 1em;
            padding: 0 1em;
            text-align: left;
        }

        .cds-content .odd {
            background-color: #f0f0f0;
        }

        .cds-content h1 {
            font-size: 1.846em;
            color: #333;
            padding-top: 0.542em;
        }

        .cds-content h2 {
            font-size: 1.846em;
            color: #333;
        }

        .cds-content h3 {
            font-size: 1.385em;
            color: #666;
            padding: 0.722em 0;
        }

        .cds-content button {
            font-family: inherit;
            font-size: 1em;
            height: 2em;
            vertical-align: center;
            color: #333;
            background-color: #f2f2f2;
            background: linear-gradient(#f2f2f2, #e8e8e8);
            background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#f2f2f2), to(#e8e8e8));
            background: -webkit-linear-gradient(top, #f2f2f2, #e8e8e8);
            background: -moz-linear-gradient(top, #f2f2f2, #e8e8e8);
            background: -ms-linear-gradient(top, #f2f2f2, #e8e8e8);
            background: -o-linear-gradient(top, #f2f2f2, #e8e8e8);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#f2f2f2", endColorstr="#e8e8e8");
            border: 1px solid #d2d2d2;
            border-radius: 0.5em;
            -webkit-border-radius: 0.5em;
            -moz-border-radius: 0.5em;
            behavior: url("../../css/PIE.htc");
        }

        .cds-content button:hover {
            color: #000;
            border: 1px solid #bbb;
            box-shadow: 1px 1px 3px #d2d2d2;
            -webkit-box-shadow: 1px 1px 3px #d2d2d2;
            -moz-box-shadow: 1px 1px 3px #d2d2d2;
        }

        .cds-content button.green {
            color: #fff;
            background-color: #339933;
            background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#393), to(#282));
            background: -webkit-linear-gradient(top, #393, #282);
            background: -moz-linear-gradient(top, #393, #282);
            background: -ms-linear-gradient(top, #393, #282);
            background: -o-linear-gradient(top, #393, #282);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#393', endColorstr='#282');
            border: 1px solid #171;
        }

        .cds-content button.green:hover {
            background-color: #282;
            background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#282), to(#171));
            background: -webkit-linear-gradient(top, #282, #171);
            background: -moz-linear-gradient(top, #282, #171);
            background: -ms-linear-gradient(top, #282, #171);
            background: -o-linear-gradient(top, #282, #171);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#282', endColorstr='#171');
            border: 1px solid #060;
        }

        /* ================================== */
        /*          keyword search            */
        /* ================================== */

        .search-container {
            width: 20em;
            min-width: 20em;
            height: 2.4em;
            margin: 0;
            padding: 1px 1px 1px 2px;
            border: 1px solid #ccc;
            border-radius: 0.5em;
            -webkit-border-radius: 0.5em;
            -moz-border-radius: 0.5em;
            behavior: url("//www.product-config.net/catalog3/css/PIE.htc");
        }

        .search-box {
            font-size: 1.154em;
            float: left;
            width: 79%;
            min-width: 80%;
            height: 2.15em;
            line-height: 2.15em;
            padding: 0 0 0 0.867em;
            border: 0 none;
            color: #999;
            display: block;
        }

        .search-button {
            background: url("//www.product-config.net/catalog3/images/search.png") no-repeat scroll center center transparent;
            border: 0 none;
            display: block;
            float: right;
            height: 2.4em;
            padding: 0;
            width: 2.4em;
            cursor: pointer;
        }

        /* ================================== */
        /*          catalog overrides         */
        /* ================================== */

        /*
        .cds-browse-list li {
            width: 300px;
        }
        */

        .cds-content {
            overflow-y: visible;
        }

        .cds-content .cds-more-button {
            height: 2.5em;
        }

        .cds-product-page h1 {
            padding: 0;
            margin: 0 0 0.271em;
        }

        .cds-product-image-container,
        .cds-product-image {
            height: auto;
        }

        .cds-product-spec-sheet {
            margin-top: 0.5em;
        }

        .cds-product-company {
            text-align: right;
        }

        .ui-autocomplete {
            text-align: left;
        }

        .cds-attribute-list #cds-attribute-q input {
            width: 66%;
        }

        .cds-attribute-list #cds-attribute-q button {
            width: 20%;
        }

        </style>
    </head>
    <body>
        <div class="search-container" style="float: right">
            <input type="text" onfocus="if (this.value === 'Search') { this.value = ''; } else { select(); }"
                    onblur="if (this.value === '') { this.value = 'Search'; }" value="Search"
                    id="cds-keys-input" class="search-box">
            <button id="cds-keys-button" class="search-button"></button>
            <script>
                function cdsKeysNoResultsCallback(q) {
                    alert("no results found for keyword search: " + q);
                }
                cds.keys.inputElementId = "cds-keys-input";
                cds.keys.buttonElementId = "cds-keys-button";
                cds.keys.resultsURLTemplate = "?page=search&cid=root";
                cds.keys.autoComplete = true;
                cds.keys.loadInput();
            </script>
        </div>
        <div style="clear: both"></div>
<?php
echo $cds->getCatalogHTML();
?>
        <div style="clear: both"></div>
    </body>
</html>
