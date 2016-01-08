<?php
/*
Plugin Name: CDS Integration
Plugin URI: http://topfloortech.com/
Description: Integrate Catalog Data Solutions with WordPress.
Version: 1.0.0
Author: Ben McClure
Author URI: http://www.topfloortech.com/
*/

// Include core CDS library and class overrides
require_once 'vendor/autoload.php';
require_once 'lib/CdsIntegration.php';
require_once 'lib/UrlHandlers/WordPressCdsUrlHandler.php';

// Include widget classes
include_once 'lib/Widgets/CdsSidebarWidget.php';
include_once 'lib/Widgets/CdsSearchResultsWidget.php';

// Include WordPress API functions
require_once 'includes/options.php';

add_action('init', 'cds_init');
function cds_init() {
    CdsIntegration::initialize();
}

add_filter('query_vars', 'cds_queryvars');
function cds_queryvars($qvars) {
    $qvars[] = 'cds_uri';

    return $qvars;
}

add_filter('body_class', 'cds_body_class');
function cds_body_class($classes) {
    return CdsIntegration::getClasses($classes);
}

add_filter('the_content', 'cds_content');
function cds_content($content) {
    return CdsIntegration::pageContent($content);
}

add_filter('the_title', 'cds_title');
function cds_title($title, $id = null) {
    return CdsIntegration::pageTitle($title);
}

add_filter('wp_title', 'cds_page_title', 0, 2);
function cds_page_title($title, $sep) {
    return CdsIntegration::pageTitle($title, true, $sep);
}

add_filter('wpseo_title', 'cds_wpseo_title');
function cds_wpseo_title($title) {
    return CdsIntegration::pageTitle($title, true);
}

/**
 * Add all shortcodes to WordPress
 */
add_shortcode('cds-sidebar', 'cds_integration_sidebar');
function cds_integration_sidebar() {
    return CdsIntegration::sidebarOutput();
}

add_shortcode('cds-main', 'cds_integration_main');
function cds_integration_main() {
    return CdsIntegration::mainOutput();
}

/**
 * Add all widgets to WordPress
 */
add_action('widgets_init', 'register_cds_sidebar_widget');
function register_cds_sidebar_widget() {
    register_widget('CdsSidebarWidget');
    register_widget('CdsSearchResultsWidget');
}

// Add the full categories hierarchy to the breadcrumb trail.
add_filter('woo_breadcrumbs_trail', 'cds_custom_breadcrumbs_trail', 10);
function cds_custom_breadcrumbs_trail($trail) {
    $trail = CdsIntegration::breadcrumbTrail($trail);

    return $trail;
}
