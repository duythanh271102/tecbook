<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'TECBOOK_BOOKS_TABLE', $wpdb->prefix . 'tecbook_books_cache' );

function get_all_products() {
    global $wpdb;
    $table_name = TECBOOK_BOOKS_TABLE;
    $results = $wpdb->get_results( "SELECT * FROM $table_name" );
    return $results;
}

function get_product_by_id( $product_id ) {
    global $wpdb;
    $table_name = TECBOOK_BOOKS_TABLE;
    $product_id = intval( $product_id );
    $query = $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $product_id );
    $product = $wpdb->get_row( $query );
    return $product;
}

// Function to prepare product data
function prepare_product_data( $product ) {
    $price_factor = floatval(get_option('techbookapi_price_factor', 1));

    if ( $product ) {
        $data = array(
            'id'                    => intval( $product->id ),
            'title'                 => ! empty( $product->title ) ? $product->title : '',
            'author'                => ! empty( $product->author ) ? $product->author : '',
            'edition'               => ! empty( $product->edition ) ? $product->edition : '',
            'document_status'       => ! empty( $product->documentStatus ) ? $product->documentStatus : '',
            'publication_date'      => !empty($product->publicationDate) ? $product->publicationDate : '',
            'publisher'             => ! empty( $product->publisher ) ? $product->publisher : '',
            'doi'                   => ! empty( $product->doi ) ? $product->doi : '',
            'page'                  => ! empty( $product->page ) ? intval( $product->page ) : '',
            'isbn'                  => ! empty( $product->isbn ) ? $product->isbn : '',
            'subjects_code'         => ! empty( $product->subjectsCode ) ? $product->subjectsCode : '',
            'subjects'              => ! empty( $product->subjects ) ? $product->subjects : '',
            'abstract'              => ! empty( $product->abstract ) ? $product->abstract : '',
            'keywords' => (!empty($product->keywords) && $product->keywords !== '0' && $product->keywords !== '0.000000') 
                                        ? explode(',', $product->keywords) 
                                        : array(''),

            'price_print'           => ! empty( $product->pricePrint ) ? floatval( $product->pricePrint ) * $price_factor : 0,
            'price_ebook'           => ! empty( $product->priceeBook ) ? floatval( $product->priceeBook )* $price_factor : 0,
            'preview_path'          => ! empty( $product->previewPath ) ? $product->previewPath : '',
            'full_content_path'     => ! empty( $product->fullContentBookPath ) ? $product->fullContentBookPath : '',
            'created_date'          => ! empty( $product->createdDate ) ? date( 'Y-m-d', strtotime( $product->createdDate ) ) : '',
            'updated_date'          => ! empty( $product->updatedDate ) ? date( 'Y-m-d', strtotime( $product->updatedDate ) ) : '',
            'deleted'               => isset( $product->deleted ) ? intval( $product->deleted ) : 0,
            'new_arrival'           => isset( $product->newArrival ) ? intval( $product->newArrival ) : 0,
            'best_sellers'          => isset( $product->bestSellers ) ? intval( $product->bestSellers ) : 0,
            'is_free'               => isset( $product->isFree ) ? intval( $product->isFree ) : 0,
        );
    } else {
        // Default values when product is not found
        $data = array(
            'id'                    => 0,
            'title'                 => '',
            'author'                => '',
            'edition'               => '',
            'document_status'       => '',
            'publication_date'      => '',
            'publisher'             => '',
            'doi'                   => '',
            'page'                  => '',
            'isbn'                  => '',
            'subjects_code'         => '',
            'subjects'              => '',
            'abstract'              => '',
            'keywords'              => array( '' ),
            'price_print'           => '',
            'price_ebook'           => '',
            'preview_path'          => '',
            'full_content_path'     => '',
            'created_date'          => '',
            'updated_date'          => '',
            'deleted'               => 0,
            'new_arrival'           => 0,
            'best_sellers'          => 0,
            'is_free'               => 0,
        );
    }

    return $data;
}

function get_books_by_ids() {
    global $wpdb;

    if (!isset($_POST['productIds']) || !is_array($_POST['productIds'])) {
        wp_send_json_error('No product IDs or invalid data.');
    }

    $product_ids = array_map('intval', $_POST['productIds']);

    if (empty($product_ids)) {
        wp_send_json_error('Product ID list is empty.');
    }

    $placeholders = implode(',', array_fill(0, count($product_ids), '%d'));

    $price_factor = floatval(get_option('techbookapi_price_factor', 1));

    $query_books = $wpdb->prepare(
        "SELECT * FROM wp_tecbook_books_cache WHERE id IN ($placeholders)",
        ...$product_ids
    );
    $books = $wpdb->get_results($query_books);


    // var_dump($books);
    // die();



    $query_publisher = $wpdb->prepare(
        "SELECT * FROM wp_tecbook_standards WHERE id IN ($placeholders)",
        ...$product_ids
    );
    $publisher = $wpdb->get_results($query_publisher); 
    
    if (empty($books) && empty($publisher)) {
        wp_send_json_error(array('message' => 'No books or publishers found.'));
    }

    foreach ($books as $book) {
        if (isset($book->pricePrint)) {
            $book->pricePrint = round($book->pricePrint * $price_factor, 2);
        }
        if (isset($book->priceeBook)) {
            $book->priceeBook = round($book->priceeBook * $price_factor, 2);
        }   
    }

    foreach ($publisher as $pub) {
        if (isset($pub->printPrice)) {
            $pub->printPrice = round($pub->printPrice * $price_factor, 2);
        }
        if (isset($pub->ebookPrice)) {
            $pub->ebookPrice = round($pub->ebookPrice * $price_factor, 2);
        }
    }

    $response = array(
        'success' => true,
        'books' => $books,         
        'standardBooks' => $publisher 
    );

    wp_send_json_success($response); 

    wp_die(); // AJAX end
}

