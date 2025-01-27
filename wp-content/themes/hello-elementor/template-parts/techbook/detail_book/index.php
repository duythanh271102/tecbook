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

$product_id = get_query_var('book_id');

$api_url = 'https://115.84.178.66:8028/api/Documents/GetById';

$api_data = array(
    'tokenKey' => '4XwMBElYC3xgZeIW0IZ1H42zyvDNM5h7',
    'item' => array(
        'id' => $product_id
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
        $product_data = $data['data'];
        $custom_title = !empty($product_data['standardTitle']) ? $product_data['standardTitle'] : 'Trang chi tiết';

        add_filter('pre_get_document_title', function ($title) use ($custom_title) {
            return $custom_title;
        });
    } else {
        echo "Error retrieving data from the API";
    }
}

wp_enqueue_script('index', get_template_directory_uri() . '/template-parts/techbook/detail_book/index.js', array('jquery'), null, true);

?>


<div id="loading-container">
    <i class="fas fa-spinner"></i>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</div>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/detail_book/index.css">
<!-- <script src="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/detail_book/index.js"></script> -->

<div class="container-fullwidth">

    <div class="container-boxed">
        <div class=" title-home">
            <a href="<?php echo home_url(); ?>/home/" id="home-link">Home</a> &gt;
            <a href="<?php echo home_url(); ?>/book/" id="home-link">Book</a> &gt;

            <span style="color: #1E00AE;"> <?= esc_html($product_data['title']); ?> </span>
        </div>
    </div>
    <div class="container-boxed">
        <div class="product-detail">
            <div class="book-detail-container">
                <!-- Bên trái: Hình ảnh sách và các nút -->
                <div class="book-image-container product-item-book" data-book-id="<?php echo $product_id; ?>">
                    <img
                        src="<?php echo !empty($product_data['isbn']) ? 'https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/' . esc_attr($product_data['isbn']) . '.jpg' : home_url() . '/wp-content/uploads/2024/09/Rectangle-17873.png'; ?>"
                        alt="Book Image"
                        class="book-image"
                        onerror="
                        let imgElement = this;
                        let extensions = ['jpg', 'png', 'jpeg', 'webp', 'gif'];
                        let currentExtensionIndex = 1; 
                        let baseSrc = '<?php echo !empty($product_data['isbn']) ? 'https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/' . esc_attr($product_data['isbn']) : ''; ?>';

                        function tryNextExtension() {
                            if (currentExtensionIndex < extensions.length) {
                                imgElement.src = baseSrc + '.' + extensions[currentExtensionIndex];
                                currentExtensionIndex++;
                            } else {
                                imgElement.src = '<?php echo home_url(); ?>/wp-content/uploads/2024/09/Rectangle-17873.png';
                            }
                        }

                        imgElement.onerror = tryNextExtension;
                        tryNextExtension();
                    ">

                    <div class="book-icons">
                        <!-- <button class="butoon-book-icon1" id="butoon-book-icon1">
                            <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-5.svg" alt="Icon 2">
                        </button> -->
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
                    <h1 id="book-title" class="book-title"><?= esc_html($product_data['title']); ?></h1>
                    <!-- <h2 id="book-subtitle" class="book-subtitle"><?= esc_html($product_data['subjects']); ?></h2> -->
                    <p><strong>Author:</strong> <span id="book-author" class="book-standard-by"><?= esc_html($product_data['author']); ?></span></p>
                    <p><strong>Publisher:</strong> <span id="book-standard-by" class="book-standard-by"><?= esc_html($product_data['publisher']); ?></span></p>
                    <p><strong>Publication date:</strong> <span id="book-published-date" class="book-published-date"><?= esc_html($product_data['publicationDate']); ?></span></p>

                    <p><strong>Abstract:</strong></p>
                    <p><span id="book-abstract" class="abstract-text"><?= esc_html($product_data['abstract']); ?></span></p>
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

        <div class="formats-container product-item-book" data-book-id="<?php echo $product_id; ?>" data-book-name="<?= esc_html($product_data['title']); ?>">
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

            <div class="format-row">
                <div class="format-label">
                    <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Frame-225-1.svg" alt="E-Book">
                </div>
                <div class="availability">Download</div>
                <div class="price">
                    <?php
                        $price_factor = floatval(get_option('techbookapi_price_factor', 1));
                        $original_price_ebook = floatval($product_data['priceeBook']);
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
                <div class="price">
                    <?php
                        $price_factor = floatval(get_option('techbookapi_price_factor', 1));
                        $original_price = floatval($product_data['pricePrint']);

                        if ($original_price == 0) {
                            echo '<span class="discount">Please contact admin for price</span>';
                            $final_price = 0;
                        } else {
                            $final_price = $original_price * $price_factor;
                            echo '<span class="discount">' . esc_html($final_price) . '$</span>';
                        } 
                    ?>
                </div>
                <div class="cart-item-quantity">
                    <input type="number" min="0" class="qty-input" data-book-quantity="quantity_price_print" value="1">
                </div>
                <div class="actions">
                    <?php if ($final_price == 0) { ?>
                        <button class="cannot-add-to-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 20 22" fill="none">
                                <path d="M14.0004 8V5C14.0004 2.79086 12.2095 1 10.0004 1C7.79123 1 6.00037 2.79086 6.00037 5V8M1.59237 9.35196L0.992373 15.752C0.821775 17.5717 0.736477 18.4815 1.03842 19.1843C1.30367 19.8016 1.76849 20.3121 2.35839 20.6338C3.0299 21 3.94374 21 5.77142 21H14.2293C16.057 21 16.9708 21 17.6423 20.6338C18.2322 20.3121 18.6971 19.8016 18.9623 19.1843C19.2643 18.4815 19.179 17.5717 19.0084 15.752L18.4084 9.35197C18.2643 7.81535 18.1923 7.04704 17.8467 6.46616C17.5424 5.95458 17.0927 5.54511 16.555 5.28984C15.9444 5 15.1727 5 13.6293 5L6.37142 5C4.82806 5 4.05638 5 3.44579 5.28984C2.90803 5.54511 2.45838 5.95458 2.15403 6.46616C1.80846 7.04704 1.73643 7.81534 1.59237 9.35196Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="add_botton">Add to cart</span>
                        </button>
                    <?php } else { ?>
                        <button class="add-to-cart btn-cart-detail hunghungh" data-book-pricebook="<?php echo esc_html($final_price); ?>" data-book-price="price_print">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 20 22" fill="none">
                                <path d="M14.0004 8V5C14.0004 2.79086 12.2095 1 10.0004 1C7.79123 1 6.00037 2.79086 6.00037 5V8M1.59237 9.35196L0.992373 15.752C0.821775 17.5717 0.736477 18.4815 1.03842 19.1843C1.30367 19.8016 1.76849 20.3121 2.35839 20.6338C3.0299 21 3.94374 21 5.77142 21H14.2293C16.057 21 16.9708 21 17.6423 20.6338C18.2322 20.3121 18.6971 19.8016 18.9623 19.1843C19.2643 18.4815 19.179 17.5717 19.0084 15.752L18.4084 9.35197C18.2643 7.81535 18.1923 7.04704 17.8467 6.46616C17.5424 5.95458 17.0927 5.54511 16.555 5.28984C15.9444 5 15.1727 5 13.6293 5L6.37142 5C4.82806 5 4.05638 5 3.44579 5.28984C2.90803 5.54511 2.45838 5.95458 2.15403 6.46616C1.80846 7.04704 1.73643 7.81534 1.59237 9.35196Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="add_botton">Add to cart</span>
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="formats-container-moblie product-item-book" data-book-id="<?php echo $product_id; ?>" data-book-name="<?= esc_html($product_data['title']); ?>">
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
                            $original_price_ebook = floatval($product_data['priceeBook']);
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
                            $original_price = floatval($product_data['pricePrint']);

                            if ($original_price == 0) {
                                echo '<span class="discount">Please contact admin for price</span>';
                                $final_price = 0;
                            } else {
                                $final_price = $original_price * $price_factor;
                                echo '<span class="discount">' . esc_html($final_price) . '$</span>';
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
                    <?php if ($final_price == 0) { ?>
                        <button class="cannot-add-to-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 20 22" fill="none">
                                <path d="M14.0004 8V5C14.0004 2.79086 12.2095 1 10.0004 1C7.79123 1 6.00037 2.79086 6.00037 5V8M1.59237 9.35196L0.992373 15.752C0.821775 17.5717 0.736477 18.4815 1.03842 19.1843C1.30367 19.8016 1.76849 20.3121 2.35839 20.6338C3.0299 21 3.94374 21 5.77142 21H14.2293C16.057 21 16.9708 21 17.6423 20.6338C18.2322 20.3121 18.6971 19.8016 18.9623 19.1843C19.2643 18.4815 19.179 17.5717 19.0084 15.752L18.4084 9.35197C18.2643 7.81535 18.1923 7.04704 17.8467 6.46616C17.5424 5.95458 17.0927 5.54511 16.555 5.28984C15.9444 5 15.1727 5 13.6293 5L6.37142 5C4.82806 5 4.05638 5 3.44579 5.28984C2.90803 5.54511 2.45838 5.95458 2.15403 6.46616C1.80846 7.04704 1.73643 7.81534 1.59237 9.35196Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="add_botton">Add to cart</span>
                        </button>
                    <?php } else { ?>
                        <button class="add-to-cart btn-cart-detail hunghungh" data-book-pricebook="<?php echo esc_html($final_price); ?>" data-book-price="price_print">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 20 22" fill="none">
                                <path d="M14.0004 8V5C14.0004 2.79086 12.2095 1 10.0004 1C7.79123 1 6.00037 2.79086 6.00037 5V8M1.59237 9.35196L0.992373 15.752C0.821775 17.5717 0.736477 18.4815 1.03842 19.1843C1.30367 19.8016 1.76849 20.3121 2.35839 20.6338C3.0299 21 3.94374 21 5.77142 21H14.2293C16.057 21 16.9708 21 17.6423 20.6338C18.2322 20.3121 18.6971 19.8016 18.9623 19.1843C19.2643 18.4815 19.179 17.5717 19.0084 15.752L18.4084 9.35197C18.2643 7.81535 18.1923 7.04704 17.8467 6.46616C17.5424 5.95458 17.0927 5.54511 16.555 5.28984C15.9444 5 15.1727 5 13.6293 5L6.37142 5C4.82806 5 4.05638 5 3.44579 5.28984C2.90803 5.54511 2.45838 5.95458 2.15403 6.46616C1.80846 7.04704 1.73643 7.81534 1.59237 9.35196Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="add_botton">Add to cart</span>
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>




        <div class="container-boxed">

            <!-- Các phần mô tả -->
            <div class="tabs">
                <button class="tab-link active" onclick="openTab(event, 'product-details')">Product Details</button>
                <button class="tab-link" onclick="openTab(event, 'full-description')">Full Description</button>
            </div>



            <div id="product-details" class="tab-content">
                <div class="book-details">
                    <?php if (!empty($product_data['title'])): ?>
                        <div class="detail-row">
                            <span class="label"><strong>• </strong> Title:</span>
                            <span class="value"><?= esc_html($product_data['title']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($product_data['subjects'])): ?>
                        <?php
                        $codes = preg_split('/[*;,]+/', $product_data['subjects']);
                        $display = array();

                        global $wpdb;
                        $table_name = $wpdb->prefix . 'tecbook_subjects';

                        foreach ($codes as $code) {
                            $code = trim($code);
                            if (!empty($code)) {
                                $name = $wpdb->get_var(
                                    $wpdb->prepare(
                                        "SELECT subjects FROM $table_name WHERE code = %s",
                                        $code
                                    )
                                );

                                // Chỉ tạo đường link nếu tìm thấy tên trong bảng
                                if (!empty($name)) {
                                    $display[] = '<a href="' . esc_url(home_url('/techbook/search-book/')) . '?subject=' . urlencode($name) . '&code=' . urlencode($code) . '">' . esc_html($name) . '</a>';
                                } else {
                                    // Nếu không tìm thấy tên, chỉ hiển thị mã code mà không có đường link
                                    $display[] = esc_html($code);
                                }
                            }
                        }

                        $direct_codes = array_filter($display, function ($item) {
                            return stripos($item, 'C') !== false;
                        });
                        $detailed_names = array_diff($display, $direct_codes);

                        $codes_str = implode('<br>', $direct_codes);
                        $names_str = implode('<br>', $detailed_names);
                        ?>

                        <div class="detail-row">
                            <?php if (!empty($codes_str)): ?>
                                <span class="label"><strong>• </strong> Subject:</span>
                                <span class="value"><?= wp_kses($codes_str, array('a' => array('href' => array()), 'br' => array())); ?></span>
                            <?php endif; ?>

                            <?php if (!empty($names_str)): ?>
                                <br>
                                <span class="label"><strong>• </strong> Subjects:</span>
                                <span class="value"><?= wp_kses($names_str, array('a' => array('href' => array()), 'br' => array())); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>






                    <?php if (!empty($product_data['publisher'])): ?>
                        <div class="detail-row">
                            <span class="label"><strong>• </strong> Published:</span>
                            <span class="value"><?= esc_html($product_data['publisher']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($product_data['author'])): ?>
                        <div class="detail-row">
                            <span class="label"><strong>• </strong> Author:</span>
                            <span class="value"><?= esc_html($product_data['author']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($product_data['publicationDate'])): ?>
                        <div class="detail-row">
                            <span class="label"><strong>• </strong> Published Date:</span>
                            <span class="value"><?= esc_html($product_data['publicationDate']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($product_data['isbn'])): ?>
                        <div class="detail-row">
                            <span class="label"><strong>• </strong> ISBN (International Standard Book Number):</span>
                            <span class="value"><?= esc_html($product_data['isbn']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($product_data['page'])): ?>
                        <div class="detail-row">
                            <span class="label"><strong>• </strong> Pages:</span>
                            <span class="value"><?= esc_html($product_data['page']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>




        </div>
        <div id="full-description" class="tab-content" style="display:none;">
            <!-- Nội dung mô tả đầy đủ -->
            <div class="preface">
                <h2 class="title">Preface</h2>
                <p class="content"><?= esc_html($product_data['abstract']); ?> </p>
            </div>


        </div>

        <!-- Các tài liệu liên quan -->
        <!-- <div class="versions">
            <h2>Document History</h2>
            <div class="news-selector">
                <label for="news">Sort by:</label>
                <select id="news" name="news">
                    <option value="newest">Newest</option>
                    <option value="Oldest">Oldest</option>
                </select>
            </div>
        </div>

        <div class="related-items">

        </div>
        <div class="document-list">
            <?php
            if ($product) {
                // Kiểm tra xem file product-list-book1.php có tồn tại hay không
                if (file_exists(get_template_directory() . '/template-parts/techbook/product-list/product-list-book1.php')) {
                    include get_template_directory() . '/template-parts/techbook/product-list/product-list-book1.php';
                } else {
                    echo '<p>Template not found.</p>';
                }
            } else {
                echo '<p>No product found for this ID.</p>';
            }
            ?>
        </div> -->
    </div>