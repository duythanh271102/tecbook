<?php

function techbook_books_page() {
    
    
if (isset($_GET['item_id'])) {
    function hte_books_detail_page($id) {
        global $wpdb;
        
        $url_update = get_api_base_url() . '/Documents/Update';
        $table_name = $wpdb->prefix . 'tecbook_books_cache';
    
        $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
    
        if (!$item) {
            return 'Book not found in cache.';
        }
    
        // Kiểm tra nếu form được submit
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_book'])) {
            // Chuẩn bị dữ liệu để gửi đến API
            $api_data = array(
                'id' => 'string',
                'tokenKey' => get_api_token(),
                'intValue' => 0,
                'boolValue' => true,
                'stringValue' => 'string',
                'pageIndex' => 0,
                'pageSize' => 0,
                'keyword' => 'string',
                'item' => array(
                    'id' => intval($_POST['id']),
                    'title' => sanitize_text_field($_POST['title']),
                    'author' => sanitize_text_field($_POST['author']),
                    'edition' => sanitize_text_field($_POST['edition']),
                    'documentStatus' => sanitize_text_field($_POST['documentStatus']),
                    'publicationDate' => sanitize_text_field($_POST['publicationDate']),
                    'publisher' => sanitize_text_field($_POST['publisher']),
                    'doi' => sanitize_text_field($_POST['doi']),
                    'page' => intval($_POST['page']),
                    'isbn' => sanitize_text_field($_POST['isbn']),
                    'subjectsCode' => sanitize_text_field($_POST['subjectsCode']),
                    'subjects' => sanitize_text_field($_POST['subjects']),
                    'abstract' => sanitize_text_field($_POST['abstract']),
                    'keywords' => sanitize_text_field($_POST['keywords']),
                    'pricePrint' => floatval($_POST['pricePrint']),
                    'priceeBook' => floatval($_POST['priceeBook']),
                    'previewPath' => sanitize_text_field($_POST['previewPath']),
                    'fullContentBookPath' => sanitize_text_field($_POST['fullContentBookPath']),
                    'createdDate' => date('Y-m-d\TH:i:s\Z'),
                    'updatedDate' => date('Y-m-d\TH:i:s\Z'),
                    'deleted' => isset($_POST['deleted']) ? true : false,
                    'totalRows' => 0
                )
            );
    
            // Loại bỏ các trường không cần gửi đến API
            unset($api_data['item']['newArrival']);
            unset($api_data['item']['bestSellers']);
            unset($api_data['item']['isFree']);
            unset($api_data['item']['specialOffer']);
            unset($api_data['item']['featured']);
    
            $response = wp_remote_post($url_update, array(
                'body' => json_encode($api_data),
                'headers' => array('Content-Type' => 'application/json'),
            ));
    
            if (is_wp_error($response)) {
                $api_error = 'Failed to update book: ' . $response->get_error_message();
            } else {
                $api_response = json_decode(wp_remote_retrieve_body($response), true);
                if ($api_response['code'] == 200 || $api_response['code'] == 201) {
                    $api_success = 'Book updated successfully in API.';
                } else {
                    $api_error = 'API update failed: ' . $api_response['message'];
                }
            }
    
            // Chuẩn bị dữ liệu để lưu vào cơ sở dữ liệu
            $db_data = array(
                'title' => sanitize_text_field($_POST['title']),
                'author' => sanitize_text_field($_POST['author']),
                'edition' => sanitize_text_field($_POST['edition']),
                'documentStatus' => sanitize_text_field($_POST['documentStatus']),
                'publicationDate' => sanitize_text_field($_POST['publicationDate']),
                'publisher' => sanitize_text_field($_POST['publisher']),
                'doi' => sanitize_text_field($_POST['doi']),
                'page' => intval($_POST['page']),
                'isbn' => sanitize_text_field($_POST['isbn']),
                'subjectsCode' => sanitize_text_field($_POST['subjectsCode']),
                'subjects' => sanitize_text_field($_POST['subjects']),
                'abstract' => sanitize_text_field($_POST['abstract']),
                'keywords' => sanitize_text_field($_POST['keywords']),
                'pricePrint' => floatval($_POST['pricePrint']),
                'priceeBook' => floatval($_POST['priceeBook']),
                'previewPath' => sanitize_text_field($_POST['previewPath']),
                'fullContentBookPath' => sanitize_text_field($_POST['fullContentBookPath']),
                'createdDate' => current_time('mysql'),
                'updatedDate' => current_time('mysql'),
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
                    '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s',
                    '%s', '%s', '%s', '%s', '%f', '%f', '%s', '%s', '%s', '%s',
                    '%d', '%d', '%d', '%d', '%d', '%d'
                ),
                array('%d')
            );
    
            if ($update_result !== false) {
                echo "<script>alert('Book updated successfully in database.');</script>";
            } else {
                $db_error_message = $wpdb->last_error;
                echo "<script>alert('Failed to update book in database: " . addslashes($db_error_message) . "');</script>";
            }            
    
            // Lấy lại thông tin sách sau khi cập nhật
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
        }
    
        // Hiển thị form chi tiết sách
        ob_start();
    
        // Hiển thị thông báo
        if (!empty($api_success)) {
            echo '<div class="notice notice-success"><p>' . esc_html($api_success) . '</p></div>';
        }
        if (!empty($api_error)) {
            echo '<div class="notice notice-error"><p>' . esc_html($api_error) . '</p></div>';
        }
        if (!empty($db_success)) {
            echo '<div class="notice notice-success"><p>' . esc_html($db_success) . '</p></div>';
        }
        if (!empty($db_error)) {
            echo '<div class="notice notice-error"><p>' . esc_html($db_error) . '</p></div>';
        }
        ?>
        <style>

        #updateBookForm h1 {
            text-align: center;
            color: #333;
        }
        form#updateBookForm {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        #updateBookForm div {
            margin-bottom: 15px;
        }
        #updateBookForm label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        #updateBookForm input[type="text"],
        #updateBookForm input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        #updateBookForm input[type="checkbox"] {
            margin-right: 10px;
        }
        #updateBookForm button {
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
        #updateBookForm button:hover {
            background-color: #218838;
        }
        #updateBookForm .checkbox-group {
            display: flex;
            align-items: center;
        }
    </style>

    <h1>Book Details</h1>
    <form id="updateBookForm" method="post">
        <div>
            <input type="hidden" id="id" name="id" value="<?php echo esc_attr($item['id']); ?>">
        </div>
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo esc_attr($item['title']); ?>">
        </div>
        <div>
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" value="<?php echo esc_attr($item['author']); ?>">
        </div>
        <div>
            <label for="edition">Edition:</label>
            <input type="text" id="edition" name="edition" value="<?php echo esc_attr($item['edition']); ?>">
        </div>
        <div>
            <label for="documentStatus">Document Status:</label>
            <input type="text" id="documentStatus" name="documentStatus" value="<?php echo esc_attr($item['documentStatus']); ?>">
        </div>
        <div>
            <label for="publicationDate">Publication Date:</label>
            <input type="text" id="publicationDate" name="publicationDate" value="<?php echo esc_attr($item['publicationDate']); ?>">
        </div>
        <div>
            <label for="publisher">Publisher:</label>
            <input type="text" id="publisher" name="publisher" value="<?php echo esc_attr($item['publisher']); ?>">
        </div>
        <div>
            <label for="doi">DOI:</label>
            <input type="text" id="doi" name="doi" value="<?php echo esc_attr($item['doi']); ?>">
        </div>
        <div>
            <label for="page">Page:</label>
            <input type="number" id="page" name="page" value="<?php echo esc_attr($item['page']); ?>">
        </div>
        <div>
            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" value="<?php echo esc_attr($item['isbn']); ?>">
        </div>
        <div>
            <label for="subjectsCode">Subjects Code:</label>
            <input type="text" id="subjectsCode" name="subjectsCode" value="<?php echo esc_attr($item['subjectsCode']); ?>">
        </div>
        <div>
            <label for="subjects">Subjects:</label>
            <input type="text" id="subjects" name="subjects" value="<?php echo esc_attr($item['subjects']); ?>">
        </div>
        <div>
            <label for="abstract">Abstract:</label>
            <input type="text" id="abstract" name="abstract" value="<?php echo esc_attr($item['abstract']); ?>">
        </div>
        <div>
            <label for="keywords">Keywords:</label>
            <input type="text" id="keywords" name="keywords" value="<?php echo esc_attr($item['keywords']); ?>">
        </div>
        <div>
            <label for="pricePrint">Price (Print):</label>
            <input type="number" id="pricePrint" name="pricePrint" value="<?php echo esc_attr($item['pricePrint']); ?>">
        </div>
        <div>
            <label for="priceeBook">Price (eBook):</label>
            <input type="number" id="priceeBook" name="priceeBook" value="<?php echo esc_attr($item['priceeBook']); ?>">
        </div>
        <div>
            <label for="previewPath">Preview Path:</label>
            <input type="text" id="previewPath" name="previewPath" value="<?php echo esc_attr($item['previewPath']); ?>">
        </div>
        <div>
            <label for="fullContentBookPath">Full Content Path:</label>
            <input type="text" id="fullContentBookPath" name="fullContentBookPath" value="<?php echo esc_attr($item['fullContentBookPath']); ?>">
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
        <button type="submit" name="update_book">Update</button>
    </form>
    
    <script>
        document.getElementById('updateButton').addEventListener('click', function() {
            const formData = new FormData(document.getElementById('updateBookForm'));
            const tokenKey = '<?php echo get_api_token(); ?>';
            const data = {
                id: 'string',
                tokenKey: tokenKey,
                intValue: 0,
                boolValue: true,
                stringValue: 'string',
                pageIndex: 0,
                pageSize: 0,
                keyword: 'string',
                item: {
                    id: parseInt(formData.get('id')),
                    title: formData.get('title'),
                    author: formData.get('author'),
                    edition: formData.get('edition'),
                    documentStatus: formData.get('documentStatus'),
                    publicationDate: formData.get('publicationDate'),
                    publisher: formData.get('publisher'),
                    doi: formData.get('doi'),
                    page: parseInt(formData.get('page')),
                    isbn: formData.get('isbn'),
                    subjectsCode: formData.get('subjectsCode'),
                    subjects: formData.get('subjects'),
                    abstract: formData.get('abstract'),
                    keywords: formData.get('keywords'),
                    pricePrint: parseFloat(formData.get('pricePrint')),
                    priceeBook: parseFloat(formData.get('priceeBook')),
                    previewPath: formData.get('previewPath'),
                    fullContentBookPath: formData.get('fullContentBookPath'),
                    createdDate: new Date().toISOString(),
                    updatedDate: new Date().toISOString(),
                    deleted: formData.get('deleted') === 'on',
                    totalRows: 0
                }
            };
            console.log('data:', data);

            fetch('<?php echo esc_url($url_update); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                if(data.code == 200 || data.code == 201){
                    alert('Book updated successfully!');
                    window.location.reload();
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Error updating book.');
            });
        });
    </script>
        <?php
        return ob_get_clean();
    }
    
    
    echo hte_books_detail_page(intval($_GET['item_id']));
    return;
}

