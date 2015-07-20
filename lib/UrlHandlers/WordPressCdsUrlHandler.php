<?php
use TopFloor\Cds\UrlHandlers\UrlHandler;

/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/16/2015
 * Time: 1:44 PM
 */
class WordPressCdsUrlHandler extends EnvironmentBasedUrlHandler
{
    protected function getEnvironments() {
        $options = get_option('cds_integration_settings');

        if (!isset($options['cds_environments'])) {
            $options['cds_environments'] = array();
        }

        return $options['environments'];
    }
}
