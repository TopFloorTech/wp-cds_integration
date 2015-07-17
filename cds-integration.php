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

// Include WordPress API functions
require_once 'includes/options.php';
require_once 'includes/widgets.php';
require_once 'includes/shortcodes.php';

// Include widget classes
include_once 'lib/Widgets/CdsSearchSidebarWidget.php';

CdsIntegration::initialize();

/**
 * Add all shortcodes to WordPress
 */
add_shortcode('cds-search-sidebar', 'cds_integration_search_sidebar');
add_shortcode('cds-search-main', 'cds_integration_search_main');

/**
 * Add all widgets to WordPress
 */
add_action('widgets_init', 'register_cds_search_sidebar_widget');
