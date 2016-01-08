<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/16/2015
 * Time: 1:35 PM
 */

class CdsSidebarWidget extends WP_Widget {
    /**
     * Sets up the Widgets name etc
     */
    public function __construct() {
        parent::__construct(
            'cds_sidebar_widget',
            __('CDS Sidebar', 'cds_integration'),
            array(
                'description' => __('Shows the CDS sidebar', 'cds_integration'),
                'classname' => 'CdsSidebarWidget',
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
        echo cds_integration_sidebar();
    }

    // Widget Backend
    public function form( $instance ) {

    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        return $new_instance;
    }
}
