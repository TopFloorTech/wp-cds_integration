<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/16/2015
 * Time: 1:35 PM
 */

class CdsSearchResultsWidget extends WP_Widget {
    /**
     * Sets up the Widgets name etc
     */
    public function __construct() {
        parent::__construct(
            'cds_search_results_widget',
            __('CDS Search Results', 'cds_integration'),
            array(
                'description' => __('Shows available CDS search results', 'cds_integration'),
                'classname' => 'CdsSearchResultsWidget',
            )
        );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        echo CdsIntegration::searchResultsWidget();
    }

    // Widget Backend
    public function form( $instance ) {

    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        return $new_instance;
    }
}
