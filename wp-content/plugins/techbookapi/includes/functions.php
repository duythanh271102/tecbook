<?php

// Đăng ký cài đặt chung cho TokenKey
function techbookapi_register_settings() {
    register_setting('techbookapi_options_group', 'techbookapi_price_factor');
}
add_action('admin_init', 'techbookapi_register_settings');

// Hàm thêm mới item vào database
function techbookapi_add_item() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'techbookapi_items';
    
    if (isset($_POST['techbookapi_add_item'])) {
        $name = sanitize_text_field($_POST['name']);
        $api_url = sanitize_text_field($_POST['api_url']);
        $input_body = ($_POST['input_body']); // Lưu dưới dạng string

        // Thêm vào database
        $wpdb->insert($table_name, array(
            'name' => $name,
            'api_url' => $api_url,
            'api_params' => $input_body
        ));

        echo '<div class="updated"><p>Item added successfully!</p></div>';
    }
}

// Function xử lý xóa item
function techbookapi_delete_item($item_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'techbookapi_items';

    $wpdb->delete($table_name, array('id' => intval($item_id)));
}

// Hàm cập nhật item
function techbookapi_update_item() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'techbookapi_items';

    if (isset($_POST['techbookapi_update_item'])) {
        $item_id = absint($_POST['item_id']);
        $name = sanitize_text_field($_POST['name']);
        $api_url = sanitize_text_field($_POST['api_url']);
        $input_body = ($_POST['input_body']); // Lưu dưới dạng string

        // Cập nhật vào database
        $wpdb->update($table_name, array(
            'name' => $name,
            'api_url' => $api_url,
            'api_params' => $input_body
        ), array('id' => $item_id));

        echo '<div class="updated"><p>Item updated successfully!</p></div>';

        // Sau khi cập nhật, gọi hàm sync products
        techbookapi_sync_products($api_url, $input_body);
    }
}

// Hàm gọi API để đồng bộ products với các param từ item
// function techbookapi_sync_products($api_url, $input_body) {
//     $token_key = get_option('techbookapi_token_key');
//     $body_json = json_decode(wp_unslash($input_body));
//     $token_key = esc_attr(get_option('techbookapi_token_key'));
//     $body_json->tokenKey = $token_key;
    
//     $args = array(
//         'body'    => json_encode($body_json),
//         'headers' => array(
//             'Authorization' => 'Bearer ' . $token_key,
//             'Content-Type'  => 'application/json',
//         ),
//         'method'  => 'POST'
//     );
    

//     // Gọi API
//     $response = wp_remote_post($api_url, $args);
//     $response_json = json_decode($response["body"]);
    
//     if($response_json['code'] !== 200){
//         echo $response_json['message'];
//     }else{
//         echo'<pre>'; 
//         var_dump($response_json['data']);
//         echo'</pre>'; 
//     }
    
//     die('*-*-*-*-');
//     if (is_wp_error($response)) {
//         $error_message = $response->get_error_message();
//         echo "<div class='error'><p>Failed to sync products: $error_message</p></div>";
//     } else {
//         echo '<div class="updated"><p>Products synced successfully!</p></div>';
//     }
// }

// Hàm để lưu kết quả vào bảng tecbook_books_cache
function hte_save_books_to_cache($books) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_books_cache';

    // Mảng kết quả để lưu trữ trạng thái của từng bản ghi
    $result = [
        'saved' => [],
        'duplicate_id' => [],
        'failed' => []
    ];

    foreach ($books as $book) {
        $book = (array)$book;

        // Kiểm tra xem `id` đã tồn tại chưa
        $existing_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE id = %d", $book['id']));

        if (!$existing_id) {
            // Chuẩn bị dữ liệu để lưu
            $data = [
                'id' => intval($book['id'] ?? 0),
                'title' => $book['title'] ?? '',
                'author' => $book['author'] ?? '',
                'edition' => $book['edition'] ?? '',
                'documentStatus' => $book['documentStatus'] ?? '',
                'publicationDate' => $book['publicationDate'] ?? '',
                'publisher' => $book['publisher'] ?? '',
                'doi' => $book['doi'] ?? '',
                'page' => intval($book['page'] ?? 0),
                'isbn' => $book['isbn'] ?? '',
                'subjectsCode' => $book['subjectsCode'] ?? '',
                'subjects' => $book['subjects'] ?? '',
                'abstract' => $book['abstract'] ?? '',
                'keywords' => $book['keywords'] ?? '',
                'pricePrint' => floatval($book['pricePrint'] ?? 0),
                'priceeBook' => floatval($book['priceeBook'] ?? 0),
                'previewPath' => $book['previewPath'] ?? '',
                'fullContentBookPath' => $book['fullContentBookPath'] ?? '',
                'createdDate' => isset($book['createdDate']) ? date('Y-m-d H:i:s', strtotime($book['createdDate'])) : current_time('mysql'),
                'updatedDate' => isset($book['updatedDate']) ? date('Y-m-d H:i:s', strtotime($book['updatedDate'])) : current_time('mysql'),
                'deleted' => 0,
                'newArrival' => 0,
                'bestSellers' => 0,
                'isFree' => 0,
                'specialOffer' => 0,
                'featured' => 0,
            ];

            // Thử chèn bản ghi mới
            $insert_result = $wpdb->insert($table_name, $data, [
                '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s',
                '%s', '%s', '%s', '%s', '%f', '%f', '%s', '%s', '%s', '%s',
                '%d', '%d', '%d', '%d', '%d', '%d'
            ]);

            // Kiểm tra kết quả chèn dữ liệu
            if ($insert_result !== false) {
                $result['saved'][] = $book['id']; 
            } else {
                // Thêm chi tiết lỗi cho bản ghi thất bại
                $result['failed'][] = [
                    'id' => $book['id'],
                    'error' => $wpdb->last_error,
                    'query' => $wpdb->last_query
                ];
            }
        } else {
            $result['duplicate_id'][] = $book['id']; 
        }
    }

    return $result;
}





