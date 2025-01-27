<?php


function techbook_standards_page() {
    // Kiểm tra nếu có tham số 'item_id' thì chuyển sang trang chi tiết
    if (isset($_GET['item_id'])) {
        echo hte_standard_detail_page(intval($_GET['item_id']));
        return;
    }

    $tokenKey = get_api_token();
    $api_url = get_api_base_url() . '/Standards/GetPaging';

    // Lấy tham số tìm kiếm từ URL
    $search = isset($_GET['s']) ? trim($_GET['s']) : '';

    // Current page number
    $pageIndex = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
    $pageSize = 50;

    // Build the API request body
    if (empty($search)) {
        $body = array(
            "tokenKey" => $tokenKey,
            "pageIndex" => $pageIndex,
            "pageSize" => $pageSize,
            "item" => array(
                "deleted" => true,
                "newArrival" => true,
                "bestSellers" => true,
                "isFree" => true,
                "totalRows" => 0
            )
        );
    } else {
        $body = array(
            "tokenKey" => $tokenKey,
            "pageIndex" => $pageIndex,
            "pageSize" => $pageSize,
            "item" => array(
                "standardTitle" => $search,
                "deleted" => true,
                "newArrival" => true,
                "bestSellers" => true,
                "isFree" => true,
                "totalRows" => 0
            )
        );
    }

    $body = json_encode($body);

    $args = array(
        'method' => 'POST',
        'body' => $body,
        'headers' => array('Content-Type' => 'application/json'),
        'timeout' => 30, 
    );

    $response = wp_remote_post($api_url, $args);

    if (is_wp_error($response)) {
        echo 'Có lỗi xảy ra: ' . $response->get_error_message();
        return;
    }

    $data = json_decode(wp_remote_retrieve_body($response));

    if (!isset($data->data->items) || empty($data->data->items)) {
        echo 'Không tìm thấy kết quả, vui lòng thử lại sau.';
        return;
    }

    $items = $data->data->items;
    $totalRows = $data->data->totalRows;
    $totalPages = ceil($totalRows / $pageSize);

    // Gọi hàm lưu các Standards vào cơ sở dữ liệu
    hte_save_standards_to_cache($items);

    ?>
    <div class="wrap">
        <h1>Danh sách Tiêu chuẩn</h1>

        <!-- Form tìm kiếm -->
        <form method="get" action="" class="search-form">
            <input type="hidden" name="page" value="techbook_standards_page" />
            <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Tìm kiếm theo Standard Title" class="search-input" />
            <input type="submit" value="Tìm kiếm" class="button search-button" />
        </form>

        <table class="wp-list-table widefat fixed striped table-view-list">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Standard Title</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo esc_html($item->id); ?></td>
                    <td><a href="?page=techbook_standards_page&item_id=<?php echo esc_html($item->id); ?>"><?php echo esc_html($item->standardTitle); ?></a></td>
                    <td><?php echo esc_html($item->status); ?></td>
                </tr>
            <?php endforeach; ?>
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
                        'base'    => str_replace($big, '%#%', (admin_url('admin.php?page=techbook_standards_page&paged=%#%'))),
                        'format'  => '&paged=%#%',
                        'current' => max(1, $pageIndex),
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




function hte_standard_detail_page($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_standards';

    // Lấy thông tin Standard từ cơ sở dữ liệu
    $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);

    if (!$item) {
        return 'Không tìm thấy tiêu chuẩn trong cơ sở dữ liệu.';
    }

    // Kiểm tra nếu form được submit
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_standard'])) {
        // Chuẩn bị dữ liệu để gửi đến API
        $api_data = array(
            'tokenKey' => get_api_token(),
            'item' => array(
                'id' => intval($_POST['id']),
                'idProduct' => sanitize_text_field($_POST['idProduct']),
                'referenceNumber' => sanitize_text_field($_POST['referenceNumber']),
                'standardTitle' => sanitize_text_field($_POST['standardTitle']),
                'status' => sanitize_text_field($_POST['status']),
                'referencedStandards' => sanitize_text_field($_POST['referencedStandards']),
                'referencingStandards' => sanitize_text_field($_POST['referencingStandards']),
                'equivalentStandards' => sanitize_text_field($_POST['equivalentStandards']),
                'replace' => sanitize_text_field($_POST['replace']),
                'repalcedBy' => sanitize_text_field($_POST['repalcedBy']),
                'standardby' => sanitize_text_field($_POST['standardby']),
                'languages' => sanitize_text_field($_POST['languages']),
                'fullDescription' => sanitize_textarea_field($_POST['fullDescription']),
                'ebookPrice' => sanitize_text_field($_POST['ebookPrice']),
                'printPrice' => sanitize_text_field($_POST['printPrice']),
                'bothPrice' => sanitize_text_field($_POST['bothPrice']),
                'currency' => sanitize_text_field($_POST['currency']),
                'historicalEditions' => sanitize_text_field($_POST['historicalEditions']),
                'documentHistoryProductId' => sanitize_text_field($_POST['documentHistoryProductId']),
                'icsCode' => sanitize_text_field($_POST['icsCode']),
                'topics' => sanitize_text_field($_POST['topics']),
                'keyword' => sanitize_text_field($_POST['keyword']),
                'identicalStandards' => sanitize_text_field($_POST['identicalStandards']),
                'publishedDate' => sanitize_text_field($_POST['publishedDate']),
                'pages' => sanitize_text_field($_POST['pages']),
                'byTechnology' => sanitize_text_field($_POST['byTechnology']),
                'byIndustry' => sanitize_text_field($_POST['byIndustry']),
                'previewPath' => sanitize_text_field($_POST['previewPath']),
                'coverPath' => sanitize_text_field($_POST['coverPath']),
                'fullPath' => sanitize_text_field($_POST['fullPath']),
            )
        );

        // Loại bỏ các trường không cần gửi đến API
        unset($api_data['item']['deleted']);
        unset($api_data['item']['newArrival']);
        unset($api_data['item']['bestSellers']);
        unset($api_data['item']['isFree']);
        unset($api_data['item']['specialOffer']);
        unset($api_data['item']['featured']);

        // Gửi dữ liệu đến API
        $url_update = get_api_base_url() . '/Standards/Update';
        $response = wp_remote_post($url_update, array(
            'body' => json_encode($api_data),
            'headers' => array('Content-Type' => 'application/json'),
        ));

        if (is_wp_error($response)) {
            $api_error = 'Có lỗi xảy ra khi cập nhật API: ' . $response->get_error_message();
        } else {
            $api_response = json_decode(wp_remote_retrieve_body($response), true);
            if ($api_response['code'] == 200 || $api_response['code'] == 201) {
                $api_success = 'Tiêu chuẩn đã được cập nhật thành công trên API.';
            } else {
                $api_error = 'Cập nhật API thất bại: ' . $api_response['message'];
            }
        }

        // Chuẩn bị dữ liệu để lưu vào cơ sở dữ liệu
        $db_data = array(
            'idProduct' => sanitize_text_field($_POST['idProduct']),
            'referenceNumber' => sanitize_text_field($_POST['referenceNumber']),
            'standardTitle' => sanitize_text_field($_POST['standardTitle']),
            'status' => sanitize_text_field($_POST['status']),
            'referencedStandards' => sanitize_text_field($_POST['referencedStandards']),
            'referencingStandards' => sanitize_text_field($_POST['referencingStandards']),
            'equivalentStandards' => sanitize_text_field($_POST['equivalentStandards']),
            'replace' => sanitize_text_field($_POST['replace']),
            'repalcedBy' => sanitize_text_field($_POST['repalcedBy']),
            'standardby' => sanitize_text_field($_POST['standardby']),
            'languages' => sanitize_text_field($_POST['languages']),
            'fullDescription' => sanitize_textarea_field($_POST['fullDescription']),
            'ebookPrice' => sanitize_text_field($_POST['ebookPrice']),
            'printPrice' => sanitize_text_field($_POST['printPrice']),
            'bothPrice' => sanitize_text_field($_POST['bothPrice']),
            'currency' => sanitize_text_field($_POST['currency']),
            'historicalEditions' => sanitize_text_field($_POST['historicalEditions']),
            'documentHistoryProductId' => sanitize_text_field($_POST['documentHistoryProductId']),
            'icsCode' => sanitize_text_field($_POST['icsCode']),
            'topics' => sanitize_text_field($_POST['topics']),
            'keyword' => sanitize_text_field($_POST['keyword']),
            'identicalStandards' => sanitize_text_field($_POST['identicalStandards']),
            'publishedDate' => sanitize_text_field($_POST['publishedDate']),
            'pages' => sanitize_text_field($_POST['pages']),
            'byTechnology' => sanitize_text_field($_POST['byTechnology']),
            'byIndustry' => sanitize_text_field($_POST['byIndustry']),
            'previewPath' => sanitize_text_field($_POST['previewPath']),
            'coverPath' => sanitize_text_field($_POST['coverPath']),
            'fullPath' => sanitize_text_field($_POST['fullPath']),
            'deleted' => isset($_POST['deleted']) ? 1 : 0,
            'newArrival' => isset($_POST['newArrival']) ? 1 : 0,
            'bestSellers' => isset($_POST['bestSellers']) ? 1 : 0,
            'isFree' => isset($_POST['isFree']) ? 1 : 0,
            'specialOffer' => isset($_POST['specialOffer']) ? 1 : 0,
            'featured' => isset($_POST['featured']) ? 1 : 0,
        );

        // Cập nhật cơ sở dữ liệu
        $update_result = $wpdb->update(
            $table_name,
            $db_data,
            array('id' => intval($_POST['id'])),
            array(
                '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
                '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
                '%s','%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%d', '%d', '%d'
            ),
            array('%d')
        );

        if ($update_result !== false) {
            echo "<script>alert('Tiêu chuẩn đã được cập nhật thành công trong cơ sở dữ liệu.');</script>";
        } else {
            $db_error_message = $wpdb->last_error;
            echo "<script>alert('Cập nhật cơ sở dữ liệu thất bại: " . addslashes($db_error_message) . "');</script>";
        }

        // Lấy lại thông tin Standard sau khi cập nhật
        $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
    }

    // Hiển thị form chi tiết Standard
    ob_start();

    // Hiển thị thông báo
    if (!empty($api_success)) {
        echo '<div class="notice notice-success"><p>' . esc_html($api_success) . '</p></div>';
    }
    if (!empty($api_error)) {
        echo '<div class="notice notice-error"><p>' . esc_html($api_error) . '</p></div>';
    }

    ?>

    <style>
        #updateStandardForm h1 {
            text-align: center;
            color: #333;
        }
        form#updateStandardForm {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        #updateStandardForm div {
            margin-bottom: 15px;
        }
        #updateStandardForm label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        #updateStandardForm input[type="text"],
        #updateStandardForm textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        #updateStandardForm input[type="checkbox"] {
            margin-right: 10px;
        }
        #updateStandardForm button {
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
        #updateStandardForm button:hover {
            background-color: #218838;
        }
        #updateStandardForm .checkbox-group {
            display: flex;
            align-items: center;
        }
    </style>

