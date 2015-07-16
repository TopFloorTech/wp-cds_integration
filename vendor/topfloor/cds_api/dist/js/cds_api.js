/**
 * Created by Ben on 7/15/2015.
 */

window.defer = window.defer || [];

/** @namespace TopFloor */
window.TopFloor = window.TopFloor || {};

TopFloor.Cds = TopFloor.Cds || {};

TopFloor.Cds.State = TopFloor.Cds.State || {};
TopFloor.Cds.State.initialized = false;

TopFloor.Cds.initialize = function () {
    defer.push({
        predicate: function () {
            return typeof TopFloor.Cds.Settings !== 'undefined'
                && typeof cds !== 'undefined';
        },
        handler: function () {
            cds.setDomain(TopFloor.Cds.Settings.domain);
            cds.setRemoteServerBaseURL(TopFloor.Cds.Settings.baseUrl);
            TopFloor.Cds.State.initialized = true;
        }
    });
};

TopFloor.Cds.Cart = {};
TopFloor.Cds.Cart.initialize = function () {
    defer.push({
        predicate: function () {
            return TopFloor.Cds.State.initialized
                && typeof cds.cart !== 'undefined';
        },
        handler: function () {
            cds.cart.setParentElementId(TopFloor.Cds.Settings.Cart.containerId);
            cds.cart.load();
        }
    });
};

TopFloor.Cds.Compare = {};
TopFloor.Cds.Compare.initialize = function () {
    defer.push({
        predicate: function () {
            return TopFloor.Cds.State.initialized
                && typeof cds.productCompareTable !== 'undefined';
        },
        handler: function () {
            cds.productCompareTable.setProductURLTemplate(TopFloor.Cds.Settings.Compare.productUrlTemplate);
            cds.productCompareTable.setParentElementId(TopFloor.Cds.Settings.Compare.containerId);
            cds.productCompareTable.load();
        }
    });
};

TopFloor.Cds.Keys = {};
TopFloor.Cds.Keys.initialize = function () {
    defer.push({
        predicate: function () {
            return TopFloor.Cds.State.initialized
                && typeof cds.keys !== 'undefined';
        },
        handler: function () {
            cds.textLabels["keyword_search_results.attribute_column_label"] = TopFloor.Cds.Settings.Keys.attributeLabel;
            cds.textLabels["keyword_search_results.value_column_label"] = TopFloor.Cds.Settings.Keys.valueLabel;
            cds.keys.containerElementId = TopFloor.Cds.Settings.Keys.containerId;
            cds.keys.productURLTemplate = TopFloor.Cds.Settings.Keys.productUrlTemplate;
            cds.keys.categoryURLTemplate = TopFloor.Cds.Settings.Keys.categoryUrlTemplate;
            cds.keys.load();
        }
    });
};

TopFloor.Cds.Search = {};
TopFloor.Cds.Search.initialize = function (parameters) {
    TopFloor.Cds.State.Search = parameters;

    defer.push({
        predicate: function () {
            return TopFloor.Cds.State.initialized
                && typeof cds.facetedSearch !== 'undefined'
                && typeof $ !== 'undefined';
        },
        handler: function () {
            $(window).load(function () {
                "use strict";

                var widest = -1;

                $('.cds-browse-list img').each(function (index, element) {
                    var width = $(element).width();

                    if (width > widest) {
                        widest = width;
                    }
                });

                if (widest > -1) {
                    cds.makeSameWidth(jQuery(".cds-browse-list").children(), null, widest);
                    cds.makeSameHeight(jQuery(".cds-browse-list").children());
                }
            });
        }
    });
};

TopFloor.Cds.Search.sidebarBlock = function () {
    defer.push({
        predicate: function () {
            return TopFloor.Cds.State.initialized
                && typeof cds.facetedSearch !== 'undefined';
        },
        handler: function () {
            cds.facetedSearch.searchURLTemplate = TopFloor.Cds.Settings.Search.searchUrlTemplate;
            cds.facetedSearch.productURLTemplate = TopFloor.Cds.Settings.Search.productUrlTemplate;
            cds.facetedSearch.categoryId = TopFloor.Cds.State.Search.categoryId;
            cds.facetedSearch.displayPowerGrid = TopFloor.Cds.State.Search.displayPowerGrid;
            cds.facetedSearch.renderProductsListType = TopFloor.Cds.State.Search.renderProductsListType;
            cds.facetedSearch.showUnitToggle = TopFloor.Cds.State.Search.showUnitToggle;
            cds.facetedSearch.appendUnitToProductURL = TopFloor.Cds.State.Search.appendUnitToProductURL;
            cds.facetedSearch.loadProducts = TopFloor.Cds.State.Search.loadProducts;
            cds.facetedSearch.init();

            cds.facetedSearch.compareCart = new cds.ProductCompareCart();
            cds.facetedSearch.compareCart.setComparePageURL(TopFloor.Cds.Settings.Search.comparePageUrl);
            cds.facetedSearch.compareCart.setMaxProducts(6);
        }
    });
};