function hte_get_books_from_cache($args = array()) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_books_cache';

    // Mặc định lấy tất cả các sách
    $default_args = array(
        'limit' => 10,        // Giới hạn số lượng kết quả, mặc định là 10
        'offset' => 0,        // Bắt đầu từ đâu
        'orderby' => 'createdDate',  // Sắp xếp theo
        'order' => 'DESC',    // Thứ tự sắp xếp
        'deleted' => 0        // Lấy sách chưa bị xóa
    );

    // Hợp nhất các tham số truyền vào với các tham số mặc định
    $args = wp_parse_args($args, $default_args);

    // Truy vấn dữ liệu từ bảng tecbook_books_cache
    $query = $wpdb->prepare(
        "SELECT * FROM $table_name 
        WHERE deleted = %d 
        ORDER BY {$args['orderby']} {$args['order']} 
        LIMIT %d OFFSET %d",
        $args['deleted'],
        $args['limit'],
        $args['offset']
    );

    // Lấy kết quả từ database
    $results = $wpdb->get_results($query, ARRAY_A);

    // Trả về kết quả
    return $results;
}





// Hàm để lưu kết quả vào bảng tecbook_publishers

function hte_save_publishers_to_cache($publishers) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_publishers';

    $result = [
        'saved' => [],
        'duplicate_id' => [],
        'failed' => []
    ];

    foreach ($publishers as $publisher) {
        $publisher = (array)$publisher;

        $existing_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE id = %d", $publisher['id']));

        if (!$existing_id) {
            $data = [
                'id' => intval($publisher['id'] ?? 0),
                'publisherCode' => $publisher['publisherCode'] ?? '',
                'englishTitle' => $publisher['englishTitle'] ?? '',
                'englishDescription' => $publisher['englishDescription'] ?? '',
                'vietnameseDescription' => $publisher['vietnameseDescription'] ?? '',
                'abstract' => $publisher['abstract'] ?? '',
                'reference' => $publisher['reference'] ?? '',
                'keyword' => $publisher['keyword'] ?? '',
                'relatedICSCode' => $publisher['relatedICSCode'] ?? '',
                'avatarPath' => $publisher['avatarPath'] ?? '',
                'featured' => 0,
            ];

            $insert_result = $wpdb->insert($table_name, $data, [
                '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d'
            ]);

            if ($insert_result !== false) {
                $result['saved'][] = $publisher['id'];
            } else {
                $result['failed'][] = [
                    'id' => $publisher['id'],
                    'error' => $wpdb->last_error,
                    'query' => $wpdb->last_query
                ];
            }
        } else {
            $result['duplicate_id'][] = $publisher['id'];
        }
    }

    return $result;
}