$tokenKey = get_api_token();
$api_url = get_api_base_url() . '/Documents/GetPaging';

// Get the search parameter from the URL
$search = isset($_GET['s']) ? trim($_GET['s']) : '';

// Current page number
$pageIndex = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
$pageSize = 50;

// Build the API request body
if (empty($search)) {
    // When there is no search parameter
    $body = array(
        "tokenKey"     => $tokenKey,
        "intValue"     => 0,
        "boolValue"    => true,
        "stringValue"  => "string",
        "pageIndex"    => $pageIndex,
        "pageSize"     => $pageSize,
        "orderBy"      => "string",
        "orderWay"     => "string",
        "item"         => array(
            "previewPath"         => "string",
            "fullContentBookPath" => "string",
            "createdDate"         => date('Y-m-d\TH:i:s\Z'),
            "updatedDate"         => date('Y-m-d\TH:i:s\Z'),
            "deleted"             => true,
            "newArrival"          => true,
            "bestSellers"         => true,
            "isFree"              => true,
            "totalRows"           => 0
        )
    );
} else {
    // When there is a search parameter
    $body = array(
        "tokenKey"     => $tokenKey,
        "intValue"     => 0,
        "boolValue"    => true,
        "stringValue"  => "string",
        "pageIndex"    => $pageIndex,
        "pageSize"     => $pageSize,
        "orderBy"      => "string",
        "orderWay"     => "string",
        "item"         => array(
            "previewPath"         => "string",
            "fullContentBookPath" => "string",
            "createdDate"         => date('Y-m-d\TH:i:s\Z'),
            "updatedDate"         => date('Y-m-d\TH:i:s\Z'),
            "deleted"             => true,
            "newArrival"          => true,
            "bestSellers"         => true,
            "isFree"              => true,
            "totalRows"           => 0,
            "title"               => $search // Include the search term in the title
        )
    );
}

