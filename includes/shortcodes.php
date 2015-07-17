<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/16/2015
 * Time: 1:56 PM
 */

function cds_integration_search_sidebar() {
    $service = CdsIntegration::service();

    if ($service === false) {
        return '';
    }

    return $service->getOutputHelper()->searchSidebarContainer();
}

function cds_integration_search_main() {
    $service = CdsIntegration::service();

    if ($service === false) {
        return '';
    }

    return $service->getOutputHelper()->searchMainContainer();
}