add_action('wp_ajax_get_books_by_ids', 'get_books_by_ids');
add_action('wp_ajax_nopriv_get_books_by_ids', 'get_books_by_ids');

// Truyền biến AJAX URL
function enqueue_custom_scripts2() {
    wp_enqueue_script('custom-js', get_template_directory_uri() . '/template-parts/techbook/wishlist/index.js', array('jquery'), null, true);
    wp_localize_script('custom-js', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts2');


// function get_books_by_ids() {
//     global $wpdb;

//     if (!isset($_POST['productIds']) || !is_array($_POST['productIds'])) {
//         wp_send_json_error('No product IDs or invalid data.');
//     }

//     $product_ids = array_map('intval', $_POST['productIds']);

//     if (empty($product_ids)) {
//         wp_send_json_error('Product ID list is empty.');
//     }

//     $placeholders = implode(',', array_fill(0, count($product_ids), '%d'));

//     $price_factor = floatval(get_option('techbookapi_price_factor', 1));

//     $query_books = $wpdb->prepare(
//         "SELECT * FROM wp_tecbook_books_cache WHERE id IN ($placeholders)",
//         ...$product_ids
//     );
//     $books = $wpdb->get_results($query_books);

//     $query_publisher = $wpdb->prepare(
//         "SELECT * FROM wp_tecbook_standards WHERE id IN ($placeholders)",
//         ...$product_ids
//     );
//     $publisher = $wpdb->get_results($query_publisher); 

//     if (empty($books) && empty($publisher)) {
//         wp_send_json_error(array('message' => 'No books or publishers found.'));
//     }

//     foreach ($books as $book) {
//         if (isset($book->pricePrint)) {
//             $book->pricePrint = round($book->pricePrint * $price_factor, 2);
//         }
//         if (isset($book->priceeBook)) {
//             $book->priceeBook = round($book->priceeBook * $price_factor, 2);
//         }   
//     }

//     foreach ($publisher as $pub) {
//         if (isset($pub->printPrice)) {
//             $pub->printPrice = round($pub->printPrice * $price_factor, 2);
//         }
//         if (isset($pub->ebookPrice)) {
//             $pub->ebookPrice = round($pub->ebookPrice * $price_factor, 2);
//         }
//     }

//     $response = array(
//         'success' => true,
//         'books' => $books,         
//         'standardBooks' => $publisher 
//     );

//     wp_send_json_success($response); 

//     wp_die(); // AJAX end
// }

// add_action('wp_ajax_get_books_by_ids', 'get_books_by_ids');
// add_action('wp_ajax_nopriv_get_books_by_ids', 'get_books_by_ids');

// // Truyền biến AJAX URL
// function enqueue_custom_scripts2() {
//     wp_enqueue_script('custom-js', get_template_directory_uri() . '/template-parts/techbook/wishlist/index.js', array('jquery'), null, true);
//     wp_localize_script('custom-js', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
// }
// add_action('wp_enqueue_scripts', 'enqueue_custom_scripts2');


// Hàm xử lý AJAX
function save_books_to_cache() {

    if (isset($_POST['books'])) {
        $books = $_POST['books'];
        $result = hte_save_books_to_cache($books);

        if (!empty($result['failed'])) {
            wp_send_json_error([
                'message' => 'Lưu thất bại với một số bản ghi.',
                'result' => $result
            ]);
        } else {
            if (!empty($result['duplicate_id'])) {
                wp_send_json_success([
                    'message' => 'Dữ liệu đã được lưu thành công, nhưng một số ID đã tồn tại và không được lưu: ' . implode(', ', $result['duplicate_id']),
                    'result' => $result
                ]);
            } else {
              
                wp_send_json_success([
                    'message' => 'Dữ liệu đã được lưu thành công.',
                    'result' => $result
                ]);
            }
        }
    } else {
        wp_send_json_error([
            'message' => 'Không có dữ liệu để lưu.'
        ]);
    }

    wp_die();
}


add_action('wp_ajax_save_books_to_cache', 'save_books_to_cache');
add_action('wp_ajax_nopriv_save_books_to_cache', 'save_books_to_cache');
