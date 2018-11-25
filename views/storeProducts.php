<table>
    <thead>
        <tr>
            <h2>Products</h2>
        </tr>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Category</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo $product->name; ?></td>
            <td><?php echo number_format($product->price , 2); ?></td>
            <td><?php echo $product->category_name; ?></td>
            <td><form method="POST" action="<?php echo get_permalink(get_option('checkout_page')); ?>">
                <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                <input type="submit" class="submit-btn" value="BUY">
            </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>