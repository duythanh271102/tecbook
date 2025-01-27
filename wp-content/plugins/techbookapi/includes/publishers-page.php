<?php

function techbook_publishers_page() {
    global $wpdb;

    // Kiểm tra nếu có tham số 'item_id' thì chuyển sang trang chi tiết
    if (isset($_GET['item_id'])) {
        echo hte_publisher_detail_page(intval($_GET['item_id']));
        return;
    }

    $tokenKey = get_api_token();
    $api_url = get_api_base_url() . '/Publishers/getpaging';
    $pageIndex = 1;
    $pageSize = 50;
    $publishers = [];

    // Lặp để lấy toàn bộ dữ liệu từ API
    while (true) {
        $body = json_encode(array(
            "tokenKey" => $tokenKey,
            "pageIndex" => $pageIndex,
            "pageSize" => $pageSize,
            "keyWord" => ""
        ));

        $response = wp_remote_post($api_url, array(
            'method'    => 'POST',
            'body'      => $body,
            'headers'   => array('Content-Type' => 'application/json'),
        ));

        if (is_wp_error($response)) {
            echo 'Có lỗi xảy ra: ' . $response->get_error_message();
            return;
        }

        $data = json_decode(wp_remote_retrieve_body($response));

        if (!isset($data->data->items) || empty($data->data->items)) {
            break;
        }

        // Lưu các mục vào mảng $publishers
        $publishers = array_merge($publishers, $data->data->items);

        // Kiểm tra nếu đã lấy hết dữ liệu
        if (count($data->data->items) < $pageSize) {
            break;
        }

        $pageIndex++;
    }

    if (!empty($publishers)) {
        hte_save_publishers_to_cache($publishers);
    }

    $search = isset($_GET['s']) ? trim($_GET['s']) : '';

    $current_page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
    $offset = ($current_page - 1) * $pageSize;

    // Xây dựng điều kiện WHERE cho truy vấn nếu có tham số tìm kiếm
    if (!empty($search)) {
        $search_sql = $wpdb->prepare("WHERE publisherCode LIKE %s", '%' . $wpdb->esc_like($search) . '%');
    } else {
        $search_sql = '';
    }

    $sql = "SELECT * FROM {$wpdb->prefix}tecbook_publishers $search_sql LIMIT %d OFFSET %d";
    $items = $wpdb->get_results($wpdb->prepare($sql, $pageSize, $offset));

    $totalRows_sql = "SELECT COUNT(*) FROM {$wpdb->prefix}tecbook_publishers $search_sql";
    $totalRows = $wpdb->get_var($totalRows_sql);
    $totalPages = ceil($totalRows / $pageSize);
    ?>
    <div class="wrap">
        <h1>Danh sách Nhà xuất bản</h1>

        <!-- Form tìm kiếm -->
        <form method="get" action="" class="search-form">
            <input type="hidden" name="page" value="techbook_publishers_page" />
            <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Tìm kiếm theo PublisherCode" class="search-input" />
            <input type="submit" value="Tìm kiếm" class="button search-button" />
        </form>


        <table class="wp-list-table widefat fixed striped table-view-list">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>PublisherCode</th>
                    <th>EnglishTitle</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($items)) : ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo esc_html($item->id); ?></td>
                        <td><a href="?page=techbook_publishers_page&item_id=<?php echo esc_html($item->id); ?>"><?php echo esc_html($item->publisherCode); ?></a></td>
                        <td><?php echo esc_html($item->englishTitle); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">Không tìm thấy kết quả phù hợp.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php
        // Phân trang
        if ($totalPages > 1): ?>
            <div class="tablenav">
                <div class="tablenav-pages">
                    <?php
                    $big = 999999999;
                    echo paginate_links(array(
                        'base'    => str_replace($big, '%#%', (admin_url('admin.php?page=techbook_publishers_page&paged=%#%'))),
                        'format'  => '&paged=%#%',
                        'current' => max(1, $current_page),
                        'total'   => $totalPages,
                        'type'    => 'plain',
                        'add_args' => array(
                            's' => $search,
                        ),
                    ));
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <style>
        /* CSS cho form tìm kiếm */