function hte_save_standards_to_cache($standards) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_standards';

    // Mảng kết quả để lưu trữ trạng thái của từng bản ghi
    $result = [
        'saved' => [],
        'duplicate_id' => [],
        'failed' => []
    ];

    foreach ($standards as $standard) {
        $standard = (array)$standard;

        // Kiểm tra xem `id` đã tồn tại chưa
        $existing_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE id = %d", $standard['id']));

        if (!$existing_id) {
            // Chuẩn bị dữ liệu để lưu
            $data = [
                'id' => intval($standard['id'] ?? 0),
                'idProduct' => $standard['idProduct'] ?? '',
                'referenceNumber' => $standard['referenceNumber'] ?? '',
                'standardTitle' => $standard['standardTitle'] ?? '',
                'status' => $standard['status'] ?? '',
                'referencedStandards' => $standard['referencedStandards'] ?? '',
                'referencingStandards' => $standard['referencingStandards'] ?? '',
                'equivalentStandards' => $standard['equivalentStandards'] ?? '',
                'replace' => $standard['replace'] ?? '',
                'repalcedBy' => $standard['repalcedBy'] ?? '',
                'standardby' => $standard['standardby'] ?? '',
                'languages' => $standard['languages'] ?? '',
                'fullDescription' => $standard['fullDescription'] ?? '',
                'ebookPrice' => floatval($standard['ebookPrice'] ?? 0),
                'printPrice' => floatval($standard['printPrice'] ?? 0),
                'bothPrice' => floatval($standard['bothPrice'] ?? 0),
                'currency' => $standard['currency'] ?? '',
                'historicalEditions' => $standard['historicalEditions'] ?? '',
                'documentHistoryProductId' => $standard['documentHistoryProductId'] ?? '',
                'icsCode' => $standard['icsCode'] ?? '',
                'topics' => $standard['topics'] ?? '',
                'keyword' => $standard['keyword'] ?? '',
                'identicalStandards' => $standard['identicalStandards'] ?? '',
                'publishedDate' => isset($standard['publishedDate']) ? $standard['publishedDate'] : '',
                'pages' => intval($standard['pages'] ?? 0),
                'byTechnology' => $standard['byTechnology'] ?? '',
                'byIndustry' => $standard['byIndustry'] ?? '',
                'previewPath' => $standard['previewPath'] ?? '',
                'coverPath' => $standard['coverPath'] ?? '',
                'fullPath' => $standard['fullPath'] ?? '',
                'deleted' => 0,
                'newArrival' => 0,
                'bestSellers' => 0,
                'isFree' => 0,
                'specialOffer' => 0,
                'featured' => 0,
            ];

            // Thử chèn bản ghi mới
            $insert_result = $wpdb->insert($table_name, $data, [
                '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
                '%s', '%s', '%s', '%f', '%f', '%f', '%s', '%s', '%s', '%s',
                '%s','%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d',
                '%d', '%d', '%d', '%d', '%d'
            ]);

            // Kiểm tra kết quả chèn dữ liệu
            if ($insert_result !== false) {
                $result['saved'][] = $standard['id'];
            } else {
                // Thêm chi tiết lỗi cho bản ghi thất bại
                $result['failed'][] = [
                    'id' => $standard['id'],
                    'error' => $wpdb->last_error,
                    'query' => $wpdb->last_query
                ];
            }
        } else {
            $result['duplicate_id'][] = $standard['id'];
        }
    }

    return $result;
}





function hte_save_subjects_to_cache($subjects) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_subjects';

    foreach ($subjects as $subject) {
        $subject = (array)$subject;
        $wpdb->replace(
            $table_name,
            array(
                'id' => $subject['id'],  // ID from API
                'code' => $subject['code'],
                'subjects' => $subject['subjects'],
                'notes' => $subject['notes'],
            ),
            array('%d', '%s', '%s', '%s')
        );
    }
}


//icscode
function hte_save_ics_codes_to_cache($ics_codes) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_ics_codes';

    foreach ($ics_codes as $ics_code) {
        $ics_code = (array)$ics_code;

        $wpdb->replace(
            $table_name,
            array(
                'icsCode' => $ics_code['icsCode'],
                'nameInEnglish' => $ics_code['nameInEnglish'],
                'nameInVietnamese' => $ics_code['nameInVietnamese'],
                'ralatedToBookSubjects' => $ics_code['ralatedToBookSubjects'],
                'keyword' => $ics_code['keyword'],
                'fatherICSCode' => $ics_code['fatherICSCode'],
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s') 
        );
    }
}



function techbook_save_order_to_cache($orders) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'techbook_order';

    foreach ($orders as $order) {
        $order = (array)$order;

        $wpdb->replace(
            $table_name,
            array(
                'id' => $order['id'],
                'full_name' => $order['full_name'],
                'phone_number' => $order['phone_number'],
                'email' => $order['email'],
                'address' => $order['address'],
                'note' => $order['note'],
                'products' => json_encode($order['products']),  
                'total_amount' => $order['total_amount'],
                'created_at' => $order['created_at'],
                'order_status' => $order['order_status'],
            ),
            array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s')
        );
    }
}


function hte_save_industry_to_cache($industries) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_industry';  

    foreach ($industries as $industry) {
        $industry = (array)$industry;
        
        $wpdb->replace(
            $table_name,
            array(
                'subjectCode' => $industry['subjectCode'],  
                'icsCodeFather' => $industry['icsCodeFather'],  
                'englishTitle' => $industry['englishTitle'],  
                'vietnameseTitle' => $industry['vietnameseTitle'],  
                'relatedICSCode' => $industry['relatedICSCode'],  
                'keyword' => $industry['keyword'],  
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s')  
        );
    }
}


function hte_save_topics_to_cache($topics) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_topics';  

    if (!empty($topics)) {
        foreach ($topics as $topic) {
            $topic = (array)$topic;
            if (isset($topic['code']) && !empty($topic['code']) && isset($topic['title']) && !empty($topic['title'])) {
                
            
                $wpdb->replace(
                    $table_name,
                    array(
                        'code' => $topic['code'],  
                        'title' => $topic['title'],  
                    ),
                    array('%s', '%s')  
                );
            } else {
                error_log('Dữ liệu không hợp lệ: ' . var_export($topic, true));
            }
        }
    }
}









