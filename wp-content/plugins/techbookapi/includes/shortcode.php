<?php
function techbookapi_display_subcategories_shortcode($atts) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'techbookapi_items';

    $items = $wpdb->get_results("SELECT * FROM $table_name");

    $output = '<div class="techbookapi-subcategories">';
    foreach ($items as $item) {
        $params = [];
        parse_str($item->api_params, $params);

        $subcategories = techbookapi_get_subcategories($item->api_url, $params, $item->api_params);

        $output .= '<h3>Subcategories from API: ' . esc_html($item->name) . '</h3>';
        if (!empty($subcategories)) {
            $output .= '<ul>';
            foreach ($subcategories as $subcategory) {
                $output .= '<li>' . esc_html($subcategory['name']) . '</li>'; // Tùy chỉnh theo cấu trúc dữ liệu trả về từ API
            }
            $output .= '</ul>';
        } else {
            $output .= '<p>No subcategories found.</p>';
        }
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('techbookapi_display_subcategories', 'techbookapi_display_subcategories_shortcode');
