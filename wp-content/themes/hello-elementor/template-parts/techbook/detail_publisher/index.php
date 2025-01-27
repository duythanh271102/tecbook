<?php

/**
 * The template for displaying the publisher's details and publications.
 *
 * @package HelloElementor
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Retrieve the publisher ID from the URL
$organization_id = get_query_var('publisher_id');
$organization = get_publisher_by_id($organization_id);
$organization_data = prepare_publisher_data($organization);
$custom_title = !empty($organization_data['english_title']) ? $organization_data['english_title'] : 'Trang chi tiết';

add_filter('pre_get_document_title', function ($title) use ($custom_title) {
    return $custom_title;
});

// Các biến cho JavaScript
$english_title = esc_js($organization_data['english_title']);
$publisherCode = !empty($organization_data['publisher_code']) ? esc_js($organization_data['publisher_code']) : '';
$api_url = 'https://115.84.178.66:8028/api/Standards/GetPaging';
$token_key = '4XwMBElYC3xgZeIW0IZ1H42zyvDNM5h7';
$price_factor = floatval(get_option('techbookapi_price_factor', 1));

// Enqueue the script and localize variables for JavaScript
wp_enqueue_script('publisher-detail-script', get_template_directory_uri() . '/template-parts/techbook/detail_publisher/index.js', array('jquery'), '1.0', true);

wp_localize_script('publisher-detail-script', 'ajax_object', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'publisherCode' => $publisherCode,
    'siteUrl' => get_site_url(),
    'templateUrl' => get_template_directory_uri(),
    'priceFactor' => $price_factor, 
    'tokenKey' => $token_key,
));
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/detail_publisher/index.css">

<!-- Pass necessary variables to JavaScript -->
<script>
    var publisherCode = "<?php echo $publisherCode; ?>";
    var apiUrl = "<?php echo esc_url($api_url); ?>";
    var tokenKey = "<?php echo esc_js($token_key); ?>";
    var siteUrl = "<?php echo esc_url(get_site_url()); ?>";
    var templateUrl = "<?php echo esc_url(get_template_directory_uri()); ?>";
</script>

<script src="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/detail_publisher/index.js"></script>
<script type="text/javascript">
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>

<body>
    <div class="container-fullwidth">
        <div class="container-boxed">
            <div class="title-home">
                <a href="<?php echo home_url(); ?>/home/" class="home-link">Home</a> > <a href="<?php echo home_url(); ?>/book/" class="home-link">Publisher</a> >
                <p style="color:#1E00AE" id="title-header-id"><?= esc_html($organization_data['english_title']); ?></p>
            </div>
        </div>

        <div class="container-boxed-standards">
            <div class="header-standards" style="background: linear-gradient(rgba(30, 0, 174, 0.7), rgba(30, 0, 174, 0.7)), url(<?php echo home_url(); ?>/wp-content/uploads/2024/09/Banner-6.png);">
                <?php if (!empty($organization_data['avatarPath'])): ?>
                    <img src="https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/<?php echo esc_html($organization_data['avatarPath']); ?>"
                        alt="<?= esc_html($organization_data['related_ics_code']); ?> Logo" class="header__logo">
                <?php else: ?>
                    <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Rectangle-17873.png" alt="Default Logo" class="header__logo">
                <?php endif; ?>

                <div class="header__info">
                    <h1 class="header__title"><?= esc_html($organization_data['english_title']); ?></h1>
                    <?php if (!empty($organization_data['related_ics_code'])): ?>
                        <div class="header__publications">
                            <span><?= esc_html($organization_data['related_ics_code']); ?> Publications</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="about-standards">
                <h2 class="about__title">About</h2>
                <p class="about__text">
                    <?= esc_html($organization_data['english_description']); ?>
                </p>
            </div>

            <?php
            $keyword = $organization_data['keyword'];

            if (!empty($keyword)) {
                $tags = explode(',', $keyword);
            ?>
                <div class="tags">
                    <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/tag.svg" alt="icon" class="icon-tag">
                    <span class="title-tag">Tag:</span>
                    <?php foreach ($tags as $tag): ?>
                        <span class="tag"><?= esc_html(trim($tag)); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="container-boxed">
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'tecbook_standards';

            $publisher_code = esc_sql($organization_data['publisher_code']);

            $query = $wpdb->prepare(
                "SELECT * FROM $table_name WHERE featured = 1 AND standardby = %s",
                $publisher_code
            );

            $documents = $wpdb->get_results($query);

            if (!empty($documents)): ?>
                <div class="container-title">
                    <p>Featured Publications</p>
                </div>

                <div class="carousel1">
                    <button class="prev-btn-deatail" id="prev-btn-deatail">&#10094;</button>
                    <div class="product-slider">
                        <div class="product-list">
                            <?php
                            $total_documents = count($documents);
                            $limit = min($total_documents, 30);
                            for ($i = 0; $i < $limit; $i++):
                                $document = $documents[$i];
                                include get_template_directory() . '/template-parts/techbook/product-list/product-list-publisher2.php';
                            endfor;
                            ?>
                        </div>
                    </div>
                    <button class="next-btn-deatail" id="next-btn-deatail">&#10095;</button>
                </div>
            <?php else: ?>
                <!-- Không hiển thị gì khi không có sản phẩm -->
            <?php endif; ?>


            <div class="container-title">
                <p>List of Publications</p>
            </div>

            <div class="product-list1">

            </div>

            <div id="page-size-select-container">
                <label for="page-size-select">Number of products per page</label>
                <select id="page-size-select">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

            <!-- Pagination -->
            <div class="custom-pagination">

                <div id="loading-container">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

            </div>
        </div>
    </div>
</body>