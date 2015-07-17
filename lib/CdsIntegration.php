<?php
use TopFloor\Cds\CdsService;

/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/16/2015
 * Time: 1:43 PM
 */

class CdsIntegration {
    /** @var CdsService $service */
    private static $service;

    private static $jsBlocks = array();

    public static function service() {
        if (!isset(self::$service)) {
            $settings = get_option('cds_integration_settings', array('host' => 'www.product-config.net', 'domain' => ''));

            if (!empty($settings['host']) || empty($settings['domain'])) {
                return false;
            }

            self::$service = new CdsService($settings['host'], $settings['domain']);
        }

        return self::$service;
    }

    public static function initialize() {
        $service = self::service();

        if ($service === false) {
            return;
        }

        $service->setUrlHandler(new WordPressCdsUrlHandler(self::$service));

        // TODO: Add commands to service based on current request

        // Get requirements and have WP output them
        $dependencies = $service->getDependencies();

        self::enqueueJsBlock($service->jsSettings());
        self::enqueueJs($dependencies->js());
        self::enqueueCss($dependencies->css());

        // Execute commands and retrieve JS output
        self::enqueueJsBlock($service->execute());
    }

    public static function enqueueJs($js) {
        foreach ($js as $handle => $url) {
            wp_enqueue_script($handle, $url);
        }
    }

    public static function enqueueCss($css) {
        foreach ($css as $handle => $url) {
            wp_enqueue_style($handle, $url);
        }
    }

    public static function enqueueJsBlock($code) {

    }

    public static function jsBlocks() {
        $template = '<script>%s</script>';

        $output = implode('</script><script>', self::$jsBlocks);

        return sprintf($template, $output);
    }
}