<h1>Chi tiết Tiêu chuẩn</h1>
<form id="updateStandardForm" method="post">
<div>
            <input type="hidden" id="id" name="id" value="<?php echo esc_attr($item['id']); ?>">
        </div>
        <div>
            <label for="idProduct">ID Product:</label>
            <input type="text" id="idProduct" name="idProduct" value="<?php echo esc_attr($item['idProduct'] ?? ''); ?>">
        </div>
        <div>
            <label for="referenceNumber">Reference Number:</label>
            <input type="text" id="referenceNumber" name="referenceNumber" value="<?php echo esc_attr($item['referenceNumber'] ?? ''); ?>">
        </div>
        <div>
            <label for="standardTitle">Standard Title:</label>
            <input type="text" id="standardTitle" name="standardTitle" value="<?php echo esc_attr($item['standardTitle'] ?? ''); ?>">
        </div>
        <div>
            <label for="status">Status:</label>
            <input type="text" id="status" name="status" value="<?php echo esc_attr($item['status'] ?? ''); ?>">
        </div>
        <div>
            <label for="referencedStandards">Referenced Standards:</label>
            <input type="text" id="referencedStandards" name="referencedStandards" value="<?php echo esc_attr($item['referencedStandards'] ?? ''); ?>">
        </div>
        <div>
            <label for="referencingStandards">Referencing Standards:</label>
            <input type="text" id="referencingStandards" name="referencingStandards" value="<?php echo esc_attr($item['referencingStandards'] ?? ''); ?>">
        </div>
        <div>
            <label for="equivalentStandards">Equivalent Standards:</label>
            <input type="text" id="equivalentStandards" name="equivalentStandards" value="<?php echo esc_attr($item['equivalentStandards'] ?? ''); ?>">
        </div>
        <div>
            <label for="replace">Replace:</label>
            <input type="text" id="replace" name="replace" value="<?php echo esc_attr($item['replace'] ?? ''); ?>">
        </div>
        <div>
            <label for="repalcedBy">Replaced By:</label>
            <input type="text" id="repalcedBy" name="repalcedBy" value="<?php echo esc_attr($item['repalcedBy'] ?? ''); ?>">
        </div>
        <div>
            <label for="standardby">Standard By:</label>
            <input type="text" id="standardby" name="standardby" value="<?php echo esc_attr($item['standardby'] ?? ''); ?>">
        </div>
        <div>
            <label for="languages">Languages:</label>
            <input type="text" id="languages" name="languages" value="<?php echo esc_attr($item['languages'] ?? ''); ?>">
        </div>
        <div>
            <label for="fullDescription">Full Description:</label>
            <textarea id="fullDescription" name="fullDescription"><?php echo esc_textarea($item['fullDescription'] ?? ''); ?></textarea>
        </div>
        <div>
            <label for="ebookPrice">Ebook Price:</label>
            <input type="text" id="ebookPrice" name="ebookPrice" value="<?php echo esc_attr($item['ebookPrice'] ?? ''); ?>">
        </div>
        <div>
            <label for="printPrice">Print Price:</label>
            <input type="text" id="printPrice" name="printPrice" value="<?php echo esc_attr($item['printPrice'] ?? ''); ?>">
        </div>
        <div>
            <label for="bothPrice">Both Price:</label>
            <input type="text" id="bothPrice" name="bothPrice" value="<?php echo esc_attr($item['bothPrice'] ?? ''); ?>">
        </div>
        <div>
            <label for="currency">Currency:</label>
            <input type="text" id="currency" name="currency" value="<?php echo esc_attr($item['currency'] ?? ''); ?>">
        </div>
        <div>
            <label for="historicalEditions">Historical Editions:</label>
            <input type="text" id="historicalEditions" name="historicalEditions" value="<?php echo esc_attr($item['historicalEditions'] ?? ''); ?>">
        </div>
        <div>
            <label for="documentHistoryProductId">Document History Product ID:</label>
            <input type="text" id="documentHistoryProductId" name="documentHistoryProductId" value="<?php echo esc_attr($item['documentHistoryProductId'] ?? ''); ?>">
        </div>
        <div>
            <label for="icsCode">ICS Code:</label>
            <input type="text" id="icsCode" name="icsCode" value="<?php echo esc_attr($item['icsCode'] ?? ''); ?>">
        </div>
        <div>
            <label for="icsCode">Topics:</label>
            <input type="text" id="topics" name="topics" value="<?php echo esc_attr($item['topics'] ?? ''); ?>">
        </div>
        <div>
            <label for="keyword">Keyword:</label>
            <input type="text" id="keyword" name="keyword" value="<?php echo esc_attr($item['keyword'] ?? ''); ?>">
        </div>
        <div>
            <label for="identicalStandards">Identical Standards:</label>
            <input type="text" id="identicalStandards" name="identicalStandards" value="<?php echo esc_attr($item['identicalStandards'] ?? ''); ?>">
        </div>
        <div>
            <label for="publishedDate">Published Date:</label>
            <input type="text" id="publishedDate" name="publishedDate" value="<?php echo esc_attr($item['publishedDate'] ?? ''); ?>">
        </div>
        <div>
            <label for="pages">Pages:</label>
            <input type="text" id="pages" name="pages" value="<?php echo esc_attr($item['pages'] ?? ''); ?>">
        </div>
        <div>
            <label for="byTechnology">By Technology:</label>
            <input type="text" id="byTechnology" name="byTechnology" value="<?php echo esc_attr($item['byTechnology'] ?? ''); ?>">
        </div>
        <div>
            <label for="byIndustry">By Industry:</label>
            <input type="text" id="byIndustry" name="byIndustry" value="<?php echo esc_attr($item['byIndustry'] ?? ''); ?>">
        </div>
        <div>
            <label for="previewPath">Preview Path:</label>
            <input type="text" id="previewPath" name="previewPath" value="<?php echo esc_attr($item['previewPath'] ?? ''); ?>">
        </div>
        <div>
            <label for="coverPath">Cover Path:</label>
            <input type="text" id="coverPath" name="coverPath" value="<?php echo esc_attr($item['coverPath'] ?? ''); ?>">
        </div>
        <div>
            <label for="fullPath">Full Path:</label>
            <input type="text" id="fullPath" name="fullPath" value="<?php echo esc_attr($item['fullPath'] ?? ''); ?>">
        </div>

        <div class="checkbox-group">
            <label for="deleted">Deleted:</label>
            <input type="checkbox" id="deleted" name="deleted" <?php echo $item['deleted'] == 1 ? 'checked' : ''; ?>>
        </div>
        <div class="checkbox-group">
            <label for="newArrival">New Arrival:</label>
            <input type="checkbox" id="newArrival" name="newArrival" <?php echo $item['newArrival'] == 1 ? 'checked' : ''; ?>>
        </div>
        <div class="checkbox-group">
            <label for="bestSellers">Best Sellers:</label>
            <input type="checkbox" id="bestSellers" name="bestSellers" <?php echo $item['bestSellers'] == 1 ? 'checked' : ''; ?>>
        </div>
        <div class="checkbox-group">
            <label for="isFree">Is Free:</label>
            <input type="checkbox" id="isFree" name="isFree" <?php echo $item['isFree'] == 1 ? 'checked' : ''; ?>>
        </div>
        <div class="checkbox-group">
            <label for="specialOffer">Special Offer:</label>
            <input type="checkbox" id="specialOffer" name="specialOffer" <?php echo $item['specialOffer'] == 1 ? 'checked' : ''; ?>>
        </div>
        <div class="checkbox-group">
            <label for="featured">Featured:</label>
            <input type="checkbox" id="featured" name="featured" <?php echo $item['featured'] == 1 ? 'checked' : ''; ?>>
        </div>

        <button type="submit" name="update_standard">Cập nhật</button>
    </form>

    <script>
        document.getElementById('updateButton').addEventListener('click', function() {
            const formData = new FormData(document.getElementById('updateStandardForm'));
            const data = {
                tokenKey: tokenKey,
                item: {
                    id: formData.get('id'),
                    idProduct: formData.get('idProduct'),
                    referenceNumber: formData.get('referenceNumber'),
                    standardTitle: formData.get('standardTitle'),
                    status: formData.get('status'),
                    referencedStandards: formData.get('referencedStandards'),
                    referencingStandards: formData.get('referencingStandards'),
                    equivalentStandards: formData.get('equivalentStandards'),
                    replace: formData.get('replace'),
                    repalcedBy: formData.get('repalcedBy'),
                    standardby: formData.get('standardby'),
                    languages: formData.get('languages'),
                    fullDescription: formData.get('fullDescription'),
                    ebookPrice: formData.get('ebookPrice'),
                    printPrice: formData.get('printPrice'),
                    bothPrice: formData.get('bothPrice'),
                    currency: formData.get('currency'),
                    historicalEditions: formData.get('historicalEditions'),
                    documentHistoryProductId: formData.get('documentHistoryProductId'),
                    icsCode: formData.get('icsCode'),
                    topics: formData.get('topics'),
                    keyword: formData.get('keyword'),
                    identicalStandards: formData.get('identicalStandards'),
                    publishedDate: formData.get('publishedDate'),
                    pages: formData.get('pages'),
                    byTechnology: formData.get('byTechnology'),
                    byIndustry: formData.get('byIndustry'),
                    previewPath: formData.get('previewPath'),
                    coverPath: formData.get('coverPath'),
                    fullPath: formData.get('fullPath')
                }
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
                    alert('Cập nhật tiêu chuẩn thành công!');
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


