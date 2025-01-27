<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'TECBOOK_PUBLISHERS_TABLE', $wpdb->prefix . 'tecbook_publishers' );

function get_all_publishers() {
    global $wpdb;
    $table_name = TECBOOK_PUBLISHERS_TABLE;
    $results = $wpdb->get_results( "SELECT * FROM $table_name" );
    return $results;
}

function get_publisher_by_id( $publisher_id ) {
    global $wpdb;
    $table_name = TECBOOK_PUBLISHERS_TABLE;
    $publisher_id = intval( $publisher_id );
    $query = $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $publisher_id );
    $publisher = $wpdb->get_row( $query );
    return $publisher;
}

// Function to prepare publisher data
function prepare_publisher_data( $publisher ) {
    if ( $publisher ) {
        $data = array(
            'id'                        => intval( $publisher->id ),
            'publisher_code'            => ! empty( $publisher->publisherCode ) ? $publisher->publisherCode : '',
            'english_title'             => ! empty( $publisher->englishTitle ) ? $publisher->englishTitle : '',
            'english_description'       => ! empty( $publisher->englishDescription ) ? $publisher->englishDescription : '',
            'vietnamese_description'    => ! empty( $publisher->vietnameseDescription ) ? $publisher->vietnameseDescription : '',
            'abstract'                  => ! empty( $publisher->abstract ) ? $publisher->abstract : '',
            'reference'                 => ! empty( $publisher->reference ) ? $publisher->reference : '',
            'keyword'                   => ! empty( $publisher->keyword ) ? $publisher->keyword : '',
            'related_ics_code'          => ! empty( $publisher->relatedICSCode ) ? $publisher->relatedICSCode : '',
            'avatarPath'                => ! empty( $publisher->avatarPath ) ? $publisher->avatarPath : '',
            'featured'                  => intval( $publisher->featured ),
        );
    } else {
        // Default values when publisher is not found
        $data = array(
            'id'                        => 0,
            'publisher_code'            => '',
            'english_title'             => '',
            'english_description'       => '',
            'vietnamese_description'    => '',
            'abstract'                  => '',
            'reference'                 => '',
            'keyword'                   => '',
            'related_ics_code'          => '',
            'avatarPath'                => '',
            'featured'                  =>0,
        );
    }

    return $data;
}


// Hàm xử lý AJAX
function filter_publishers_by_letter() {
    global $wpdb;
    $letter = isset($_POST['letter']) ? $_POST['letter'] : '';

    $table_name = $wpdb->prefix . 'tecbook_publishers';
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT ID, englishTitle FROM $table_name WHERE englishTitle LIKE %s",
        $letter . '%'
    ));

    // Đổ dữ liệu ra ngoài theo định dạng HTML
    if (!empty($results)) {
        foreach ($results as $publisher) {
            // Sử dụng home_url() để tạo liên kết chi tiết
            $detail_url = home_url('/detail/publisher-' . $publisher->ID . '/');
            echo '<li><a href="' . esc_url($detail_url) . '">' . esc_html($publisher->englishTitle) . '</a><span class="arrow">&rsaquo;</span></li>';
        }
    } else {
        echo '<li>No Publishers</li>';
    }

    wp_die();
}
add_action('wp_ajax_filter_publishers_by_letter', 'filter_publishers_by_letter');
add_action('wp_ajax_nopriv_filter_publishers_by_letter', 'filter_publishers_by_letter');

// Truyền biến AJAX URL
function enqueue_custom_scripts() {
    wp_enqueue_script('custom-js', get_template_directory_uri() . '/template-parts/techbook/Home/index.js', array('jquery'), null, true);
    wp_localize_script('custom-js', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');




function load_publishers_by_letter() {
    global $wpdb;

    $letter = isset($_POST['letter']) ? $_POST['letter'] : '';
    $page = isset($_POST['page']) ? intval($_POST['page']) : null;

    $table_name = $wpdb->prefix . 'tecbook_publishers';

    if ($letter !== '') {
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name WHERE englishTitle LIKE %s ORDER BY englishTitle ASC",
            $letter . '%'
        ));

        if (!empty($results)) {
            foreach ($results as $organization) {
                include locate_template('template-parts/techbook/product-list/product-list-publisher1.php');
            }
        } else {
            echo '<p>No Publishers</p>';
        }

    } else {
        $page = $page ? $page : 1;
        $items_per_page = 10;
        $offset = ($page - 1) * $items_per_page;

        $total_products = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        $total_pages = ceil($total_products / $items_per_page);

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name ORDER BY englishTitle ASC LIMIT %d OFFSET %d",
            $items_per_page, $offset
        ));

        if (!empty($results)) {
            foreach ($results as $organization) {
                include locate_template('template-parts/techbook/product-list/product-list-publisher1.php');
            }
        } else {
            echo '<p>No Publishers</p>';
        }

        if ($total_pages > 1) {
            echo '<div class="pagination-controls">';
            echo '<button class="page-num ' . ($page == 1 ? 'active' : '') . '" data-page="1">1</button>';

            if ($page > 3) {
                echo '<span>...</span>';
            }
            for ($i = max(2, $page - 1); $i <= min($total_pages - 1, $page + 1); $i++) {
                $active = $i == $page ? 'active' : '';
                echo '<button class="page-num ' . $active . '" data-page="' . $i . '">' . $i . '</button>';
            }

            if ($page < $total_pages - 2) {
                echo '<span>...</span>';
            }
            if ($total_pages > 1) {
                echo '<button class="page-num ' . ($page == $total_pages ? 'active' : '') . '" data-page="' . $total_pages . '">' . $total_pages . '</button>';
            }

            echo '</div>';
        }
    }

    wp_die();
}
add_action('wp_ajax_load_publishers_by_letter', 'load_publishers_by_letter');
add_action('wp_ajax_nopriv_load_publishers_by_letter', 'load_publishers_by_letter');

