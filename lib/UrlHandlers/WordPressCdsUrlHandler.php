<?php
use TopFloor\Cds\UrlHandlers\EnvironmentBasedUrlHandler;

/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/16/2015
 * Time: 1:44 PM
 */
class WordPressCdsUrlHandler extends EnvironmentBasedUrlHandler
{
    protected function initialize() {
        //add_rewrite_tag('%cds_uri%', '([^?&]+)');
        //add_rewrite_tag('%cds_filter%', '([^?&]+)');

        foreach (CdsIntegration::environments() as $basePath => $categoryId) {
            $destination = 'index.php?pagename=' . $basePath . '&cds_uri=$matches[1]';

            $regex = '^(' . $basePath . '(\/[^\?]+)?)(\/?\?{0}|\/?\?{1}.*)$';

            add_rewrite_rule($regex, $destination, 'top');
        }
    }

    public function buildParameters($parameters = array()) {
        if (isset($_REQUEST['filter'])) {
            $parameters['filter'] = $_REQUEST['filter'];
        }

        return parent::buildParameters($parameters);
    }

    /**
     * @return mixed
     */
    protected function getEnvironments() {
        return CdsIntegration::environments();
    }

    public function getCurrentUri() {
        global $wp_query;

        if (isset($wp_query->query_vars['cds_uri'])) {
            return $wp_query->query_vars['cds_uri'];
        }

        return parent::getCurrentUri();
    }
}
