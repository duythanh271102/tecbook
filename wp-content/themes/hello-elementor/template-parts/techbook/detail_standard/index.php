<?php

/**
 * The template for displaying footer.
 *
 * @package HelloElementor
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

<?php
$standard_id = get_query_var('standard_id');

$api_url = 'https://115.84.178.66:8028/api/Standards/GetById';
$api_data = array(
    'tokenKey' => '4XwMBElYC3xgZeIW0IZ1H42zyvDNM5h7',
    'item' => array(
        'id' => $standard_id
    )
);

$response = wp_remote_post($api_url, array(
    'method'    => 'POST',
    'headers'   => array('Content-Type' => 'application/json'),
    'body'      => json_encode($api_data),
));


if (is_wp_error($response)) {
    $error_message = $response->get_error_message();
    echo "There was an error: $error_message";
} else {
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['code']) && $data['code'] === '200') {
        $data = $data['data'];
    } else {
        echo "Error retrieving data from the API";
    }
}
?>


<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/detail_standard/index.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/detail_standard/index.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/detail_book/index.js"></script>

<script type="text/javascript">
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    const idProduct = "<?php echo esc_js($data['idProduct']); ?>";
</script>

<div id="loading-container">
    <i class="fas fa-spinner"></i>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</div>

<div class="container-fullwidth">

    <div class="container-boxed">
        <div class=" title-home">
            <a href="<?php echo home_url(); ?>/home/" id="home-link">Home</a> &gt;
            <a href="<?php echo home_url(); ?>/Publisher/" id="home-link">Publisher</a> &gt;
            <span style="color: #1E00AE;"> <?= esc_html($data['standardTitle']); ?> </span>
        </div>
    </div>

    <div class="container-boxed">
        <div class="product-detail">
            <div class="book-detail-container">
                <!-- Bên trái: Hình ảnh sách và các nút -->
                <div class="book-image-container product-item-book" data-book-id="<?php echo isset($data['id']) ? esc_attr($data['id']) : ''; ?>">
                    <img src="<?= isset($data['idProduct']) && !empty($data['idProduct'])
                                    ? 'https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/standards/cover/' . $data['idProduct'] . '.jpg'
                                    : esc_url(home_url() . '/wp-content/uploads/2024/09/Rectangle-17873.png'); ?>"
                        alt="Book Image" class="book-image" onerror="this.onerror=null; this.src='<?= esc_url(home_url() . '/wp-content/uploads/2024/09/Rectangle-17873.png'); ?>'">


                    <div class="book-icons">
                        <button class="butoon-book-icon1" id="butoon-book-icon3"><img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-6.svg" alt="Icon 2">
                            <p>Preview </p>
                        </button>
                        <!-- <button class="butoon-book-icon1" id="butoon-book-icon1"><img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-5.svg" alt="Icon 2"></button> -->
                        <button class="butoon-book-icon1 icon-action icon-wishlist" id="butoon-book-icon2">
                            <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
                                <path d="M15.1111 1.41016C18.6333 1.41016 21 4.76266 21 7.89016C21 14.2239 11.1778 19.4102 11 19.4102C10.8222 19.4102 1 14.2239 1 7.89016C1 4.76266 3.36667 1.41016 6.88889 1.41016C8.91111 1.41016 10.2333 2.43391 11 3.33391C11.7667 2.43391 13.0889 1.41016 15.1111 1.41016Z"
                                    stroke="#157FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                </div>



                <!-- Bên phải: Thông tin sách -->
                <div class="book-info" id="book-info-container">
                    <h1 id="book-title" class="book-title"><?= esc_html($data['referenceNumber']); ?></h1>
                    <h2 id="book-subtitle" class="book-subtitle"><?= esc_html($data['standardTitle']); ?></h2>

                    <?php if (!empty($data['standardby'])): ?>
                        <?php
                        $publisher_name = $data['standardby'];

                        global $wpdb;
                        $table_name = $wpdb->prefix . 'tecbook_publishers';
                        $publisher = $wpdb->get_row($wpdb->prepare(
                            "SELECT * FROM $table_name WHERE publisherCode = %s",
                            $publisher_name
                        ));

                        if ($publisher) {
                            $publisher_id = $publisher->id;

                            // Tạo URL 
                            $url = site_url('/detail/publisher-' . $publisher_id . '/');
                        ?>
                            <p>
                                <strong>Publisher : </strong>
                                <span id="book-standard-by" class="book-standard-by">
                                    <a href="<?= esc_url($url); ?>">
                                        <?= esc_html($publisher_name); ?>
                                    </a>
                                </span>
                            </p>
                        <?php } else { ?>
                            <!-- Trường hợp không tìm thấy nhà xuất bản trong cơ sở dữ liệu -->
                            <p>
                                <strong>Publisher : </strong>
                                <span id="book-standard-by" class="book-standard-by">
                                    <?= esc_html($publisher_name); ?>
                                </span>
                            </p>
                        <?php } ?>
                    <?php endif; ?>


                    <p><strong>Published date:</strong> <span id="book-published-date" class="book-published-date"><?= esc_html($data['publishedDate']); ?></span></p>
                    <!-- <p><strong>Publisher:</strong> <span id="book-published" class="book-published-date"><?= esc_html($data['published']); ?></span></p> -->
                    <p><strong>Status:</strong>
                        <span id="book-status" class="status-label">
                            <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-7.svg" alt="Status Icon" class="status-icon"> <?= esc_html($data['status']); ?>
                        </span>
                    </p>


                    <p><strong>Abstract:</strong> </p>

                    <p><span id="book-abstract" class="abstract-text"><?= esc_html($data['fullDescription']); ?></span></p>

                    <!-- <a href="#" class="view-more" id="view-more-link">View more ></a> -->
                </div>

            </div>
        </div>


        <!-- Các phiên bản -->


        <div class="versions">
            <h2>Format</h2>
            <!-- <div class="language-selector">
                <label for="language">Language:</label>
                <select id="language" name="language">
                    <option value="english">English</option>
                    <option value="vietnamese">Vietnamese</option>
                </select>
            </div> -->
        </div>
        <div class="formats-container product-item-book" data-book-id="<?php echo isset($data['id']) ? esc_attr($data['id']) : ''; ?>" data-book-name="<?= esc_html($data['standardTitle']); ?>">
            <div class="format-row">
                <div class="format-label">
                    <strong class="Formats1">Available Formats </strong>
                </div>
                <div class="availability">
                    <strong class="Formats1">Availability </strong>
                </div>
                <div class="price">
                    <div><strong class="Formats1">Priced</strong></div>
                    <!-- <div class="discount-header">20%</div> -->
                </div>
                <div class="quantity">
                    <div><strong class="Formats1">Quantity</strong></div>
                </div>
                <div class="actions">
                </div>
            </div>
            <div class="dashed-line"></div>

            <!-- <div class="format-row">
                <div class="format-label">
                    <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Frame-225.svg" alt="PDF">
                </div>
                <div class="availability">15mb, download</div>
                <div class="price">
                    <span class="discount">29.59$</span>
                    <del>39$</del>
                </div>
                <div class="actions">
                    <button class="add-to-cart">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/shopping-bag-02.svg" alt="Cart Icon" class="cart-icon1"> <p class="add_botton">Add to cart</p>
                    </button>
                    <button class="contact-order">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/credit-card-check.svg" alt="purchase Icon" class="purchase-icon"> <p class="add_botton">Instant purchase</p> 
                    </button>
                </div>
            </div>
            <div class="dashed-line"></div> -->

            <div class="format-row">
                <div class="format-label">
                    <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Frame-225-1.svg" alt="E-Book">
                </div>
                <div class="availability">Download</div>
                <div class="price hunghung1">
                    <?php
                    $price_factor = floatval(get_option('techbookapi_price_factor', 1));
                    $original_price_ebook = floatval($data['ebookPrice']);

                    if ($original_price_ebook == 0) {
                        echo '<span class="discount">Please contact admin for price</span>';
                        $final_price_ebook = 0;
                    } else {
                        $final_price_ebook = $original_price_ebook * $price_factor;
                        echo '<span class="discount">' . esc_html($final_price_ebook) . '$</span>';
                    }
                    ?>
                </div>

                <div class="cart-item-quantity">
                    <input type="number" min="0" class="qty-input" data-book-quantity="quantity_price_ebook" value="1">
                </div>
                <div class="actions">
                    <?php if ($final_price_ebook == 0) { ?>
                        <button class="cannot-add-to-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 20 22" fill="none">
                                <path d="M14.0004 8V5C14.0004 2.79086 12.2095 1 10.0004 1C7.79123 1 6.00037 2.79086 6.00037 5V8M1.59237 9.35196L0.992373 15.752C0.821775 17.5717 0.736477 18.4815 1.03842 19.1843C1.30367 19.8016 1.76849 20.3121 2.35839 20.6338C3.0299 21 3.94374 21 5.77142 21H14.2293C16.057 21 16.9708 21 17.6423 20.6338C18.2322 20.3121 18.6971 19.8016 18.9623 19.1843C19.2643 18.4815 19.179 17.5717 19.0084 15.752L18.4084 9.35197C18.2643 7.81535 18.1923 7.04704 17.8467 6.46616C17.5424 5.95458 17.0927 5.54511 16.555 5.28984C15.9444 5 15.1727 5 13.6293 5L6.37142 5C4.82806 5 4.05638 5 3.44579 5.28984C2.90803 5.54511 2.45838 5.95458 2.15403 6.46616C1.80846 7.04704 1.73643 7.81534 1.59237 9.35196Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="add_botton">Add to cart</span>
                        </button>
                    <?php } else { ?>
                        <button class="add-to-cart btn-cart-detail" data-book-pricebook="<?php echo esc_html($final_price_ebook); ?>" data-book-price="price_ebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 20 22" fill="none">
                                <path d="M14.0004 8V5C14.0004 2.79086 12.2095 1 10.0004 1C7.79123 1 6.00037 2.79086 6.00037 5V8M1.59237 9.35196L0.992373 15.752C0.821775 17.5717 0.736477 18.4815 1.03842 19.1843C1.30367 19.8016 1.76849 20.3121 2.35839 20.6338C3.0299 21 3.94374 21 5.77142 21H14.2293C16.057 21 16.9708 21 17.6423 20.6338C18.2322 20.3121 18.6971 19.8016 18.9623 19.1843C19.2643 18.4815 19.179 17.5717 19.0084 15.752L18.4084 9.35197C18.2643 7.81535 18.1923 7.04704 17.8467 6.46616C17.5424 5.95458 17.0927 5.54511 16.555 5.28984C15.9444 5 15.1727 5 13.6293 5L6.37142 5C4.82806 5 4.05638 5 3.44579 5.28984C2.90803 5.54511 2.45838 5.95458 2.15403 6.46616C1.80846 7.04704 1.73643 7.81534 1.59237 9.35196Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="add_botton">Add to cart</span>
                        </button>
                    <?php } ?>
                    <!-- <button class="contact-order">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/credit-card-check.svg" alt="purchase Icon" class="purchase-icon"> <p class="add_botton">Instant purchase</p> 
                    </button> -->
                </div>
            </div>
            <div class="dashed-line"></div>

            <div class="format-row">
                <div class="format-label">
                    <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Frame-225-2.svg" alt="Printed">
                </div>
                <div class="availability">Ships in 1-2 business days</div>
                <div class="price hunghung2">
                    <?php
                    $price_factor = floatval(get_option('techbookapi_price_factor', 1));
                    $original_price_print = floatval($data['printPrice']);

                    if ($original_price_print == 0) {
                        echo '<span class="discount">Please contact admin for price</span>';
                        $final_price_print = 0;
                    } else {
                        $final_price_print = $original_price_print * $price_factor;
                        echo '<span class="discount">' . esc_html($final_price_print) . '$</span>';
                    }
                    ?>
                </div>

                <div class="cart-item-quantity">
                    <input type="number" min="0" class="qty-input" data-book-quantity="quantity_price_print" value="1">
                </div>

                <div class="actions">
                    <?php if ($final_price_print == 0) { ?>
                        <button class="cannot-add-to-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 20 22" fill="none">
                                <path d="M14.0004 8V5C14.0004 2.79086 12.2095 1 10.0004 1C7.79123 1 6.00037 2.79086 6.00037 5V8M1.59237 9.35196L0.992373 15.752C0.821775 17.5717 0.736477 18.4815 1.03842 19.1843C1.30367 19.8016 1.76849 20.3121 2.35839 20.6338C3.0299 21 3.94374 21 5.77142 21H14.2293C16.057 21 16.9708 21 17.6423 20.6338C18.2322 20.3121 18.6971 19.8016 18.9623 19.1843C19.2643 18.4815 19.179 17.5717 19.0084 15.752L18.4084 9.35197C18.2643 7.81535 18.1923 7.04704 17.8467 6.46616C17.5424 5.95458 17.0927 5.54511 16.555 5.28984C15.9444 5 15.1727 5 13.6293 5L6.37142 5C4.82806 5 4.05638 5 3.44579 5.28984C2.90803 5.54511 2.45838 5.95458 2.15403 6.46616C1.80846 7.04704 1.73643 7.81534 1.59237 9.35196Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="add_botton">Add to cart</span>
                        </button>
                    <?php } else { ?>
                        <button class="add-to-cart btn-cart-detail" data-book-pricebook="<?php echo esc_html($final_price_print); ?>" data-book-price="price_print">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 20 22" fill="none">
                                <path d="M14.0004 8V5C14.0004 2.79086 12.2095 1 10.0004 1C7.79123 1 6.00037 2.79086 6.00037 5V8M1.59237 9.35196L0.992373 15.752C0.821775 17.5717 0.736477 18.4815 1.03842 19.1843C1.30367 19.8016 1.76849 20.3121 2.35839 20.6338C3.0299 21 3.94374 21 5.77142 21H14.2293C16.057 21 16.9708 21 17.6423 20.6338C18.2322 20.3121 18.6971 19.8016 18.9623 19.1843C19.2643 18.4815 19.179 17.5717 19.0084 15.752L18.4084 9.35197C18.2643 7.81535 18.1923 7.04704 17.8467 6.46616C17.5424 5.95458 17.0927 5.54511 16.555 5.28984C15.9444 5 15.1727 5 13.6293 5L6.37142 5C4.82806 5 4.05638 5 3.44579 5.28984C2.90803 5.54511 2.45838 5.95458 2.15403 6.46616C1.80846 7.04704 1.73643 7.81534 1.59237 9.35196Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="add_botton">Add to cart</span>
                        </button>
                    <?php } ?>
                    <!-- <button class="contact-order">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/credit-card-check.svg" alt="purchase Icon" class="purchase-icon"> <p class="add_botton">Instant purchase</p> 
                    </button> -->
                </div>
            </div>
        </div>
        <div class="formats-container-moblie product-item-book" data-book-id="<?php echo isset($data['id']) ? esc_attr($data['id']) : ''; ?>" data-book-name="<?= esc_html($data['standardTitle']); ?>">
            <div class="format-moblie">
                <div class="detail-row">
                    <strong class="Formats1">Available Formats </strong>
                    <div class="format-right"> <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Frame-225-1.svg" alt="E-Book">
                    </div>
                </div>
                <div class="detail-row">
                    <strong class="Formats1">Availability </strong>
                    <div class="format-right">
                        <span class="availability">Download</span>
                    </div>
                </div>
                <div class="detail-row">
                    <strong class="Formats1">Priced</strong>
                    <div class="format-right " style="color: #2C2C2C; font-family: Ford Antenna; font-size: 14px; font-weight: 600;  line-height: 24px;">
                        <?php
                        $price_factor = floatval(get_option('techbookapi_price_factor', 1));
                        $original_price_ebook = floatval($data['ebookPrice']);

                        if ($original_price_ebook == 0) {
                            echo '<span class="discount">Please contact admin for price</span>';
                            $final_price_ebook = 0;
                        } else {
                            $final_price_ebook = $original_price_ebook * $price_factor;
                            echo '<span class="discount">' . esc_html($final_price_ebook) . '$</span>';
                        }
                        ?>
                    </div>

                </div>
                <div class="detail-row">
                    <strong class="Formats1">Quantity</strong>
                    <div class="cart-item-quantity">
                        <input type="number" min="0" class="qty-input" data-book-quantity="quantity_price_ebook" value="1">
                    </div>
                </div>
                <div class="actions">
                    <?php if ($final_price_ebook == 0) { ?>
                        <button class="cannot-add-to-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 20 22" fill="none">
                                <path d="M14.0004 8V5C14.0004 2.79086 12.2095 1 10.0004 1C7.79123 1 6.00037 2.79086 6.00037 5V8M1.59237 9.35196L0.992373 15.752C0.821775 17.5717 0.736477 18.4815 1.03842 19.1843C1.30367 19.8016 1.76849 20.3121 2.35839 20.6338C3.0299 21 3.94374 21 5.77142 21H14.2293C16.057 21 16.9708 21 17.6423 20.6338C18.2322 20.3121 18.6971 19.8016 18.9623 19.1843C19.2643 18.4815 19.179 17.5717 19.0084 15.752L18.4084 9.35197C18.2643 7.81535 18.1923 7.04704 17.8467 6.46616C17.5424 5.95458 17.0927 5.54511 16.555 5.28984C15.9444 5 15.1727 5 13.6293 5L6.37142 5C4.82806 5 4.05638 5 3.44579 5.28984C2.90803 5.54511 2.45838 5.95458 2.15403 6.46616C1.80846 7.04704 1.73643 7.81534 1.59237 9.35196Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="add_botton">Add to cart</span>
                        </button>
                    <?php } else { ?>
                        <button class="add-to-cart btn-cart-detail" data-book-pricebook="<?php echo esc_html($final_price_ebook); ?>" data-book-price="price_ebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 20 22" fill="none">
                                <path d="M14.0004 8V5C14.0004 2.79086 12.2095 1 10.0004 1C7.79123 1 6.00037 2.79086 6.00037 5V8M1.59237 9.35196L0.992373 15.752C0.821775 17.5717 0.736477 18.4815 1.03842 19.1843C1.30367 19.8016 1.76849 20.3121 2.35839 20.6338C3.0299 21 3.94374 21 5.77142 21H14.2293C16.057 21 16.9708 21 17.6423 20.6338C18.2322 20.3121 18.6971 19.8016 18.9623 19.1843C19.2643 18.4815 19.179 17.5717 19.0084 15.752L18.4084 9.35197C18.2643 7.81535 18.1923 7.04704 17.8467 6.46616C17.5424 5.95458 17.0927 5.54511 16.555 5.28984C15.9444 5 15.1727 5 13.6293 5L6.37142 5C4.82806 5 4.05638 5 3.44579 5.28984C2.90803 5.54511 2.45838 5.95458 2.15403 6.46616C1.80846 7.04704 1.73643 7.81534 1.59237 9.35196Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="add_botton">Add to cart</span>
                        </button>
                    <?php } ?>
                    <!-- <button class="contact-order">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/credit-card-check.svg" alt="purchase Icon" class="purchase-icon"> <p class="add_botton">Instant purchase</p> 
                    </button> -->
                </div>
            </div>
            <div class="dashed-line"></div>

            <div class="format-moblie">
                <div class="detail-row">
                    <strong class="Formats1">Available Formats </strong>
                    <div class="format-right"> <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Frame-225-2.svg" alt="Printed"></div>
                </div>
                <div class="detail-row">
                    <strong class="Formats1">Availability </strong>
                    <div class="format-right">
                        <span class="availability">Ships in 1-2 business days</span>
                    </div>
                </div>
                <div class="detail-row">
                    <strong class="Formats1">Priced</strong>
                    <div class="format-right " style="color: #2C2C2C; font-family: Ford Antenna; font-size: 14px; font-weight: 600;  line-height: 24px;">
                        <?php
                        $price_factor = floatval(get_option('techbookapi_price_factor', 1));
                        $original_price_print = floatval($data['printPrice']);

                        if ($original_price_print == 0) {
                            echo '<span class="discount">Please contact admin for price</span>';
                            $final_price_print = 0;
                        } else {
                            $final_price_print = $original_price_print * $price_factor;
                            echo '<span class="discount">' . esc_html($final_price_print) . '$</span>';
                        }
                        ?>
                    </div>

                </div>
                <div class="detail-row">
                    <strong class="Formats1">Quantity</strong>
                    <div class="cart-item-quantity">
                        <input type="number" min="0" class="qty-input" data-book-quantity="quantity_price_print" value="1">
                    </div>
                </div>
                <div class="actions">
                    <?php if ($final_price_print == 0) { ?>
                        <button class="cannot-add-to-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 20 22" fill="none">
                                <path d="M14.0004 8V5C14.0004 2.79086 12.2095 1 10.0004 1C7.79123 1 6.00037 2.79086 6.00037 5V8M1.59237 9.35196L0.992373 15.752C0.821775 17.5717 0.736477 18.4815 1.03842 19.1843C1.30367 19.8016 1.76849 20.3121 2.35839 20.6338C3.0299 21 3.94374 21 5.77142 21H14.2293C16.057 21 16.9708 21 17.6423 20.6338C18.2322 20.3121 18.6971 19.8016 18.9623 19.1843C19.2643 18.4815 19.179 17.5717 19.0084 15.752L18.4084 9.35197C18.2643 7.81535 18.1923 7.04704 17.8467 6.46616C17.5424 5.95458 17.0927 5.54511 16.555 5.28984C15.9444 5 15.1727 5 13.6293 5L6.37142 5C4.82806 5 4.05638 5 3.44579 5.28984C2.90803 5.54511 2.45838 5.95458 2.15403 6.46616C1.80846 7.04704 1.73643 7.81534 1.59237 9.35196Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="add_botton">Add to cart</span>
                        </button>
                    <?php } else { ?>
                        <button class="add-to-cart btn-cart-detail" data-book-pricebook="<?php echo esc_html($final_price_print); ?>" data-book-price="price_print">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 20 22" fill="none">
                                <path d="M14.0004 8V5C14.0004 2.79086 12.2095 1 10.0004 1C7.79123 1 6.00037 2.79086 6.00037 5V8M1.59237 9.35196L0.992373 15.752C0.821775 17.5717 0.736477 18.4815 1.03842 19.1843C1.30367 19.8016 1.76849 20.3121 2.35839 20.6338C3.0299 21 3.94374 21 5.77142 21H14.2293C16.057 21 16.9708 21 17.6423 20.6338C18.2322 20.3121 18.6971 19.8016 18.9623 19.1843C19.2643 18.4815 19.179 17.5717 19.0084 15.752L18.4084 9.35197C18.2643 7.81535 18.1923 7.04704 17.8467 6.46616C17.5424 5.95458 17.0927 5.54511 16.555 5.28984C15.9444 5 15.1727 5 13.6293 5L6.37142 5C4.82806 5 4.05638 5 3.44579 5.28984C2.90803 5.54511 2.45838 5.95458 2.15403 6.46616C1.80846 7.04704 1.73643 7.81534 1.59237 9.35196Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="add_botton">Add to cart</span>
                        </button>
                    <?php } ?>
                    <!-- <button class="contact-order">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/credit-card-check.svg" alt="purchase Icon" class="purchase-icon"> <p class="add_botton">Instant purchase</p> 
                    </button> -->
                </div>
            </div>
        </div>


        <!-- Các phần mô tả -->
        <div class="tabs">
            <button class="tab-link active" onclick="openTab(event, 'product-details')">Product Details</button>
            <button class="tab-link" onclick="openTab(event, 'full-description')">Full Description</button>
        </div>



        <div id="product-details" class="tab-content">
            <div class="book-details">
                <?php if (!empty($data['referenceNumber'])): ?>
                    <div class="detail-row">
                        <span class="label"><strong>• </strong> Reference number:</span>
                        <span class="value"><?= esc_html($data['referenceNumber']); ?></span>
                    </div>
                <?php endif; ?>




                <?php if (!empty($data['standardTitle'])): ?>
                    <div class="detail-row">
                        <span class="label"><strong>• </strong> Standard Title:</span>
                        <span class="value"><?= esc_html($data['standardTitle']); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['icsCode'])): ?>
                    <?php
                    $codes = explode('*', $data['icsCode']);
                    $items = array(); // Mảng chứa cả icsCode và nameInEnglish

                    global $wpdb;
                    $table_name = $wpdb->prefix . 'tecbook_ics_codes';

                    foreach ($codes as $code) {
                        $code = trim($code);
                        if (!empty($code)) {
                            $name = $wpdb->get_var(
                                $wpdb->prepare(
                                    "SELECT nameInEnglish FROM $table_name WHERE icsCode = %s",
                                    $code
                                )
                            );
                            if ($name) {
                                // Bảo vệ chuỗi trước khi thêm
                                $items[] = array(
                                    'icsCode' => esc_html($code),
                                    'nameInEnglish' => esc_html($name)
                                );
                            }
                        }
                    }
                    ?>

                    <?php if (!empty($items)): ?>
                        <div class="detail-row">
                            <span class="label"><strong>• </strong> ICS Code:</span>
                            <span class="value">
                                <?php foreach ($items as $item): ?>
                                    <div>
                                        <?php
                                        // Tạo URL chứa cả icsCode và nameInEnglish, nhưng chỉ hiển thị nameInEnglish
                                        echo '<a href="' . esc_url(home_url('/techbook/search-publisher/'))
                                            . '?icsCode=' . urlencode($item['icsCode'])
                                            . '&nameInEnglish=' . urlencode($item['nameInEnglish'])
                                            . '" target="_blank">'
                                            . $item['nameInEnglish']
                                            . '</a><br>';
                                        ?>
                                    </div>
                                <?php endforeach; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>







                <?php if (!empty($data['publishedDate'])): ?>
                    <div class="detail-row">
                        <span class="label"><strong>• </strong> Published Date:</span>
                        <span class="value"><?= esc_html($data['publishedDate']); ?></span>
                    </div>
                <?php endif; ?>



                <?php if (!empty($data['equivalentStandards'])): ?>
                    <div class="detail-row">
                        <span class="label"><strong>• </strong> Equivalent standards:</span>
                        <div class="value">
                            <?php

                            $standards = explode('*', $data['equivalentStandards']);

                            foreach ($standards as $standard) {
                                $standard = trim($standard);
                                if (!empty($standard)) {
                                    echo esc_html($standard) . '<br>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>




                <?php if (!empty($data['referencedStandards'])): ?>
                    <div class="detail-row">
                        <span class="label"><strong>• </strong> Referenced standards:</span>
                        <div class="value">
                            <?php

                            $standards = explode('*', $data['referencedStandards']);

                            foreach ($standards as $standard) {
                                $standard = trim($standard);
                                if (!empty($standard)) {
                                    echo '<a href="' . esc_url(home_url('/techbook/search-publisher/')) . '?referencedStandards=' . urlencode($standard) . '">' . esc_html($standard) . '</a><br>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['referencingStandards'])): ?>
                    <div class="detail-row">
                        <span class="label"><strong>• </strong> Referencing standards:</span>
                        <div class="value">
                            <?php

                            $standards = explode('*', $data['referencingStandards']);

                            foreach ($standards as $standard) {
                                $standard = trim($standard);
                                if (!empty($standard)) {
                                    echo '<a href="' . esc_url(home_url('/techbook/search-publisher/')) . '?referencingStandards=' . urlencode($standard) . '">' . esc_html($standard) . '</a><br>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['repalcedBy'])): ?>
                    <div class="detail-row">
                        <span class="label"><strong>• </strong> Replaced by:</span>
                        <a href="<?= esc_url(home_url('/techbook/search-publisher/')); ?>?repalcedBy=<?= urlencode($data['repalcedBy']); ?>" class="value">
                            <?= esc_html($data['repalcedBy']); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['replace'])): ?>
                    <div class="detail-row">
                        <span class="label"><strong>• </strong> Replace:</span>
                        <div class="value">
                            <?php

                            $standards = explode('*', $data['replace']);

                            foreach ($standards as $standard) {
                                $standard = trim($standard);
                                if (!empty($standard)) {
                                    echo '<a href="' . esc_url(home_url('/techbook/search-publisher/')) . '?replace=' . urlencode($standard) . '">' . esc_html($standard) . '</a><br>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>





                <?php if (!empty($data['standardby'])): ?>
                    <div class="detail-row">
                        <span class="label"><strong>• </strong> Standard by:</span>
                        <?php if (!empty($data['standardby'])): ?>
                            <?php
                            $publisher_name = $data['standardby'];

                            global $wpdb;
                            $table_name = $wpdb->prefix . 'tecbook_publishers';

                            $publisher = $wpdb->get_row($wpdb->prepare(
                                "SELECT * FROM $table_name WHERE publisherCode = %s",
                                $publisher_name
                            ));

                            if ($publisher) {
                                $publisher_id = $publisher->id;

                                $url = site_url('/detail/publisher-' . $publisher_id . '/');
                            ?>
                                <span id="book-standard-by" class="value">
                                    <a href="<?= esc_url($url); ?>">
                                        <?= esc_html($publisher_name); ?>
                                    </a>
                                </span>
                            <?php } else { ?>

                                <span id="book-standard-by" class="value">
                                    <?= esc_html($publisher_name); ?>
                                </span>
                            <?php } ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>



                <?php if (!empty($data['pages'])): ?>
                    <div class="detail-row">
                        <span class="label"><strong>• </strong> Pages:</span>
                        <span class="value"><?= esc_html($data['pages']); ?></span>
                    </div>
                <?php endif; ?>



                <?php if (!empty($data['languages'])): ?>
                    <div class="detail-row">
                        <span class="label"><strong>•</strong> Languages:</span>
                        <span class="value"><?= esc_html($data['languages']); ?></span>

                    </div>
                <?php endif; ?>

                <?php
                // Kiểm tra nếu `printPrice` hoặc `ebookPrice` có giá trị thì hiển thị phần Formats
                if (!empty($data['printPrice']) || !empty($data['ebookPrice'])): ?>
                    <div class="detail-row">
                        <span class="label"><strong>• </strong> Formats:</span>
                        <span class="value">
                            <?php
                            $formats = [];

                            // Kiểm tra và thêm "Printed" nếu `printPrice` có giá trị
                            if (isset($data['printPrice']) && !empty($data['printPrice'])) {
                                $formats[] = 'Printed';
                            }


                            if (isset($data['ebookPrice']) && !empty($data['ebookPrice'])) {
                                $formats[] = 'eBook';
                            }


                            if (!empty($formats)) {
                                echo implode(', ', $formats);
                            }
                            ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

        </div>
        <div id="full-description" class="tab-content" style="display:none;">
            <div class="preface">
                <h2 class="title">Preface</h2>
                <p class="content"><?= esc_html($data['fullDescription']); ?> </p>
            </div>

        </div>

        <!-- Các tài liệu liên quan -->
        <div class="versions">
            <h2>Document History</h2>
            <div class="news-selector">
                <!-- <label for="news">Sort by:</label>
                <select id="news" name="news">
                    <option value="newest">Newest</option>
                    <option value="Oldest">Oldest</option>
                </select> -->
            </div>
        </div>

        <div class="related-items">

        </div>
        <!-- phần dưới -->
        <div class="document-list">
            <?php
            if (!empty($data['documentHistoryProductId'])) {

                $ids = explode(';', $data['documentHistoryProductId']);
                $ids = array_map('trim', $ids);
                $ids_json = json_encode($ids);

            ?>
                <div id="document-history" data-ids='<?php echo $ids_json; ?>'></div>
            <?php
            } else {
                echo '<p>No standards.</p>';
            }
            ?>

        </div>
    </div>