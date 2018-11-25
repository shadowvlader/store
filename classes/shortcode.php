<?php
/**
 * Shortcode class
 *
 */
if ( ! class_exists('storeShortcode') ) {

    class storeShortcode {
        public static function storeProducts($atts = [], $content = NULL, $tag = '') {
            ob_start();

            global $wpdb;
            $config = storeConfig::getInstance();

            $atts = array_change_key_case((array)$atts, CASE_LOWER);

	        // override default attributes with user attributes
	        $options = shortcode_atts([
		        'products' => '',
		        'lock' => ''
	        ], $atts, $tag);

            $products = $wpdb->get_results("SELECT `". $wpdb->prefix ."store_products`.`id`, `". $wpdb->prefix ."store_products`.`name`, `". $wpdb->prefix ."store_products`.`price`, `". $wpdb->prefix ."store_categories`.`name` AS `category_name`
			FROM `". $wpdb->prefix ."store_products` 
			LEFT JOIN `". $wpdb->prefix ."store_categories` ON
			`". $wpdb->prefix ."store_products`.`category` = `". $wpdb->prefix ."store_categories`.`id`");

            require $config->getItem('views_path') . 'storeProducts.php';

            return ob_get_clean();
        }

        public static function checkout() {
            ob_start();

            $checkoutActionResponse = NULL;

            global $wpdb;
            $config = storeConfig::getInstance();

            require $config->getItem('views_path') . 'checkout.php';

            return ob_get_clean();
        }
  
    }
  
}