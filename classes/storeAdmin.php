<?php
/**
 * Our main class
 */
if ( ! class_exists('storeAdmin') ) {
  class storeAdmin {
  
    /**
     * Admin interface > overview
     */
    public function adminIndex() {
			$config = storeConfig::getInstance();
			
			if ( isset($_POST['checkout_page']) && is_numeric($_POST['checkout_page']) && $_POST['checkout_page'] > 0 ) {
				update_option('checkout_page', $_POST['checkout_page']);
			}
			
      require $config->getItem('views_path').'adminindex.php';
    }

    private function validateFormInput($input){
    	if(!isset($_POST[$input]) || empty($_POST[$input])) return false;

    	return true;
    }

    private function generateActionResponse($success, $message){
	    return (object)[
		    "success" => $success,
		    "message" => $message
	    ];
		}
	
    /**
     * Admin interface > categories
     */
    public function adminCategory() {
      global $wpdb;
		  $config = storeConfig::getInstance();      

		  $categoryActionResponse = NULL;

		  if(isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == 'delete'){
			  $category = $wpdb->get_row("SELECT `name` FROM `". $wpdb->prefix ."store_categories` WHERE `id` = '". $wpdb->_real_escape($_GET['id']) ."'");
			  if($category) {
				  $wpdb->delete($wpdb->prefix."store_categories", ['id' => $_GET['id']]);
				  $categoryActionResponse = self::generateActionResponse(true, "Category successfully deleted");
			  }
		  }

		  if(isset($_POST['new-category'])){
			  if(self::validateFormInput('name')){
          $wpdb->insert($wpdb->prefix."store_categories", [
            'name' => $_POST['name'],
            'parent_category' => $_POST['parent']
          ]);
				  $categoryActionResponse = self::generateActionResponse(true, "Category successfully added");
			  } else {
				  $categoryActionResponse = self::generateActionResponse(false,"Category name must be provided!");
			  }
		  }

		  if(isset($_POST['save-category'])){
			  if(self::validateFormInput('name') && self::validateFormInput('parent') && self::validateFormInput('id')) {
				  $wpdb->update( $wpdb->prefix . 'store_categories', [
            'name' => $_POST['name'],
            'parent_category' => $_POST['parent']
			        ],
				    [
					  'id' => $_POST['id']
				    ]
				  );
				  $categoryActionResponse = self::generateActionResponse(true, "Category successfully updated");
			  } else {
				  $categoryActionResponse = self::generateActionResponse(false,"Category name must be provided!");
			  }
		  }

		  $categories = $wpdb->get_results("SELECT * FROM `". $wpdb->prefix ."store_categories`");

		  require $config->getItem('views_path').'admincategory.php';
    }

    

    /**
     * Admin interface > products
     */
    public function adminProduct() {
      global $wpdb;
	    $config = storeConfig::getInstance();

	    $productActionResponse = NULL;

	    if(isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == 'delete'){
		    $product = $wpdb->get_row("SELECT `name` FROM `". $wpdb->prefix ."store_products` WHERE `id` = '". $wpdb->_real_escape($_GET['id']) ."'");
		    if($product) {
		    	$wpdb->delete($wpdb->prefix."store_products", ['id' => $_GET['id']]);
			    $productActionResponse = self::generateActionResponse(true, "Product successfully deleted");
		    }
	    }

	    if(isset($_POST['new-product'])){
				if(self::validateFormInput('name') && self::validateFormInput('price') && self::validateFormInput('category')){
					$wpdb->insert($wpdb->prefix."store_products", [
						'name' => $_POST['name'],
						'price' => $_POST['price'],
						'category' => $_POST['category']
					]);
					$productActionResponse = self::generateActionResponse(true, "Product successfully added");
				} else {
					$productActionResponse = self::generateActionResponse(false,"Product Name, Price and Category must be provided!");
				}
	    }

	    if(isset($_POST['save-product'])){
		    if(self::validateFormInput('name') && self::validateFormInput('price') && self::validateFormInput('id')) {
			    $wpdb->update( $wpdb->prefix . 'store_products', [
				        'name' => $_POST['name'],
				        'price' => $_POST['price'],
				        'category' => $_POST['category']
		            ],
			        [
			        	'id' => $_POST['id']
			        ]
			    );
			    $productActionResponse = self::generateActionResponse(true, "Product successfully updated");
		    } else {
			    $productActionResponse = self::generateActionResponse(false,"Product Name, Price and Category must be provided!");
		    }
	    }

	    $categories = $wpdb->get_results("SELECT * FROM `". $wpdb->prefix ."store_categories`");
	    $products = $wpdb->get_results("SELECT `". $wpdb->prefix ."store_products`.`id`, `". $wpdb->prefix ."store_products`.`name`, `". $wpdb->prefix ."store_products`.`price`, `". $wpdb->prefix ."store_products`.`category`, `". $wpdb->prefix ."store_categories`.`name` AS `category_name`
			FROM `". $wpdb->prefix ."store_products` 
			LEFT JOIN `". $wpdb->prefix ."store_categories` ON
			`". $wpdb->prefix ."store_products`.`category` = `". $wpdb->prefix ."store_categories`.`id`");

	    require $config->getItem('views_path').'adminproduct.php';
    }

    /**
     * Admin interface > orders
     */
    public function adminOrder() {
		global $wpdb;
		$config = storeConfig::getInstance();
		$orderActionResponse = NULL;
	    if(isset($_GET['action']) && isset($_GET['id'])){
			$order = $wpdb->get_row("SELECT * FROM `". $wpdb->prefix ."store_orders` WHERE `id` = '". $wpdb->_real_escape($_GET['id']) ."'");
		    if($order) {
		    	if($_GET['action'] == 'set-paid') {
		    		$wpdb->update($wpdb->prefix . "store_orders", ['status' => 'paid'], [ 'id' => $_GET['id'] ]);
				    $orderActionResponse = self::generateActionResponse( true, "Order successfully set to paid" );
			    } else if($_GET['action'] == 'set-shipped') {
				    $wpdb->update($wpdb->prefix . "store_orders", ['status' => 'shipped'], [ 'id' => $_GET['id'] ]);
				    $orderActionResponse = self::generateActionResponse( true, "Order successfully set to shipped" );
			    } else  if($_GET['action'] == 'set-delivered') {
				    $wpdb->update($wpdb->prefix . "store_orders", ['status' => 'delivered'], [ 'id' => $_GET['id'] ]);
				    $orderActionResponse = self::generateActionResponse( true, "Order successfully set to delivered" );
			    } else  if($_GET['action'] == 'set-pending') {
				    $wpdb->update($wpdb->prefix . "store_orders", ['status' => 'pending'], [ 'id' => $_GET['id'] ]);
				    $orderActionResponse = self::generateActionResponse( true, "Order successfully set to pending" );
			    }
		    }
	    }

		$orders = $wpdb->get_results("SELECT `". $wpdb->prefix ."store_orders`.`id`, `". $wpdb->prefix ."store_orders`.`sum`, `". $wpdb->prefix ."store_orders`.`status`, `". $wpdb->prefix ."store_orders`.`email`, `". $wpdb->prefix ."store_orders`.`date`, `". $wpdb->prefix ."store_products`.`name` AS `product_name`
			FROM `". $wpdb->prefix ."store_orders` 
			LEFT JOIN `". $wpdb->prefix ."store_products` ON
			`". $wpdb->prefix ."store_orders`.`product_id` = `". $wpdb->prefix ."store_products`.`id`");
		require $config->getItem('views_path').'adminorder.php';
    }

    /**
     * Delete table for store
     */
    public static function pluginRemove() {
      global $wpdb;
      $wpdb->query('DROP TABLE `'. $wpdb->prefix .'store_categories`');
      $wpdb->query('DROP TABLE `'. $wpdb->prefix .'store_products`');
      $wpdb->query('DROP TABLE `'. $wpdb->prefix .'store_orders`');
    }
    
    /**
     * Create table for store
     */
    public static function pluginInstall() {
      global $wpdb;
      $wpdb->query('CREATE TABLE IF NOT EXISTS  `'. $wpdb->prefix .'store_categories` (
      `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
      `name` VARCHAR(64) NOT NULL,
      `parent_category` BIGINT NOT NULL
      ) ENGINE = MYISAM ;');

      $wpdb->query('CREATE TABLE IF NOT EXISTS `'. $wpdb->prefix .'store_products` (
      `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
      `name` VARCHAR(128) NOT NULL,
      `category` BIGINT NOT NULL,
      `price` DOUBLE(5, 2) NOT NULL
      ) ENGINE = MYISAM ;');

      $wpdb->query('CREATE TABLE IF NOT EXISTS `'. $wpdb->prefix .'store_orders` (
      `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
      `product_id` BIGINT NOT NULL,
      `sum` DOUBLE(5, 2) NOT NULL,
      `status` ENUM("pending", "paid", "shipped", "delivered"),
      `email` VARCHAR(64) NOT NULL,
      `date` DATETIME NOT NULL
      ) ENGINE = MYISAM ;');
    }   
  }
}