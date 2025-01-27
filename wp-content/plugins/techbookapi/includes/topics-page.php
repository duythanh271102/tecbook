<?php


function techbook_topics_page() {
    global $wpdb;
    
    $pageIndex = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
    $pageSize = 500;

    $search = isset($_GET['s']) ? trim($_GET['s']) : '';

    $tokenKey = get_api_token();

    if (empty($search)) {

        $api_url = 'https://115.84.178.66:8028/api/Topic/GetPaging';
        $body = json_encode(array(
            "tokenKey" => $tokenKey,
            "pageIndex" => $pageIndex,
            "pageSize" => $pageSize
        ));

        // Make the API request
        $response = wp_remote_post($api_url, array(
            'method'    => 'POST',
            'body'      => $body,
            'headers'   => array('Content-Type' => 'application/json'),
        ));

        // Check if there was an error with the response
        if (is_wp_error($response)) {
            echo 'Có lỗi xảy ra: ' . $response->get_error_message();
            return;
        }

        // Decode the response data
        $response_body = wp_remote_retrieve_body($response);
        $data = json_decode($response_body);

        // Check if data is empty or missing
        if (!isset($data->data->items) || empty($data->data->items)) {
            echo 'Không có dữ liệu nào được tìm thấy.' . '<br>';
            echo 'Dữ liệu trả về từ API: <pre>' . var_export($data, true) . '</pre>'; // Debugging the decoded data
            return;
        }

        // If data is not empty, save it to cache
        $items = $data->data->items;
        hte_save_topics_to_cache($items); // Save topics to the database

        $totalRows = isset($data->data->totalRows) ? $data->data->totalRows : 0; 
        $totalPages = ceil($totalRows / $pageSize);

    } else {
        // Search case - querying the database directly
        $offset = ($pageIndex - 1) * $pageSize;

        $search_sql = '%' . $wpdb->esc_like($search) . '%';

        $sql = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}tecbook_topics WHERE title LIKE %s LIMIT %d OFFSET %d",
            $search_sql,
            $pageSize,
            $offset
        );

        // Get the items from the database
        $items = $wpdb->get_results($sql);

        // Get the total rows matching the search
        $totalRows = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}tecbook_topics WHERE title LIKE %s",
                $search_sql
            )
        );

        // Calculate the total pages
        $totalPages = ceil($totalRows / $pageSize);
    }

    ?>
    <div class="wrap">
        <h1>Danh sách Topics</h1>

        <!-- Form tìm kiếm -->
        <form method="get" action="" class="search-form">
            <input type="hidden" name="page" value="techbook_topics_page" />
            <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Tìm kiếm theo title" class="search-input" />
            <input type="submit" value="Tìm kiếm" class="button search-button" />
        </form>

        <table class="wp-list-table widefat fixed striped table-view-list">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Title</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo isset($item->code) ? esc_html($item->code) : 'N/A'; ?></td>
                        <td><?php echo isset($item->title) ? esc_html($item->title) : 'N/A'; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">Không tìm thấy kết quả phù hợp.</td>
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
                        'base'    => str_replace($big, '%#%', (admin_url('admin.php?page=techbook_topics_page&paged=%#%'))),
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
