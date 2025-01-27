<?php


function techbook_industry_page() {
    global $wpdb;

    if (isset($_GET['item_id'])) {
        echo hte_industry_detail_page(intval($_GET['item_id']));
        return;
    }

    $pageIndex = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
    $pageSize = 50;

    $search = isset($_GET['s']) ? trim($_GET['s']) : '';

    $tokenKey = get_api_token();

    if (empty($search)) {

        $api_url = get_api_base_url() . '/StandardSubjectIndustry/GetAll';
        $body = json_encode(array(
            "id" => "string",
            "tokenKey" => $tokenKey,
            "pageIndex" => $pageIndex,
            "pageSize" => $pageSize,
            "item" => array(
                "subjectCode" => "string"
            )
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

        if (!isset($data->data) || empty($data->data)) {
            echo 'Không có dữ liệu nào được tìm thấy.';
            return;
        }

        if (!empty($data->data)) {
            hte_save_industry_to_cache($data->data); 
        }

        $items = $data->data;
        $totalRows = $data->totalRows; 
        $totalPages = ceil($totalRows / $pageSize);
    } else {
        $offset = ($pageIndex - 1) * $pageSize;

        $search_sql = '%' . $wpdb->esc_like($search) . '%';

        $sql = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}tecbook_industry WHERE englishTitle LIKE %s LIMIT %d OFFSET %d",
            $search_sql,
            $pageSize,
            $offset
        );

        $items = $wpdb->get_results($sql);

        // Get total number of matching rows
        $totalRows = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}tecbook_industry WHERE englishTitle LIKE %s",
                $search_sql
            )
        );

        $totalPages = ceil($totalRows / $pageSize);
    }

    ?>
    <div class="wrap">
        <h1>Danh sách Industry and technology</h1>

        <!-- Form tìm kiếm -->
        <form method="get" action="" class="search-form">
            <input type="hidden" name="page" value="techbook_industry_page" />
            <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Tìm kiếm theo englishTitle" class="search-input" />
            <input type="submit" value="Tìm kiếm" class="button search-button" />
        </form>

        <table class="wp-list-table widefat fixed striped table-view-list">
            <thead>
                <tr>
                    <th>subjectCode</th>
                    <th>englishTitle</th>
                    <th>vietnameseTitle</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo esc_html($item->subjectCode); ?></td>
                        <td><a href="?page=techbook_industry_page&item_id=<?php echo esc_html($item->subjectCode); ?>"><?php echo esc_html($item->englishTitle); ?></a></td>
                        <td><?php echo esc_html($item->vietnameseTitle); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Không tìm thấy kết quả phù hợp.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php
        // Pagination
        if ($totalPages > 1): ?>
            <div class="tablenav">
                <div class="tablenav-pages">
                    <?php
                    $big = 999999999; // Large number to make pagination work
                    echo paginate_links(array(
                        'base'    => str_replace($big, '%#%', (admin_url('admin.php?page=techbook_industry_page&paged=%#%'))),
                        'format'  => '&paged=%#%',
                        'current' => max(1, $pageIndex),
                        'total'   => $totalPages,
                        'type'    => 'plain',
                        'add_args' => array('s' => $search), 
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








function hte_industry_detail_page($id) {
    $tokenKey = get_api_token();

    // URL API GetById
    $url = get_api_base_url() . '/StandardSubjectIndustry/GetById';
    $url_update = get_api_base_url() . '/StandardSubjectIndustry/Update';

    // Prepare JSON data for API request
    $body = json_encode([
        "tokenKey" => $tokenKey,
        "item" => [
            "subjectCode" => "$id"
        ]
    ]);

    // Call API to get details
    $response = wp_remote_post($url, [
        'method' => 'POST',
        'body' => $body,
        'headers' => [
            'Content-Type' => 'application/json',
        ],
    ]);

    echo '<pre>' . print_r(json_decode($body, true), true) . '</pre>';

    if (is_wp_error($response)) {
        return 'Có lỗi xảy ra khi lấy dữ liệu.';
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (!isset($data['data'])) {
        return 'Không tìm thấy dữ liệu.';
    }

    // Retrieve the detailed data
    $item = $data['data'];

    // Display the update form
    ob_start();
    ?>
    <style>
        #updateSubjectForm h1 {
            text-align: center;
            color: #333;
        }
        form#updateSubjectForm {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        #updateSubjectForm div {
            margin-bottom: 15px;
        }
        #updateSubjectForm label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        #updateSubjectForm input[type="text"],
        #updateSubjectForm textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        #updateSubjectForm button {
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
        #updateSubjectForm button:hover {
            background-color: #218838;
        }
    </style>

    <h1>Chi tiết industry and technology</h1>
    <form id="updateSubjectForm">
        <div>
            <input type="hidden" id="id" name="id" value="<?php echo esc_attr($item['subjectCode']); ?>">
        </div>
        <div>
            <label for="code">icsCodeFather:</label>
            <input type="text" id="icsCodeFather" name="icsCodeFather" value="<?php echo esc_attr($item['icsCodeFather']); ?>" required>
        </div>
        <div>
            <label for="subjects">englishTitle:</label>
            <input type="text" id="englishTitle" name="englishTitle" value="<?php echo esc_attr($item['englishTitle']); ?>" required>
        </div>
        <div>
            <label for="subjects">vietnameseTitle:</label>
            <input type="text" id="vietnameseTitle" name="vietnameseTitle" value="<?php echo esc_attr($item['vietnameseTitle']); ?>" required>
        </div>
        <div>
            <label for="subjects">relatedICSCode:</label>
            <input type="text" id="relatedICSCode" name="relatedICSCode" value="<?php echo esc_attr($item['relatedICSCode']); ?>" required>
        </div>
        <div>
            <label for="subjects">keyword:</label>
            <input type="text" id="keyword" name="keyword" value="<?php echo esc_attr($item['keyword']); ?>" required>
        </div>

        <button type="button" id="updateButton">Cập nhật</button>
    </form>

    <script>
        document.getElementById('updateButton').addEventListener('click', function() {
            const formData = new FormData(document.getElementById('updateSubjectForm'));
            const data = {
                tokenKey: '<?php echo esc_js($tokenKey); ?>',
                item: {
                    subjectCode: formData.get('subjectCode'),
                    icsCodeFather: formData.get('icsCodeFather'),
                    englishTitle: formData.get('englishTitle'),
                    vietnameseTitle: formData.get('vietnameseTitle'),
                    relatedICSCode: formData.get('relatedICSCode'),
                    keyword: formData.get('keyword')
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
                    alert('Cập nhật Industry thành công!');
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
