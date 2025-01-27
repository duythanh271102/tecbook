<?php

function techbook_ics_codes_page() {
    global $wpdb;

    if (isset($_GET['item_id'])) {
        echo hte_ics_code_detail_page(sanitize_text_field($_GET['item_id']));
        return;
    }

    $tokenKey = get_api_token();
    $api_url = 'https://115.84.178.66:8028/api/InternationalClassificationStandards/GetPaging';
    $pageIndex = 1;
    $pageSize = 50;
    $ics_codes = [];

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

        $ics_codes = array_merge($ics_codes, $data->data->items);

        if (count($data->data->items) < $pageSize) {
            break;
        }

        $pageIndex++;
    }

    if (!empty($ics_codes)) {
        hte_save_ics_codes_to_cache($ics_codes);
    }

    $search = isset($_GET['s']) ? trim($_GET['s']) : '';
    $current_page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
    $offset = ($current_page - 1) * $pageSize;

    if (!empty($search)) {
        $search_sql = $wpdb->prepare("WHERE icsCode LIKE %s", '%' . $wpdb->esc_like($search) . '%');
    } else {
        $search_sql = '';
    }

    $sql = "SELECT * FROM {$wpdb->prefix}tecbook_ics_codes $search_sql LIMIT %d OFFSET %d";
    $items = $wpdb->get_results($wpdb->prepare($sql, $pageSize, $offset));

    $totalRows_sql = "SELECT COUNT(*) FROM {$wpdb->prefix}tecbook_ics_codes $search_sql";
    $totalRows = $wpdb->get_var($totalRows_sql);
    $totalPages = ceil($totalRows / $pageSize);
    ?>
    <div class="wrap">
        <h1>Danh sách ICS Codes</h1>

        <form method="get" action="" class="search-form">
            <input type="hidden" name="page" value="techbook_ics_codes_page" />
            <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Tìm kiếm theo ICS Code" class="search-input" />
            <input type="submit" value="Tìm kiếm" class="button search-button" />
        </form>

        <table class="wp-list-table widefat fixed striped table-view-list">
            <thead>
                <tr>
                    <th>ICS Code</th>
                    <th>Name in English</th>
                    <th>Name in Vietnamese</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($items)) : ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><a href="?page=techbook_ics_codes_page&item_id=<?php echo esc_html($item->icsCode); ?>"><?php echo esc_html($item->icsCode); ?></a></td>
                        <td><?php echo esc_html($item->nameInEnglish); ?></td>
                        <td><?php echo esc_html($item->nameInVietnamese); ?></td>
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
        if ($totalPages > 1): ?>
            <div class="tablenav">
                <div class="tablenav-pages">
                    <?php
                    $big = 999999999;
                    echo paginate_links(array(
                        'base'    => str_replace($big, '%#%', (admin_url('admin.php?page=techbook_ics_codes_page&paged=%#%'))),
                        'format'  => '&paged=%#%',
                        'current' => max(1, $current_page),
                        'total'   => $totalPages,
                        'type'    => 'plain',
                        'add_args' => array('s' => $search),
                    ));
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
}


function hte_ics_code_detail_page($icsCode) {
    $tokenKey = get_api_token();
    $url = 'https://115.84.178.66:8028/api/InternationalClassificationStandards/GetByCode';
    $url_update = 'https://115.84.178.66:8028/api/InternationalClassificationStandards/Update';

    $body = json_encode([
        "icsCode" => $icsCode, 
        "tokenKey" => $tokenKey,
        "item" => [
            "icsCode" => $icsCode,
            "nameInEnglish" => "",
            "nameInVietnamese" => "",
            "ralatedToBookSubjects" => "",
            "keyword" => "",
            "fatherICSCode" => ""
        ]
    ]);

    $response = wp_remote_post($url, [
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

    $item = $data['data'];

    ob_start();
    ?>

<style>

#updateICSCodeForm h1 {
    text-align: center;
    color: #333;
}
form#updateICSCodeForm {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 0 auto;
}
#updateICSCodeForm div {
    margin-bottom: 15px;
}
#updateICSCodeForm label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}
#updateICSCodeForm input[type="text"],
#updateICSCodeForm input[type="number"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
#updateICSCodeForm input[type="checkbox"] {
    margin-right: 10px;
}
#updateICSCodeForm button {
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
#updateICSCodeForm button:hover {
    background-color: #218838;
}
#updateICSCodeForm .checkbox-group {
    display: flex;
    align-items: center;
}
</style>
    <h1>Chi tiết ICS Code</h1>
    <form id="updateICSCodeForm">
        <div>
            <input type="hidden" id="icsCode" name="icsCode" value="<?php echo esc_attr($item['icsCode']); ?>">
        </div>
        <div>
            <label for="nameInEnglish">Name in English:</label>
            <input type="text" id="nameInEnglish" name="nameInEnglish" value="<?php echo esc_attr($item['nameInEnglish']); ?>" required>
        </div>
        <div>
            <label for="nameInVietnamese">Name in Vietnamese:</label>
            <input type="text" id="nameInVietnamese" name="nameInVietnamese" value="<?php echo esc_attr($item['nameInVietnamese']); ?>" required>
        </div>
        <div>
            <label for="ralatedToBookSubjects">Related to Book Subjects:</label>
            <input type="text" id="ralatedToBookSubjects" name="ralatedToBookSubjects" value="<?php echo esc_attr($item['ralatedToBookSubjects']); ?>">
        </div>
        <div>
            <label for="keyword">Keyword:</label>
            <input type="text" id="keyword" name="keyword" value="<?php echo esc_attr($item['keyword']); ?>">
        </div>
        <div>
            <label for="fatherICSCode">Father ICS Code:</label>
            <input type="text" id="fatherICSCode" name="fatherICSCode" value="<?php echo esc_attr($item['fatherICSCode']); ?>">
        </div>

        <button type="button" id="updateICSButton">Cập nhật</button>
    </form>

    <script>
        document.getElementById('updateICSButton').addEventListener('click', function() {
            const formData = new FormData(document.getElementById('updateICSCodeForm'));
            const data = {
                tokenKey: '<?php echo esc_js($tokenKey); ?>',
                 item: {
                icsCode: formData.get('icsCode'),
             
                nameInEnglish: formData.get('nameInEnglish'),
                nameInVietnamese: formData.get('nameInVietnamese'),
                ralatedToBookSubjects: formData.get('ralatedToBookSubjects'),
                keyword: formData.get('keyword'),
                fatherICSCode: formData.get('fatherICSCode')
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
                    alert('Cập nhật ICS Code thành công!');
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
?>