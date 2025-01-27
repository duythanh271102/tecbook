<?php

function techbook_subjects_page() {
    global $wpdb;

    // Check if 'item_id' is set, redirect to the detail page
    if (isset($_GET['item_id'])) {
        echo hte_subject_detail_page(intval($_GET['item_id']));
        return;
    }

    // Current page index and size
    $pageIndex = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
    $pageSize = 50;


    // Get search parameter from URL
    $search = isset($_GET['s']) ? trim($_GET['s']) : '';

    $tokenKey = get_api_token();

    if (empty($search)) {
        // When no search term, use API to display subjects

        $api_url = get_api_base_url() . '/SubjectType/GetAll';
        $body = json_encode(array(
            "id" => "string",
            "tokenKey" => $tokenKey,
            "intValue" => 0,
            "boolValue" => true,
            "stringValue" => "string",
            "pageIndex" => $pageIndex,
            "pageSize" => $pageSize,
            "keyword" => "",
            "orderBy" => "string",
            "orderWay" => "string",
            "item" => array(
                "id" => 0,
                "code" => "string",
                "subjects" => "",
                "notes" => "string"
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

        // Optionally, save data to database
        if (!empty($data->data)) {
            hte_save_subjects_to_cache($data->data); // Save to database
        }

        $items = $data->data;
        $totalRows = $data->totalRows; // Lấy tổng số hàng từ phản hồi API
$totalPages = ceil($totalRows / $pageSize);
    } else {
        // When a search term is provided, search in the database

        $offset = ($pageIndex - 1) * $pageSize;

        // Sanitize the search term for use in SQL LIKE
        $search_sql = '%' . $wpdb->esc_like($search) . '%';

        // Prepare SQL query
        $sql = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}tecbook_subjects WHERE subjects LIKE %s LIMIT %d OFFSET %d",
            $search_sql,
            $pageSize,
            $offset
        );

        $items = $wpdb->get_results($sql);

        // Get total number of matching rows
        $totalRows = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}tecbook_subjects WHERE subjects LIKE %s",
                $search_sql
            )
        );

        $totalPages = ceil($totalRows / $pageSize);
    }

    ?>
    <div class="wrap">
        <h1>Danh sách Subjects</h1>

        <!-- Form tìm kiếm -->
        <form method="get" action="" class="search-form">
            <input type="hidden" name="page" value="techbook_subjects_page" />
            <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Tìm kiếm theo Subjects" class="search-input" />
            <input type="submit" value="Tìm kiếm" class="button search-button" />
        </form>

        <table class="wp-list-table widefat fixed striped table-view-list">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Subjects</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo esc_html($item->id); ?></td>
                        <td><a href="?page=techbook_subjects_page&item_id=<?php echo esc_html($item->id); ?>"><?php echo esc_html($item->code); ?></a></td>
                        <td><?php echo esc_html($item->subjects); ?></td>
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
                        'base'    => str_replace($big, '%#%', (admin_url('admin.php?page=techbook_subjects_page&paged=%#%'))),
                        'format'  => '&paged=%#%',
                        'current' => max(1, $pageIndex),
                        'total'   => $totalPages,
                        'type'    => 'plain',
                        'add_args' => array('s' => $search), // Keep search parameter in pagination links
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

function hte_subject_detail_page($id) {
    $tokenKey = get_api_token();

    // URL API GetById
    $url = get_api_base_url() . '/SubjectType/GetById';
    $url_update = get_api_base_url() . '/SubjectType/Update';

    // Prepare JSON data for API request
    $body = json_encode([
        "id" => "string",
        "tokenKey" => $tokenKey,
        "intValue" => 0,
        "boolValue" => true,
        "stringValue" => "string",
        "pageIndex" => 0,
        "pageSize" => 0,
        "keyword" => "string",
        "orderBy" => "string",
        "orderWay" => "string",
        "item" => [
            "id" => $id,
            "code" => "",
            "subjects" => "",
            "notes" => ""
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

    <h1>Chi tiết Subject</h1>
    <form id="updateSubjectForm">
        <div>
            <input type="hidden" id="id" name="id" value="<?php echo esc_attr($item['id']); ?>">
        </div>
        <div>
            <label for="code">Code:</label>
            <input type="text" id="code" name="code" value="<?php echo esc_attr($item['code']); ?>" required>
        </div>
        <div>
            <label for="subjects">Subjects:</label>
            <input type="text" id="subjects" name="subjects" value="<?php echo esc_attr($item['subjects']); ?>" required>
        </div>
        <div>
            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes" required><?php echo esc_textarea($item['notes']); ?></textarea>
        </div>

        <button type="button" id="updateButton">Cập nhật</button>
    </form>

    <script>
        document.getElementById('updateButton').addEventListener('click', function() {
            const formData = new FormData(document.getElementById('updateSubjectForm'));
            const data = {
                id: "string",
                tokenKey: '<?php echo esc_js($tokenKey); ?>',
                intValue: 0,
                boolValue: true,
                stringValue: "string",
                pageIndex: 0,
                pageSize: 0,
                keyword: "string",
                orderBy: "string",
                orderWay: "string",
                item: {
                    id: parseInt(formData.get('id')),
                    code: formData.get('code'),
                    subjects: formData.get('subjects'),
                    notes: formData.get('notes')
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
                    alert('Cập nhật Subject thành công!');
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
