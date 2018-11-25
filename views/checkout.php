<?php
if (isset($_POST['email'])){
  $product = $wpdb->get_row("SELECT * FROM `". $wpdb->prefix ."store_products` WHERE `id` = '". $wpdb->_real_escape($_POST['id']) ."'");
  $success = $wpdb->insert($wpdb->prefix."store_orders", [
    'product_id' => $_POST['id'],
    'sum' => $product->price,
    'status' => 'pending',
    'email' => $_POST['email'],
    'date' => date('Y-m-d')
  ]);
}
?>

<?php if(isset($_POST['id'])){ ?>
<form method="POST" action="<?php echo get_permalink(get_option('checkout_page')); ?>">
  <table>
    <tbody>
      <tr>
        <th>Email</th>
        <td><input type="text" name="email"></td>
      </tr>
      <tr>
        <td><input type="hidden" name="id" value="<?php echo $_POST['id'] ?>"></td>
      </tr>
      <tr>
        <td><input type="submit" value="Pay Now"></td>
      </tr>
    </tbody>
  </table>
</form>
<?php } ?>