.search-form {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    margin-bottom: 20px;
    gap: 10px;
    flex-direction: row;
}

.search-input {
    width: 300px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.search-input:focus {
    border-color: #007cba;
    outline: none;
}

.search-button {
    background-color: #007cba;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.search-button:hover {
    background-color: #005a9e;
}

.search-button:active {
    background-color: #004880;
}

    </style>
    <?php

    
}



function hte_publisher_detail_page($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_publishers';

    // Lấy thông tin Publisher từ cơ sở dữ liệu
    $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);

    if (!$item) {
        return 'Không tìm thấy nhà xuất bản trong cơ sở dữ liệu.';
    }

    // Kiểm tra nếu form được submit
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_publisher'])) {
        $api_data = array(
            'tokenKey' => get_api_token(),
            'item' => array(
                'id' => intval($_POST['id']),
                'publisherCode' => sanitize_text_field($_POST['publisherCode']),
                'englishTitle' => sanitize_text_field($_POST['englishTitle']),
                'englishDescription' => sanitize_textarea_field($_POST['englishDescription']),
                'vietnameseDescription' => sanitize_textarea_field($_POST['vietnameseDescription']),
                'abstract' => sanitize_textarea_field($_POST['abstract']),
                'reference' => sanitize_text_field($_POST['reference']),
                'keyword' => sanitize_text_field($_POST['keyword']),
                'relatedICSCode' => sanitize_text_field($_POST['relatedICSCode']),
                'avatarPath' => sanitize_text_field($_POST['avatarPath']),
            )
        );

        // Loại bỏ các trường không cần gửi đến API
        unset($api_data['item']['featured']);

        // Gửi dữ liệu đến API
        $url_update = get_api_base_url() . '/Publishers/Update';
        $response = wp_remote_post($url_update, array(
            'body' => json_encode($api_data),
            'headers' => array('Content-Type' => 'application/json'),
        ));

        if (is_wp_error($response)) {
            $api_error = 'Có lỗi xảy ra khi cập nhật API: ' . $response->get_error_message();
        } else {
            $api_response = json_decode(wp_remote_retrieve_body($response), true);
            if ($api_response['code'] == 200 || $api_response['code'] == 201) {
                $api_success = 'Nhà xuất bản đã được cập nhật thành công trên API.';
            } else {
                $api_error = 'Cập nhật API thất bại: ' . $api_response['message'];
            }
        }

        // Chuẩn bị dữ liệu để lưu vào cơ sở dữ liệu
        $db_data = array(
            'publisherCode' => sanitize_text_field($_POST['publisherCode']),
            'englishTitle' => sanitize_text_field($_POST['englishTitle']),
            'englishDescription' => sanitize_textarea_field($_POST['englishDescription']),
            'vietnameseDescription' => sanitize_textarea_field($_POST['vietnameseDescription']),
            'abstract' => sanitize_textarea_field($_POST['abstract']),
            'reference' => sanitize_text_field($_POST['reference']),
            'keyword' => sanitize_text_field($_POST['keyword']),
            'relatedICSCode' => sanitize_text_field($_POST['relatedICSCode']),
            'avatarPath' => sanitize_text_field($_POST['avatarPath']),
            'featured' => isset($_POST['featured']) ? 1 : 0,
        );

        // Cập nhật cơ sở dữ liệu
        $update_result = $wpdb->update(
            $table_name,
            $db_data,
            array('id' => intval($_POST['id'])),
            array(
                '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d'
            ),
            array('%d')
        );

        if ($update_result !== false) {
            echo "<script>alert('Nhà xuất bản đã được cập nhật thành công trong cơ sở dữ liệu.');</script>";
        } else {
            $db_error_message = $wpdb->last_error;
            echo "<script>alert('Cập nhật cơ sở dữ liệu thất bại: " . addslashes($db_error_message) . "');</script>";
        }

        $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
    }

    ob_start();

    if (!empty($api_success)) {
        echo '<div class="notice notice-success"><p>' . esc_html($api_success) . '</p></div>';
    }
    if (!empty($api_error)) {
        echo '<div class="notice notice-error"><p>' . esc_html($api_error) . '</p></div>';
    }

    ?>

    <style>
        #updatePublisherForm h1 {
            text-align: center;
            color: #333;
        }
        form#updatePublisherForm {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        #updatePublisherForm div {
            margin-bottom: 15px;
        }
        #updatePublisherForm label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        #updatePublisherForm input[type="text"],
        #updatePublisherForm textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        #updatePublisherForm input[type="checkbox"] {
            margin-right: 10px;
        }
        #updatePublisherForm button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            margin: 0 auto;
        }
        #updatePublisherForm button:hover {
            background-color: #218838;
        }
        #updatePublisherForm .checkbox-group {
            display: flex;
            align-items: center;
        }
    </style>

    <h1>Chi tiết Nhà xuất bản</h1>
    <form id="updatePublisherForm" method="post">
        <div>
            <input type="hidden" id="id" name="id" value="<?php echo esc_attr($item['id']); ?>">
        </div>
        <div>
            <label for="publisherCode">Publisher Code:</label>
            <input type="text" id="publisherCode" name="publisherCode" value="<?php echo esc_attr($item['publisherCode']); ?>">
        </div>
        <div>
            <label for="englishTitle">English Title:</label>
            <input type="text" id="englishTitle" name="englishTitle" value="<?php echo esc_attr($item['englishTitle']); ?>">
        </div>
        <div>
            <label for="englishDescription">English Description:</label>
            <textarea id="englishDescription" name="englishDescription"><?php echo esc_textarea($item['englishDescription']); ?></textarea>
        </div>
        <div>
            <label for="vietnameseDescription">Vietnamese Description:</label>
            <textarea id="vietnameseDescription" name="vietnameseDescription"><?php echo esc_textarea($item['vietnameseDescription']); ?></textarea>
        </div>
        <div>
            <label for="abstract">Abstract:</label>
            <textarea id="abstract" name="abstract"><?php echo esc_textarea($item['abstract']); ?></textarea>
        </div>
        <div>
            <label for="reference">Reference:</label>
            <input type="text" id="reference" name="reference" value="<?php echo esc_attr($item['reference']); ?>">
        </div>
        <div>
            <label for="keyword">Keyword:</label>
            <input type="text" id="keyword" name="keyword" value="<?php echo esc_attr($item['keyword']); ?>">
        </div>
        <div>
            <label for="relatedICSCode">Related ICS Code:</label>
            <input type="text" id="relatedICSCode" name="relatedICSCode" value="<?php echo esc_attr($item['relatedICSCode']); ?>">
        </div>
        <div>
            <label for="avatarPath">Avatar Path:</label>
            <input type="text" id="avatarPath" name="avatarPath" value="<?php echo esc_attr($item['avatarPath']); ?>">
        </div>
        <div class="checkbox-group">
            <label for="featured">Featured:</label>
            <input type="checkbox" id="featured" name="featured" <?php echo $item['featured'] == 1 ? 'checked' : ''; ?>>
        </div>

        <button type="submit" name="update_publisher">Cập nhật</button>
    </form>


    <script>
        document.getElementById('updateButton').addEventListener('click', function() {
            const formData = new FormData(document.getElementById('updatePublisherForm'));
            const data = {
                id: formData.get('id'),
                tokenKey: '4XwMBElYC3xgZeIW0IZ1H42zyvDNM5h7',
                publisherCode: formData.get('publisherCode'),
                englishTitle: formData.get('englishTitle'),
                englishDescription: formData.get('englishDescription'),
                vietnameseDescription: formData.get('vietnameseDescription'),
                abstract: formData.get('abstract'),
                reference: formData.get('reference'),
                keyword: formData.get('keyword'),
                relatedICSCode: formData.get('relatedICSCode'),
                avatarPath: formData.get('avatarPath')
            };

            fetch('<?php echo esc_url($url_update); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                if (data.code == 200 || data.code == 201) {
                    alert('Cập nhật nhà xuất bản thành công!');
                    window.location.reload();
                } else {
                    alert('Đã có lỗi xảy ra khi cập nhật.');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi gửi dữ liệu cập nhật.');
            });
        });
    </script>
    <?php
    return ob_get_clean();
}


