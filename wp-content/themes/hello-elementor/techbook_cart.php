<?php

function save_order_to_database() { 
    
    $data = $_POST;
    if (!isset($data['cartItems']) || !isset($data['fullname']) || !isset($data['phone']) || !isset($data['email']) || !isset($data['address'])) {
        wp_send_json_error(['status' => 'error', 'message' => 'Thiếu thông tin yêu cầu.']);
        wp_die();
    }

    $cartItems = $data['cartItems']; 
    $products = json_encode($cartItems);

    $full_name = sanitize_text_field($data['fullname']);
    $phone_number = sanitize_text_field($data['phone']);
    $email = sanitize_email($data['email']);
    $address = sanitize_text_field($data['address']);
    $note = isset($data['note']) ? sanitize_textarea_field($data['note']) : '';
    $total_amount = isset($data['total_amount']) ? floatval($data['total_amount']) : 0;
    $order_status = isset($data['order_status']) ? sanitize_text_field($data['order_status']) : 'new';
    $created_at = current_time('mysql'); 

    global $wpdb;
    $table_name = $wpdb->prefix . 'techbook_order';

    // Insert data to table
    $result = $wpdb->insert(
        $table_name,
        [
            'full_name' => $full_name,
            'phone_number' => $phone_number,
            'email' => $email,
            'address' => $address,
            'note' => $note,
            'products' => $products,
            'total_amount' => $total_amount,
            'created_at' => $created_at,
            'order_status' => $order_status,
        ], 
        [
            '%s', // full_name (string)
            '%s', // phone_number (string)
            '%s', // email (string)
            '%s', // address (string)
            '%s', // note (string)
            '%s', // products (JSON string)
            '%f', // total_amount (float)
            '%s', // created_at (MySQL date format string)
            '%s', // order_status (string)
        ]
    );

    // Kiểm tra kết quả lưu vào database
    if ($result) {
        wp_send_json_success(['status' => 'success', 'message' => 'Order created successfully.']);
    } else {
        wp_send_json_error(['status' => 'error', 'message' => 'Failed to create order.']);
    }

    wp_die(); 
}

add_action('wp_ajax_save_order_to_database', 'save_order_to_database');
add_action('wp_ajax_nopriv_save_order_to_database', 'save_order_to_database');