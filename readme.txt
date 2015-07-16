=== CDS Integration ===
Contributors: broken85
Requires at least: 3.8
Tested up to: 4.0
Stable tag: master
License: GPL2

Allows you to show CDS (Catalog Data Solutions) widgets and content on a WordPress site.

== Description ==
Using this plugin, you can integrate a CDS catalog with your WordPress site. Things such as categories, products,
faceted search in the sidebar, and more are all able to be handled automatically via this plugin.

== Installation ==
1. Enable the plugin and its dependencies
2. Add the following to your <head> tag in your theme: <?php echo CdsIntegration::jsBlocks(); ?>
2. Visit the plugin settings page and fill out the CDS host and domain provided by Catalog Data Solutions.
3. Place your widgets and configure your other settings as appropriate.

== Frequently Asked Questions ==
Q: Where do I find my CDS Host and Domain?
A: The host and domain are both provided by Catalog Data Solutions and you would need to retrieve these values from
them. They may have sent you a \"cds_service_request.php\" file which contains the values.