// Convert the body to JSON
$body = json_encode($body);

// Use wp_remote_post to call the API
$response = wp_remote_post($api_url, array(
    'method'    => 'POST',
    'body'      => $body,
    'headers'   => array('Content-Type' => 'application/json'),
));

if (is_wp_error($response)) {
    echo 'Something went wrong: ' . $response->get_error_message();
    return;
}

$data = json_decode(wp_remote_retrieve_body($response));

if (!isset($data->data->items) || empty($data->data->items)) {
    echo 'No items found.';
    return;
}

$items = $data->data->items;
$totalRows = $data->data->totalRows;
$totalPages = ceil($totalRows / $pageSize);

hte_save_books_to_cache($items);

?>
<div class="wrap">
    <h1>Book List</h1>

    <!-- Search form -->
    <form method="get" action="" class="search-form">
        <input type="hidden" name="page" value="techbook_books_page" />
        <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search by Title" class="search-input" />
        <input type="submit" value="Search" class="button search-button" />
    </form>

    <table class="wp-list-table widefat fixed striped table-view-list">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Publisher</th>
                <th>ISBN</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo esc_html($item->id); ?></td>
                <td><a href="?page=techbook_books_page&item_id=<?php echo esc_html($item->id); ?>"><?php echo esc_html($item->title); ?></a></td>
                <td><?php echo esc_html($item->publisher); ?></td>
                <td><?php echo esc_html($item->isbn); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    // Pagination
    if ($totalPages > 1): ?>
        <div class="tablenav">
            <div class="tablenav-pages">
                <?php
                $big = 999999999; // need an unlikely integer
                echo paginate_links(array(
                    'base'      => str_replace($big, '%#%', (admin_url('admin.php?page=techbook_books_page&paged=%#%'))),
                    'format'    => '&paged=%#%',
                    'current'   => max(1, $pageIndex),
                    'total'     => $totalPages,
                    'type'      => 'plain',
                    'add_args'  => array(
                        's' => $search,
                    ),
                ));
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* CSS for the search form */
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