<?php

function techbook_books_tag_page() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'tecbook_books_cache';
    $filter = isset($_GET['filter']) ? sanitize_text_field($_GET['filter']) : 'newArrival';

    $valid_filters = ['newArrival', 'bestSellers', 'specialOffer', 'featured'];
    if (!in_array($filter, $valid_filters)) {
        $filter = 'newArrival';
    }

    $column = esc_sql($filter);

    if (isset($_POST['cancel_id'])) {
        $cancel_id = intval($_POST['cancel_id']);
        $wpdb->update(
            $table_name,
            [$column => 0],
            ['id' => $cancel_id],
            ['%d'],
            ['%d']
        );
        echo '<script>alert("Đã hủy trạng thái thành công cho ID: ' . esc_html($cancel_id) . '");</script>';
    }

    $results = $wpdb->get_results(
        $wpdb->prepare("SELECT id, title FROM $table_name WHERE `$column` = %d", 1),
        ARRAY_A
    );

    echo '<div class="wrap">';
    echo '<h1>Books</h1>';
    echo '<div style="margin-bottom: 20px;">';
    foreach ($valid_filters as $valid_filter) {
        $button_class = ($filter === $valid_filter) ? 'button button-primary' : 'button';
        $button_label = ucwords(str_replace('_', ' ', $valid_filter));
        echo '<a href="?page=techbook_books_tag_page&filter=' . esc_attr($valid_filter) . '" class="' . esc_attr($button_class) . '">' . esc_html($button_label) . '</a> ';
    }
    echo '</div>';

    if (!empty($results)) {
        echo '<form method="post">';
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Title</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . esc_html($row['id']) . '</td>';
            echo '<td>' . esc_html($row['title']) . '</td>';
            echo '<td>';
            echo '<button type="submit" name="cancel_id" value="' . esc_attr($row['id']) . '" class="button">Cancel</button>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</form>';
    } else {
        echo '<p>Không có bản ghi nào có giá trị <strong>1</strong> trong cột "' . esc_html($filter) . '".</p>';
    }

    echo '</div>';
}







