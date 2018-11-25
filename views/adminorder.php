<?php
if($productActionResponse != NULL){
	echo "<div class='". ($productActionResponse->success?"updated":"error") ."' id='message'>
		  <p><strong>". $productActionResponse->message ."</strong></p>
		</div>";
}
?>

<h2>Orders</h2>

<table class="wp-list-table widefat fixed striped pages">
    <thead>
    <tr>
        <th style="width: 20px;">ID</th>
        <th style="width: 20%;">Product</th>
        <th style="width: 20%;">Price</th>
        <th style="width: 40%;">Email</th>
        <th style="width: 10%;">Date</th>
        <th style="width: 10%;">Status</th>
        <th colspan="2" style="width: 150px;">Actions</th>
    </tr>
    </thead>
    <tbody>

	<?php foreach ($orders as $order): ?>
        <tr>
            <td><?php echo $order->id; ?></td>
            <td><?php echo $order->product_name; ?></td>
            <td><?php echo number_format($order->sum, 2); ?></td>
            <td><?php echo $order->email; ?></td>
            <td><?php echo date("Y-m-d", strtotime($order->date)); ?></td>
            <td><?php echo strtoupper($order->status); ?></td>
            <td>
                <?php if($order->status == "pending"): ?>
                    <a href="<?php echo $config->getItem('plugin_order_url'); ?>&action=set-paid&id=<?php echo $order->id; ?>"><span class="dashicons dashicons-image-rotate"></span> Set paid</a>
                <?php elseif($order->status == "paid"):; ?>
                    <a href="<?php echo $config->getItem('plugin_order_url'); ?>&action=set-shipped&id=<?php echo $order->id; ?>"><span class="dashicons dashicons-image-rotate"></span> Set shipped</a>
                <?php elseif($order->status == "shipped"): ?>
                    <a href="<?php echo $config->getItem('plugin_order_url'); ?>&action=set-delivered&id=<?php echo $order->id; ?>"><span class="dashicons dashicons-editor-spellcheck"></span> Set delivered</a>
                <?php elseif($order->status == "delivered"): ?>
                    <a href="<?php echo $config->getItem('plugin_order_url'); ?>&action=set-pending&id=<?php echo $order->id; ?>"><span class="dashicons dashicons-yes"></span> Set pending</a>
                <?php endif; ?>
            </td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>