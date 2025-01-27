<?php

/**
 * The template for displaying footer.
 *
 * @package HelloElementor
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


$products = get_all_products();


$documents = get_all_standards();
?>


<?php
function enqueue_ajax_script()
{
?>
    <script type="text/javascript">
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    </script>
<?php
}
add_action('wp_head', 'enqueue_ajax_script');
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/Home/index.css">
<script src="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/Home/index.js"></script>





<div class="container-fullwidth">
    <div class="container-boxed">
        <div class="container">
            <!-- Sidebar Left (25%) -->
            <div class="sidebar" id="sidebar">
                <!-- Header toggle buttons -->
                <div class="sidebar-header">
                    <button id="publisher-btn" class="tab active">
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/check-verified-03.svg" alt="Publisher Icon" class="icon">
                        <?php echo esc_attr__('Standards', 'hello-elementor'); ?>
                    </button>
                    <button id="books-btn" class="tab">
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/book.svg" alt="Books Icon" class="icon">
                        <?php echo esc_attr__('Books', 'hello-elementor'); ?>
                    </button>
                </div>


                <!-- Publisher Content -->
                <div id="publisher-content" class="content active">
                    <h3 class="header-with-icon">
                        <span class="icon-text" translate="no">
                            <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/check-verified-03-1.svg" alt="Publisher Icon" class="icon">
                            <?php _e('List of Publisher', 'hello-elementor'); ?>
                        </span>

                        <span class="view-more"><a href="<?php echo get_site_url(); ?>/publisher/"><?php echo esc_attr__('View more', 'hello-elementor'); ?> ></a></span>
                    </h3>

                    <div class="publisher-container">
                        <div class="letters-column">
                            <ul class="letters-list">
                                <?php foreach (range('A', 'Z') as $letter): ?>
                                    <li><a href="#" class="letter"><?php echo $letter; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Danh sách nhà xuất bản -->
                        <div class="publishers-column">
                            <div id="loading-container">
                                <i class="fas fa-spinner"></i>
                                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

                            </div>


                            <ul class="publishers-list">
                                <!-- Nội dung nhà xuất bản sẽ được cập nhật tại đây -->
                            </ul>
                        </div>
                    </div>

                </div>

                <!-- Books Content -->
                <div id="books-content" class="content">
                    <h3 class="header-with-icon">
                        <span class="icon-text">
                            <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/check-verified-03-1.svg" alt="Publisher Icon" class="icon">
                            <?php _e('Subject', 'hello-elementor'); ?>
                        </span>
                        <!-- <span class="view-more"><a href="#">View more ></a></span> -->
                    </h3>
                    <ul class="topics-list">
                        <?php
                        $subjects = get_all_subjects();

                        if ($subjects) {
                            foreach ($subjects as $subject) {
                                $subject_name = urlencode($subject->subjects);
                                $subject_code = urlencode($subject->code);

                                $locale = get_locale();

                                if ($locale === 'vi') {
                                    echo '<li><a href="' . home_url('/vi/sach/?subject=' . $subject_name . '&code=' . $subject_code) . '">' . esc_html($subject->subjects) . '</a><span class="arrow">&rsaquo;</span></li>';
                                } else {
                                    echo '<li><a href="' . home_url('/books/?subject=' . $subject_name . '&code=' . $subject_code) . '">' . esc_html($subject->subjects) . '</a><span class="arrow">&rsaquo;</span></li>';
                                }
                            }
                        } else {
                            echo '<li>No Subject found.</li>';
                        }
                        ?>
                    </ul>

                    <!-- <div class="thanhngang" ></div>
                    <h4><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/award.svg" alt="Publisher Icon" class="icon">Special Book Collections</h4>
                    <ul class="collections-list">
                        <li><a href="#">Food Science Discipline</a><span class="arrow">&rsaquo;</span></li>
                        <li><a href="#">Extraction and Production</a><span class="arrow">&rsaquo;</span></li>
                        <li><a href="#">Environmental Engineering</a><span class="arrow">&rsaquo;</span></li>
                        <li><a href="#">Facility and Maintenance Engineering</a><span class="arrow">&rsaquo;</span></li>
                        <li><a href="#">Fire Protection and Safety</a><span class="arrow">&rsaquo;</span></li>
                        <li><a href="#">Heat and Mass Transfer</a><span class="arrow">&rsaquo;</span></li>
                        <li><a href="#">Highway Transportation</a><span class="arrow">&rsaquo;</span></li>
                        <li><a href="#">Process Safety</a><span class="arrow">&rsaquo;</span></li>
                    </ul>-->
                </div>
            </div>
            <div class="drag-handle">
                <span class="arrow"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/right-arrow.png" alt="icon"></span>
            </div>


            <!-- Overlay -->
            <div class="overlay"></div>

            <!-- Main Content (75%) -->
            <div class="main-content">
                <!-- Banner Slider -->
                <div class="slider-container">
                    <div class="slide">
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/Banner.png" alt="Banner 1">
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/Banner.png" alt="Banner 2">
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/Banner.png" alt="Banner 3">
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/Banner.png" alt="Banner 4">
                    </div>
                    <div class="dots">
                        <span class="dot active"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                </div>





                <!-- Featured Standards Section -->
                <div class="featured-section">
                    <h2>
                        <span><?php _e('Featured Standards', 'hello-elementor'); ?></span>

                    </h2>
                </div>

                <!-- <div class="standard-tabs">
                    <button class="tab-item active">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        ANSI/ (AAMA) 001
                    </button>
                    <button class="tab-item">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        <a>ISO 9001</a>
                    </button>
                    <button class="tab-item">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        <a>ISO 20121</a>
                    </button>
                    <button class="tab-item">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        <a>CAC/GL 68</a>
                    </button>
                    <button class="tab-item">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        <a>CODEX STAN 177</a>
                    </button>
                    <button class="tab-item">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        <a>CAC/GL 3</a>
                    </button>
                    <button class="tab-item">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        <a> TCVN 4032:1985</a>
                    </button>
                </div> -->


                <div class="carousel">
                    <button class="prev-btn" id="prev-btn1">&#10094;</button> <!-- Nút trái -->
                    <div class="product-slider1">
                        <div class="product-list1">
                            <?php
                            global $wpdb;
                            $table_name = $wpdb->prefix . 'tecbook_standards';

                            // Lấy danh sách các documents có featured = 1
                            $documents = $wpdb->get_results("SELECT * FROM $table_name WHERE featured = 1");

                            if (!empty($documents)): ?>
                                <?php
                                $total_documents = count($documents);
                                $limit = min($total_documents, 30);
                                ?>
                                <?php for ($i = 0; $i < $limit; $i++): ?>
                                    <?php $document = $documents[$i]; ?>
                                    <?php include get_template_directory() . '/template-parts/techbook/product-list/product-list-publisher2.php'; ?>
                                <?php endfor; ?>
                            <?php else: ?>
                                <p><?php _e('No products available at the moment.', 'hello-elementor'); ?></p>
                            <?php endif; ?>

                        </div>
                    </div>
                    <button class="next-btn" id="next-btn1">&#10095;</button> <!-- Nút phải -->
                </div>



                <!-- Featured Books Section-->
                <div class="featured-section">
                    <h2> <span> <?php _e('Featured Books', 'hello-elementor'); ?> </span>
                        <!-- <span class="view-more"><a href="#">View more ></a></span> -->
                    </h2>
                </div>

                <!-- <div class="standard-tabs">
                    <button class="tab-item active">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        ANSI/ (AAMA) 001
                    </button>
                    <button class="tab-item">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        <a>ISO 9001</a>
                    </button>
                    <button class="tab-item">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        <a>ISO 20121</a>
                    </button>
                    <button class="tab-item">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        <a>CAC/GL 68</a>
                    </button>
                    <button class="tab-item">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        <a>CODEX STAN 177</a>
                    </button>
                    <button class="tab-item">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        <a>CAC/GL 3</a>
                    </button>
                    <button class="tab-item">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        <a> TCVN 4032:1985</a>
                    </button>
                </div> -->


                <div class="carousel">
                    <button class="prev-btn" id="prev-btn2">&#10094;</button>
                    <!-- Product Slider -->
                    <div class="product-slider2">
                        <div class="product-list2">

                            <?php
                            global $wpdb;
                            $table_name = $wpdb->prefix . 'tecbook_books_cache';

                            $products = $wpdb->get_results("SELECT * FROM $table_name WHERE featured = 1");

                            if (!empty($products)): ?>
                                <?php

                                $total_products = count($products);
                                $limit = min($total_products, 30);
                                ?>
                                <?php for ($i = 0; $i < $limit; $i++): ?>
                                    <?php $product = $products[$i]; ?>
                                    <?php include get_template_directory() . '/template-parts/techbook/product-list/product-list-book.php'; ?>
                                <?php endfor; ?>
                            <?php else: ?>
                                <p><?php _e('No products available at the moment.', 'hello-elementor'); ?></p>
                            <?php endif; ?>


                        </div>
                    </div>
                    <button class="next-btn" id="next-btn2">&#10095;</button>
                </div>
            </div>
        </div>
    </div>


    <!-- phần tiếp -->

    <div class="container-fullwidth1">
        <div class="container-boxed">
            <div class="title1"><?php _e('New Arrivals', 'hello-elementor'); ?> </div>

            <div class="buton1">
                <button id="Standards1" class="Standards1"><?php _e('Standards', 'hello-elementor'); ?></button>
                <button id="Books1" class="Books1"><?php _e('Books', 'hello-elementor'); ?></button>
            </div>

            <div id="standards1-content" class="content-section">

                <div class="carousel1">
                    <button class="prev-btn" id="prev-btn">&#10094;</button>
                    <div class="product-slider">
                        <div class="product-list">

                            <?php
                            global $wpdb;
                            $table_name = $wpdb->prefix . 'tecbook_standards';

                            // Lấy danh sách các documents có featured = 1
                            $documents = $wpdb->get_results("SELECT * FROM $table_name WHERE newArrival = 1");

                            if (!empty($documents)): ?>
                                <?php
                                $total_documents = count($documents);
                                $limit = min($total_documents, 30);
                                ?>
                                <?php for ($i = 0; $i < $limit; $i++): ?>
                                    <?php $document = $documents[$i]; ?>
                                    <?php include get_template_directory() . '/template-parts/techbook/product-list/product-list-publisher2.php'; ?>
                                <?php endfor; ?>
                            <?php else: ?>
                                <p><?php _e('No products available at the moment.', 'hello-elementor'); ?></p>
                            <?php endif; ?>

                        </div>
                    </div>
                    <button class="next-btn" id="next-btn">&#10095;</button>
                </div>

            </div>

            <div id="books1-content" class="content-section" style="display: none;">
                <div class="carousel1-book">
                    <button class="prev-btn" id="prev-btn-book">&#10094;</button>
                    <div class="product-slider-book">
                        <div class="product-list-book">

                            <?php
                            global $wpdb;
                            $table_name = $wpdb->prefix . 'tecbook_books_cache';

                            $products = $wpdb->get_results("SELECT * FROM $table_name WHERE newArrival = 1");

                            if (!empty($products)): ?>
                                <?php

                                $total_products = count($products);
                                $limit = min($total_products, 30);
                                ?>
                                <?php for ($i = 0; $i < $limit; $i++): ?>
                                    <?php $product = $products[$i]; ?>
                                    <?php include get_template_directory() . '/template-parts/techbook/product-list/product-list-book.php'; ?>
                                <?php endfor; ?>
                            <?php else: ?>
                                <p><?php _e('No products available at the moment.', 'hello-elementor'); ?></p>
                            <?php endif; ?>

                        </div>
                    </div>
                    <button class="next-btn" id="next-btn-book">&#10095;</button>
                </div>
            </div>

        </div>
    </div>


    <!-- Phần 3 -->

    <!-- Phần 3 -->

    <div class="container-fullwidth">
        <div class="container-boxed">

            <div class="special-offer">
                <div class="title2"><?php _e('Special Offer', 'hello-elementor'); ?></div>
                <div class="filter-buttons">
                    <button id="standards" class="filter-btn"><?php _e('Standards', 'hello-elementor'); ?></button>
                    <button id="books" class="filter-btn"><?php _e('Books', 'hello-elementor'); ?></button>
                </div>
            </div>

            <div class="product-display" id="product-book">
                <!-- Left Section (30%) -->
                <div class="left-section">
                    <?php
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'tecbook_books_cache';

                    $products = $wpdb->get_results("SELECT * FROM $table_name WHERE specialOffer = 1 LIMIT 4");

                    if (!empty($products)):
                        foreach ($products as $product):
                            include get_template_directory() . '/template-parts/techbook/product-list/product-list-book.php';
                        endforeach;
                    else:
                        echo '<p>No products available with special offers.</p>';
                    endif;
                    ?>
                </div>


                <!-- Center Section (40%) -->
                <div class="center-section">
                    <?php
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'tecbook_books_cache';

                    $product = $wpdb->get_row("SELECT * FROM $table_name WHERE specialOffer = 1 ORDER BY id LIMIT 4, 1");

                    if ($product):
                        $price_factor = floatval(get_option('techbookapi_price_factor', 1));
                        $adjusted_pricePrint = isset($product->pricePrint) ? $product->pricePrint * $price_factor : null;
                        $adjusted_ebookPrice = isset($product->ebookPrice) ? $product->ebookPrice * $price_factor : null;
                    ?>

                        <div class="product-card center-product product-item-book" data-book-id="<?php echo $product->id; ?>">

                            <p class="discount <?= isset($product->discount) && !empty($product->discount) ? 'has-discount' : 'no-discount'; ?>">
                                <?= isset($product->discount) && !empty($product->discount) ? $product->discount : '&nbsp;'; ?>
                            </p>

                            <a href="<?php echo get_site_url(); ?>/detail/book-<?php echo isset($product->id) ? intval($product->id) : ''; ?>" class="product-link">
                                <img
                                    src="<?php echo isset($product->isbn) ? 'https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/' . $product->isbn . '.jpg' : get_site_url() . '/wp-content/uploads/2024/09/Rectangle-17873.png'; ?>"
                                    alt="Product Image" class="product-image-center"
                                    onerror="
                        let imgElement = this;
                        let extensions = ['jpg', 'png', 'jpeg', 'webp', 'gif'];
                        let currentExtensionIndex = 1;
                        let baseSrc = '<?php echo isset($product->isbn) ? 'https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/' . $product->isbn : ''; ?>';

                        function tryNextExtension() {
                            if (currentExtensionIndex < extensions.length) {
                                imgElement.src = baseSrc + '.' + extensions[currentExtensionIndex];
                                currentExtensionIndex++;
                            } else {
                                imgElement.src = '<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/Rectangle-17873.png';
                            }
                        }

                        imgElement.onerror = tryNextExtension;
                        tryNextExtension();
                    ">


                                <!-- <p class="product-category1"><?= isset($product->subjects) && !empty($product->subjects) ? $product->subjects : '&nbsp;'; ?></p> -->
                                <h3 class="product-title1"><?= isset($product->title) && !empty($product->title) ? $product->title : '&nbsp;'; ?></h3>

                            </a>
                            <p class="product-group1"><?= isset($product->author) && !empty($product->author) ? $product->author : '&nbsp;'; ?></p>

                            <!-- <p class="product-price1">
                                <?php
                                if (isset($adjusted_pricePrint)) {
                                    echo number_format($adjusted_pricePrint, 2) . ' $';
                                } elseif (isset($adjusted_ebookPrice)) {
                                    echo number_format($adjusted_ebookPrice, 2) . ' $';
                                } else {
                                    echo ' ';
                                }
                                ?>
                            </p> -->

                            <p class="product-info"><?= isset($product->abstract) && !empty($product->abstract) ? $product->abstract : '&nbsp;'; ?></p>

                            <div class="button-container">
                                <button class="btn-wishlist icon-wishlist">
                                    <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
                                        <path d="M15.1111 1.41016C18.6333 1.41016 21 4.76266 21 7.89016C21 14.2239 11.1778 19.4102 11 19.4102C10.8222 19.4102 1 14.2239 1 7.89016C1 4.76266 3.36667 1.41016 6.88889 1.41016C8.91111 1.41016 10.2333 2.43391 11 3.33391C11.7667 2.43391 13.0889 1.41016 15.1111 1.41016Z"
                                            stroke="#1E00AE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>

                                    <?php _e('Add to wishlist', 'hello-elementor'); ?>
                                </button>
                                <!-- <button class="btn-cart icon-cart">
                                    <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/shopping-bag-02-2.svg" alt="cart icon"> Add to cart
                                </button> -->
                            </div>
                        </div>

                    <?php else: ?>
                        <p>No featured product available.</p>
                    <?php endif; ?>
                </div>

                <!-- Right Section (30%) -->
                <div class="right-section">
                    <?php
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'tecbook_books_cache';

                    $products = $wpdb->get_results("SELECT * FROM $table_name WHERE specialOffer = 1 LIMIT 4 OFFSET 5");

                    if (!empty($products)):
                        foreach ($products as $product):
                            include get_template_directory() . '/template-parts/techbook/product-list/product-list-book.php';
                        endforeach;
                    else:
                        echo '<p>No products available with special offers.</p>';
                    endif;
                    ?>
                </div>
            </div>




            <div class="product-display" id="product-standards">
                <!-- Left Section (30%) -->
                <div class="left-section">
                    <?php
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'tecbook_standards';

                    $documents = $wpdb->get_results("SELECT * FROM $table_name WHERE specialOffer = 1 LIMIT 4");

                    if (!empty($documents)):
                        foreach ($documents as $document):
                            include get_template_directory() . '/template-parts/techbook/product-list/product-list-publisher2.php';
                        endforeach;
                    else:
                        echo '<p>No products available with special offers.</p>';
                    endif;
                    ?>
                </div>


                <!-- Center Section (40%) -->

                <div class="center-section">
                    <?php
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'tecbook_standards';

                    $document = $wpdb->get_row("SELECT * FROM $table_name WHERE specialOffer = 1 ORDER BY id LIMIT 4, 1");

                    if ($document):
                        $price_factor = floatval(get_option('techbookapi_price_factor', 1));
                        $adjusted_prices = [];

                        if (!empty($document->pricePrint)) {
                            $adjusted_prices[] = $document->pricePrint * $price_factor;
                        }
                        if (!empty($document->ebookPrice)) {
                            $adjusted_prices[] = $document->ebookPrice * $price_factor;
                        }

                        $minPrice = !empty($adjusted_prices) ? min($adjusted_prices) : null;
                        $maxPrice = !empty($adjusted_prices) ? max($adjusted_prices) : null;
                    ?>

                        <div class="product-card center-product product-item-book" data-book-id="<?php echo $document->id; ?>">

                            <p class="discount <?= isset($document->discount) && !empty($product->discount) ? 'has-discount' : 'no-discount'; ?>">
                                <?= isset($document->discount) && !empty($document->discount) ? $document->document : '&nbsp;'; ?>
                            </p>

                            <a href="<?php echo get_site_url(); ?>/detail/standard-<?php echo isset($document->id) ? intval($document->id) : ''; ?>" class="product-link">
                                <img
                                    src="<?= isset($document->idProduct) && !empty($document->idProduct)
                                                ? 'https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/standards/cover/' . $document->idProduct . '.jpg'
                                                : get_site_url() . '/wp-content/uploads/2024/09/Rectangle-17873.png'; ?>"
                                    alt="Product Image" class="product-image-center"
                                    onerror="this.onerror=null; this.src='<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/Rectangle-17873.png';">

                            </a>


                            <h3 class="product-title1"><?= isset($document->referenceNumber) && !empty($document->referenceNumber) ? $document->referenceNumber : '&nbsp;'; ?></h3>

                            <p class="product-group1"><?= isset($document->standardby) && !empty($document->standardby) ? $document->standardby : '&nbsp;'; ?></p>

                            <!-- <p class="product-price1">
                                <?php
                                if ($minPrice !== null && $maxPrice !== null && $minPrice != $maxPrice) {
                                    echo number_format($minPrice, 2) . '$ - ' . number_format($maxPrice, 2) . '$';
                                } elseif ($minPrice !== null) {
                                    echo number_format($minPrice, 2) . '$';
                                } else {
                                    echo ' ';
                                }
                                ?>
                            </p> -->

                            <p class="product-info"><?= isset($document->standardTitle) && !empty($document->standardTitle) ? $document->standardTitle : '&nbsp;'; ?></p>

                            <div class="button-container">
                                <button class="btn-wishlist icon-wishlist">
                                    <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
                                        <path d="M15.1111 1.41016C18.6333 1.41016 21 4.76266 21 7.89016C21 14.2239 11.1778 19.4102 11 19.4102C10.8222 19.4102 1 14.2239 1 7.89016C1 4.76266 3.36667 1.41016 6.88889 1.41016C8.91111 1.41016 10.2333 2.43391 11 3.33391C11.7667 2.43391 13.0889 1.41016 15.1111 1.41016Z"
                                            stroke="#1E00AE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg><?php _e('Add to wishlist', 'hello-elementor'); ?>
                                </button>
                            </div>
                        </div>

                    <?php else: ?>
                        <p>No featured product available.</p>
                    <?php endif; ?>
                </div>


                <!-- Right Section (30%) -->
                <div class="right-section">
                    <?php
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'tecbook_standards';

                    $documents = $wpdb->get_results("SELECT * FROM $table_name WHERE specialOffer = 1 LIMIT 4 OFFSET 5");

                    if (!empty($documents)):
                        foreach ($documents as $document):
                            include get_template_directory() . '/template-parts/techbook/product-list/product-list-publisher2.php';
                        endforeach;
                    else:
                        echo '<p>No products available with special offers.</p>';
                    endif;
                    ?>


                </div>

            </div>
        </div>

        <!-- Phần 4 -->

        <div class="container-fullwidth">
            <div class="container-boxed">
                <div class="banner-container">
                    <div class="banner-item banner1">
                        <h2><?php _e('Banner', 'hello-elementor'); ?> 1</h2>
                        <!-- <a href="#" class="view-more">View more</a> -->
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/Container-20.png" alt="Banner 1 Image" class="banner-image">
                    </div>

                    <div class="banner-item banner2">
                        <h2><?php _e('Banner', 'hello-elementor'); ?> 2</h2>
                        <!-- <a href="#" class="view-more">View more</a> -->
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/img1-21.png.png" alt="Banner 2 Image" class="banner-image">
                    </div>

                    <div class="banner-item banner3">
                        <h2><?php _e('Banner', 'hello-elementor'); ?> 3</h2>
                        <!-- <a href="#" class="view-more">View more</a> -->
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/Container-21.png" alt="Banner 3 Image" class="banner-image">
                    </div>
                </div>


            </div>
        </div>



        <!-- Phần 5 -->

        <div class="container-fullwidth2">
            <div class="container-boxed">
                <div class="thanh-ngang"></div>

                <div class="featured-section">
                    <h2> <span> <?php _e('Top Seller Books', 'hello-elementor'); ?> </span>
                        <!-- <span class="view-more"><a href="#">View more ></a></span> -->
                    </h2>
                </div>

                <div class="top-sell-book">
                    <div class="anhphu">
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/Background-16.png" alt="anh" class="anhphu-banner">
                    </div>
                    <div class="san-pham-top-sell">
                        <div class="carousel">
                            <button class="prev-btn" id="prev-btn3">&#10094;</button>
                            <div class="product-slider3">
                                <div class="product-list3">
                                    <?php
                                    global $wpdb;
                                    $table_name = $wpdb->prefix . 'tecbook_books_cache';

                                    $products = $wpdb->get_results("SELECT * FROM $table_name WHERE bestSellers = 1");

                                    if (!empty($products)): ?>
                                        <?php

                                        $total_products = count($products);
                                        $limit = min($total_products, 30);
                                        ?>
                                        <?php for ($i = 0; $i < $limit; $i++): ?>
                                            <?php $product = $products[$i]; ?>
                                            <?php include get_template_directory() . '/template-parts/techbook/product-list/product-list-book.php'; ?>
                                        <?php endfor; ?>
                                    <?php else: ?>
                                        <p><?php _e('No products available at the moment.', 'hello-elementor'); ?> </p>
                                    <?php endif; ?>
                                </div>

                            </div>
                            <button class="next-btn" id="next-btn3">&#10095;</button>
                        </div>
                    </div>
                </div>

                <div class="thanh-ngang1"></div>

                <div class="featured-section">
                    <h2> <span><?php _e('Top Seller Standards ', 'hello-elementor'); ?> </span>
                        <!-- <span class="view-more"><a href="#">View more ></a></span> -->
                    </h2>
                </div>


                <div class="top-sell-standards">
                    <div class="anhphu">
                        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/Background-16.png" alt="anh" class="anhphu-banner">
                    </div>
                    <div class="san-pham-top-standards">
                        <div class="carousel">
                            <button class="prev-btn" id="prev-btn4">&#10094;</button>
                            <div class="product-slider4">
                                <div class="product-list4">
                                    <?php
                                    global $wpdb;
                                    $table_name = $wpdb->prefix . 'tecbook_standards';

                                    // Lấy danh sách các documents có featured = 1
                                    $documents = $wpdb->get_results("SELECT * FROM $table_name WHERE bestSellers = 1");

                                    if (!empty($documents)): ?>
                                        <?php
                                        $total_documents = count($documents);
                                        $limit = min($total_documents, 30);
                                        ?>
                                        <?php for ($i = 0; $i < $limit; $i++): ?>
                                            <?php $document = $documents[$i]; ?>
                                            <?php include get_template_directory() . '/template-parts/techbook/product-list/product-list-publisher2.php'; ?>
                                        <?php endfor; ?>
                                    <?php else: ?>
                                        <p><?php _e('No products available at the moment.', 'hello-elementor'); ?></p>
                                    <?php endif; ?>

                                </div>

                            </div>
                            <button class="next-btn" id="next-btn4">&#10095;</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>