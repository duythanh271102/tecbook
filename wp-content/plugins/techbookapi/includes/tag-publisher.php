<?php

function techbook_publishers_tag_page() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'tecbook_publishers';
    $filter = 'featured'; 
    if (isset($_POST['cancel_id'])) {
        $cancel_id = intval($_POST['cancel_id']);
        $wpdb->update(
            $table_name,
            [$filter => 0], 
            ['id' => $cancel_id], 
            ['%d'], 
            ['%d']  
        );
        echo '<script>alert("Đã hủy trạng thái featured thành công cho ID: ' . esc_html($cancel_id) . '");</script>';
    }

    
    $counts = $wpdb->get_results(
        "SELECT `$filter` AS value, COUNT(*) AS count FROM $table_name GROUP BY `$filter`",
        ARRAY_A
    );

    $zero_count = 0;
    $one_count = 0;
    foreach ($counts as $count) {
        if ($count['value'] == 0) {
            $zero_count = $count['count'];
        } elseif ($count['value'] == 1) {
            $one_count = $count['count'];
        }
    }

    // Lấy danh sách các bản ghi có `featured = 1`
    $results = $wpdb->get_results(
        $wpdb->prepare("SELECT id, englishTitle FROM $table_name WHERE `$filter` = %d", 1),
        ARRAY_A
    );

    echo '<div class="wrap">';
    echo '<h1>Publishers</h1>';


    // Hiển thị bảng dữ liệu
    if (!empty($results)) {
        echo '<form method="post">';
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>English Title</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . esc_html($row['id']) . '</td>';
            echo '<td>' . esc_html($row['englishTitle']) . '</td>';
            echo '<td>';
            echo '<button type="submit" name="cancel_id" value="' . esc_attr($row['id']) . '" class="button">Cancel</button>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</form>';
    } else {
        echo '<p>Không có bản ghi nào có giá trị <strong>1</strong> trong cột "featured".</p>';
    }

    echo '</div>';
}

