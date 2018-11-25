<?php
/**
 * @author Vladas Satas <vladassa@gmail.com>
 */
/*
Plugin Name: Store
Plugin URI:
Description: Test store
Author: Vladas
Version: 1.0
Author URI:
*/
require ABSPATH.'wp-content/plugins/store/classes/config.php';
require ABSPATH.'wp-content/plugins/store/classes/storeAdmin.php';
require ABSPATH.'wp-content/plugins/store/classes/shortcode.php';

/* Set base configuration */
$config = storeConfig::getInstance();

$config->addItem('plugin_id', 'store');
$config->addItem('plugin_category_id', 'store-category');
$config->addItem('plugin_product_id', 'store-product');
$config->addItem('plugin_order_id', 'store-order');
$config->addItem('plugin_index_id', 'store');

$config->addItem('plugin_path', plugin_dir_path(__FILE__));
$config->addItem('views_path', $config->getItem('plugin_path').'views/');

$config->addItem('plugin_url', home_url('/wp-admin/admin.php?page='.$config->getItem('plugin_id')));
$config->addItem('plugin_category_url', home_url('/wp-admin/admin.php?page='.$config->getItem('plugin_category_id')));
$config->addItem('plugin_product_url', home_url('/wp-admin/admin.php?page='.$config->getItem('plugin_product_id')));
$config->addItem('plugin_order_url', home_url('/wp-admin/admin.php?page='.$config->getItem('plugin_order_id')));
$config->addItem('plugin_index_url', home_url('/wp-admin/admin.php?page='.$config->getItem('plugin_index_id')));

$config->addItem('plugin_form_handler_url', home_url('/wp-content/plugins/'.$config->getItem('plugin_id').'/form-handler.php'));

$config->addItem('plugin_name', 'Store');

/**
 * Create admin menus
 */
function storeMenu() {
    $config = storeConfig::getInstance();

    add_menu_page($config->getItem('plugin_name'), $config->getItem('plugin_name'), 'level_10', $config->getItem('plugin_id'), array('storeAdmin', 'adminIndex'));
    add_submenu_page($config->getItem('plugin_id'), 'Categories', 'Categories', 'level_10', $config->getItem('plugin_category_id'), array('storeAdmin', 'adminCategory'));
    add_submenu_page($config->getItem('plugin_id'), 'Products', 'Products', 'level_10', $config->getItem('plugin_product_id'), array('storeAdmin', 'adminProduct'));
    add_submenu_page($config->getItem('plugin_id'), 'Orders', 'Orders', 'level_10', $config->getItem('plugin_order_id'), array('storeAdmin', 'adminOrder'));
}
add_action('admin_menu', 'storeMenu');

add_shortcode('products', array('storeShortcode', 'storeProducts'));
add_shortcode('checkout', array('storeShortcode', 'checkout'));


/**
 * Create table for store on plugin activation
 */
register_activation_hook(__FILE__, array('storeAdmin', 'pluginInstall'));

/**
 * Delete table for store on plugin activation
 */
register_deactivation_hook(__FILE__, array('storeAdmin', 'pluginRemove'));