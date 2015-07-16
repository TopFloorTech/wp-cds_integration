<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/16/2015
 * Time: 1:56 PM
 */

function cds_integration_search_sidebar() {
    return CdsIntegration::service()->getOutputHelper()->searchSidebarContainer();
}

function cds_integration_search_main() {
    return CdsIntegration::service()->getOutputHelper()->searchMainContainer();
}