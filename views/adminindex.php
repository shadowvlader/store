<div class="wrap">
    <h2>Testing plugin</h2>
    <p>To use this plugin, first put shortcode "[products]", where you want them to be displayed. <br/>
    Then, select your checkout page below and put in it shortcode "[checkout]". <br/>
    You can add, edit and delete products and their categories in submenus. <br/>
    You can see the orders and change their status.</p>
  
    <form method="POST" action="<?php echo $config->getItem('plugin_index_url'); ?>">
    <table class="form-table">
      <tbody>
        <tr class="form-field">
          <th scope="row"><label for="checkout_page"><strong>Checkout page</strong></label></th>
          <td>
            <?php wp_dropdown_pages(array('name' => 'checkout_page', 'selected' => get_option('checkout_page'), 'show_option_none' => 'None')); ?>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <input type="submit" value="Save" class="button-primary" />
    </p>
  </form>
</div><!-- .wrap -->