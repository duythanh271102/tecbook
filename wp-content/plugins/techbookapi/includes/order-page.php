<?php
function techbook_orders_page()
{
    global $wpdb;

    wp_enqueue_style('techbook-order-style', plugin_dir_url(__FILE__) . 'assets/order-style.css');


    // Check if an order ID is provided to display order details
    if (isset($_GET['order_id'])) {
        $order_id = intval($_GET['order_id']);
        $table_name = $wpdb->prefix . 'techbook_order';

        // Retrieve the specific order
        $order = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $order_id));
        $statuses = ['new', 'viewed', 'shipped', 'canceled', 'delivered'];




        if ($order) {
            // Check if the form has been submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Update the order details
                $full_name = sanitize_text_field($_POST['full_name']);
                $phone_number = sanitize_text_field($_POST['phone_number']);
                $email = sanitize_email($_POST['email']);
                $address = sanitize_text_field($_POST['address']);
                $note = sanitize_text_field($_POST['note']);
                $order_status = sanitize_text_field($_POST['order_status']);


                $wpdb->update(
                    $table_name,
                    [
                        'full_name' => $full_name,
                        'phone_number' => $phone_number,
                        'email' => $email,
                        'address' => $address,
                        'note' => $note,
                        'order_status' => $order_status,
                    ],
                    ['id' => $order_id]
                );

                echo '<script>
                alert("Order updated successfully!");
                window.location.href = "' . admin_url('admin.php?page=techbook_orders_page') . '";
            </script>';
            }
?>

            <div class="wrap-detail">
                <h1>Edit Order Details</h1>
                <form method="POST">
                    <table class="form-table">
                        <tr>
                            <th>Full Name</th>
                            <td><input type="text" name="full_name" value="<?php echo esc_attr($order->full_name); ?>" /></td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td><input type="text" name="phone_number" value="<?php echo esc_attr($order->phone_number); ?>" /></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><input type="email" name="email" value="<?php echo esc_attr($order->email); ?>" /></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><input type="text" name="address" value="<?php echo esc_attr($order->address); ?>" /></td>
                        </tr>
                        <tr>
                            <th>Note</th>
                            <td><input type="text" name="note" value="<?php echo esc_attr($order->note); ?>" /></td>
                        </tr>
                        <tr>
                            <th>Products</th>
                            <td>
                                <?php $products = json_decode($order->products, true);
                                if ($products) {
                                    echo '<ul class="product-list-display">';
                                    foreach ($products as $product) {
                                        echo '<li>';
                                        echo '<span class="product-name">' . esc_html($product['name']) . '</span>';

                                        if (isset($product['priceTypes']) && is_array($product['priceTypes'])) {
                                            echo '<ul class="price-type-list">';
                                            foreach ($product['priceTypes'] as $priceType) {
                                                echo '<li>';
                                                echo '<span class="product-price">' . number_format($priceType['price'], 2) . ' $</span> ';
                                                echo '<span class="product-quantity">x ' . intval($priceType['quantity']) . '</span> ';
                                                echo '<span class="price-type">';
                                                if ($priceType['priceType'] === 'price_ebook') {
                                                    echo 'Price Ebook';
                                                } elseif ($priceType['priceType'] === 'price_print') {
                                                    echo 'Price Print';
                                                } else {
                                                    echo esc_html($priceType['priceType']);
                                                }
                                                echo '</span>';
                                                echo '</li>';
                                            }
                                            echo '</ul>';
                                        }
                                        echo '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo '<p>No products found.</p>';
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Total Amount</th>
                            <td><span class="total-amount-display"><?php echo number_format($order->total_amount, 2); ?></span></td>
                        </tr>

                        <tr>
                            <th>Order Status</th>
                            <td>
                                <select name="order_status">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?php echo esc_attr($status); ?>" <?php selected($order->order_status, $status); ?>>
                                            <?php echo ucfirst(esc_html($status)); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td><?php echo esc_html($order->created_at); ?></td>
                        </tr>
                    </table>
                    <p>
                        <input type="submit" value="Update" class="button button-primary" />
                        <a href="<?php echo admin_url('admin.php?page=techbook_orders_page'); ?>" class="button">Back to Orders</a>
                    </p>
                </form>
            </div>
        <?php
        } else {
            echo '<div class="wrap"><h1>Order not found</h1></div>';
        }
    } else {
        $table_name = $wpdb->prefix . 'techbook_order';
        $items_per_page = 10;
        $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $offset = ($current_page - 1) * $items_per_page;

        // Get total number of orders
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

        // Retrieve orders for the current page, ordered by id in descending order
        $orders = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY id DESC LIMIT %d OFFSET %d", $items_per_page, $offset));

        ?>
        <div class="wrap">
            <h1>Orders</h1>
            <div class="table-container">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>

                            <th>Full Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>


                            <th>Total Amount</th>
                            <th>Order Status</th>
                            <th>Created At</th>
                            <th>Note</th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($orders): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>

                                    <td><?php echo esc_html($order->full_name); ?></td>
                                    <td><?php echo esc_html($order->phone_number); ?></td>
                                    <td><?php echo esc_html($order->email); ?></td>
                                    <!-- <td class="product-cell">
                                        <ul class="product-list">
                                            <?php
                                            $products = json_decode($order->products, true);
                                            if ($products) {
                                                foreach ($products as $product) {
                                                    echo '<li>' . esc_html($product['product_name']) . ' - SL: ' . intval($product['quantity']) . '</li>';
                                                }
                                            } else {
                                                echo '<li>No products found.</li>';
                                            }
                                            ?>
                                        </ul>
                                    </td> -->
                                    <td><?php echo esc_html($order->total_amount); ?></td>
                                    <td><?php echo esc_html($order->order_status); ?></td>
                                    <td><?php echo esc_html($order->created_at); ?></td>
                                    <td><?php echo esc_html($order->note); ?></td>
                                    <td><a href="<?php echo admin_url('admin.php?page=techbook_orders_page&order_id=' . $order->id); ?>" class="button">Detail</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">No orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php
                // Display pagination if necessary
                $total_pages = ceil($total_items / $items_per_page);

                if ($total_pages > 1) {
                    $page_links = paginate_links(array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => __('&laquo;'),
                        'next_text' => __('&raquo;'),
                        'total' => $total_pages,
                        'current' => $current_page,
                        'type' => 'array', // This outputs the links as an array
                    ));

                    if ($page_links) {
                        echo '<div class="techbook-pagination"><ul class="pagination-list">';
                        foreach ($page_links as $link) {
                            echo '<li class="pagination-item">' . $link . '</li>';
                        }
                        echo '</ul></div>';
                    }
                }
                ?>
            </div>
        </div>

<?php
    }
}
