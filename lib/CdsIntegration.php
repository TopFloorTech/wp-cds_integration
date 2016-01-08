<?php
use TopFloor\Cds\CdsComponents\KeysCdsComponent;
use TopFloor\Cds\CdsService;

/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/16/2015
 * Time: 1:43 PM
 */

class CdsIntegration {
    /** @var CdsService $service */
    private static $service = false;
    private static $jsBlocks = array();
    /** @var KeysCdsComponent */
    private static $searchComponent;

    /**
     * @return \TopFloor\Cds\CdsService
     */
    public static function service() {
        if (self::$service === false) {
            $settings = self::options();

            if (empty($settings['cds_host']) || empty($settings['cds_domain'])) {
                return false;
            }

            $service = new CdsService($settings['cds_host'], $settings['cds_domain']);
            $urlHandler = new WordPressCdsUrlHandler($service);
            $service->setUrlHandler($urlHandler);

            $pages = $service->getAvailablePages();

            self::$service = $service;

            if (!empty($pages[self::pageSlug()])) {
                $service->setPage($service->createPage(self::pageSlug()));
            } else {
                $environments = $settings['cds_environments'];


                if (!empty($environments)) {
                    $keys = array_keys($environments);

                    $path = array_shift($keys);

                    $productUrlTemplate = $urlHandler->construct(array(
                      'page' => 'product',
                      'id' => '%PRODUCT%',
                      'cid' => 'product',
                    ), '', $path);

                    $categoryUrlTemplate = $urlHandler->construct(array(
                      'page' => 'search',
                      'cid' => '%CATEGORY%',
                    ), '', $path);

                    $component = new KeysCdsComponent($service, null, array('parameters' => array(
                      'productUrlTemplate' => $productUrlTemplate,
                      'categoryUrlTemplate' => $categoryUrlTemplate,
                    )));

                    $service->addComponent($component);
                    self::$searchComponent = $component;
                }
            }
        }

        return self::$service;
    }

    public static function pageSlug() {
        $urlHandler = self::service()->getUrlHandler();

        $uri = $urlHandler->getCurrentUri();
        return $urlHandler->getPageFromUri($uri);
    }

    public static function options() {
        $default = array(
            'cds_host' => 'www.product-config.net',
            'cds_domain' => '',
            'environments' => array(),
        );

        $settings = get_option('cds_integration_settings', array());

        return $settings + $default;
    }

    public static function environments() {
        $settings = self::options();

        return $settings['cds_environments'];
    }

    public static function pageContent($content) {
        $uri = self::service()->getUrlHandler()->getEnvironmentUri();

        if (!empty($uri)) {
            // Return just the CDS container
            $content = '[cds-main]';
        }

        return $content;
    }

    public static function pageTitle($title, $browserTitle = false, $sep = '|') {
        if (!$browserTitle && !in_the_loop()) {
            return $title;
        }

        $environment = self::service()->getUrlHandler()->getCurrentEnvironment();

        if (!empty($environment)) {
            // Return just the CDS container
            $newTitle = self::service()->pageTitle() ?: $title;

            if ($browserTitle) {
                $sepPos = strpos($title, $sep);

                if ($sepPos !== false) {
                    //$title = trim(substr($title, $sepPos + 1));
                }

                $newTitle = "$newTitle $sep $title";
            }

            $title = $newTitle;
        }

        return $title;
    }

    public static function sidebarOutput() {
        return self::service()->sidebarOutput();
    }

    public static function mainOutput() {
        return self::service()->output();
    }

    public static function searchResultsWidget() {
        if (!is_search()) {
            return '';
        }

        self::service();

        return self::$searchComponent->output();
    }

    public static function getClasses($classes) {
        $page = self::pageSlug();

        if (!empty($page)) {
            $classes[] = 'cds-command cds-page';
            $classes[] = "cds-command-$page cds-page-$page";
        }

        return $classes;
    }

    public static function initialize() {
        self::service();
        self::initializeAssets();
    }

    protected static function initializeAssets() {
        // Get requirements and have WP output them

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-migrate');
        wp_enqueue_script('jquery-ui');
        wp_enqueue_script('jquery-ui-dialog');

        $dependencies = self::service()->getDependencies();

        $js = array('cds-integration' => plugins_url('js/cds_integration.js', dirname(__FILE__)));
        $js += $dependencies->js();

        self::enqueueJsBlock(self::service()->jsSettings());
        self::enqueueJs($js);
        self::enqueueCss($dependencies->css(), array('wp-jquery-ui-dialog'));

        // Execute commands and retrieve JS output
        self::enqueueJsBlock(self::service()->execute());
    }

    public static function enqueueJs($js, $dependencies = array()) {
        $dependencies = (array) $dependencies;

        foreach ($js as $handle => $url) {

            // Make url absolute if needed
            if (preg_match('/^((https?:)?\/\/)/', $url) === 0) {
                if (substr($url, 0, 1) !== '/') {
                    $url = '/' . $url;
                }

                $url = plugins_url('vendor/topfloor/cds_api/dist/js' . $url, dirname(__FILE__));
            }

            wp_enqueue_script($handle, $url, $dependencies, false, true);

            //$dependencies[] = $handle;
        }
    }

    public static function enqueueCss($css, $dependencies = array()) {
        $dependencies = (array) $dependencies;

        foreach ($css as $handle => $url) {

            // Make url absolute if needed
            if (preg_match('/^((https?:)?\/\/)/', $url) === 0) {
                if (substr($url, 0, 1) !== '/') {
                    $url = '/' . $url;
                }

                $url = plugins_url('vendor/topfloor/cds_api/dist/css' . $url, dirname(__FILE__));
            }

            wp_enqueue_style($handle, $url, $dependencies);

            //$dependencies[] = $handle;
        }
    }

    public static function enqueueJsBlock($code) {
        self::$jsBlocks[] = $code;
    }

    public static function jsBlocks() {
        $output = implode('</script><script>', self::$jsBlocks);

        return sprintf('<script>%s</script>', $output);
    }

    public static function breadcrumbTrail($trail) {
        $linkTemplate = '<a href="%s" title="%s">%s</a>';

        /** @var WordPressCdsUrlHandler $urlHandler */
        $urlHandler = self::service()->getUrlHandler();
        $uri = $urlHandler->getEnvironmentUri();

        if (!$urlHandler->getCurrentEnvironment() || empty($uri)) {
            return $trail;
        }

        $breadcrumbs = self::service()->getBreadcrumbsHelper()->getBreadcrumbs();

        $end = $trail['trail_end'];
        unset($trail['trail_end']);

        foreach ($breadcrumbs as $breadcrumb) {
            $value = ($breadcrumb['label'] == 'Home') ? $end : $breadcrumb['label'];

            if (!empty($breadcrumb['url'])) {
                $value = sprintf($linkTemplate, $breadcrumb['url'], esc_attr($value), $value);
            }

            $trail[] = $value;
        }

        return $trail;
    }
}
