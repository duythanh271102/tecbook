<?php

/**
 * The template for displaying footer.
 *
 * @package HelloElementor
 */

if (! defined('ABSPATH')) {
    exit; 
}
$products = get_all_products();
$items_per_page = 10;
$current_page = get_query_var('paged') ? get_query_var('paged') : 1;
$total_products = count($products);
$total_pages = ceil($total_products / $items_per_page);
$offset = ($current_page - 1) * $items_per_page;

$products_to_display = array_slice($products, $offset, $items_per_page);

$big = 999999999;

$pagination_args = array(
    'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
    'format'    => '?paged=%#%',
    'total'     => $total_pages,
    'current'   => max(1, $current_page),
    'show_all'  => false,
    'end_size'  => 1,
    'mid_size'  => 1,
    'type'      => 'plain',
);

$pagination_links = paginate_links($pagination_args);

$price_factor = floatval(get_option('techbookapi_price_factor', 1));
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/all_book/all_book.css">

<script src="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/all_book/all_book.js"></script>

<script>
    const price_factor = <?php echo json_encode($price_factor); ?>;
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>

<div class="container-fullwidth">
    <div class="container-boxed">
        <div class=" title-home">
            <a href="<?php echo home_url(); ?>/home/" id="home-link"><?php _e('Home', 'hello-elementor'); ?></a> > <span style="color: #1E00AE;"> <?php _e('Books', 'hello-elementor'); ?> </span>
        </div>
    </div>

    <div class="container-boxed-banner" style="background: linear-gradient(rgba(30, 0, 174, 0.8), rgba(30, 0, 174, 0.8)), url(<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/Banner-4.png);">
        <div class="titile-banner"> <?php _e('Search Books', 'hello-elementor'); ?> </div>
        <div class="search-bar">
            <input type="text" placeholder="<?php _e('Keyword', 'hello-elementor'); ?>" class="search-input">
            <!-- <div class="search-category-book">
            <span class="selected-option">Books categories</span>
            </div> -->
            <!-- Modal -->
            <!-- <div id="bookCategoryModal" class="modal-book">
              <div class="modal-content-book">
                <h2>Popular Books Categories</h2>
                <div class="thanh-blue"></div>
                <div class="categories">
                 
                </div>
               
              </div>
              <div class="thanh-trang"></div>
              <button class="view-all">View all ></button>
            </div> -->

            <button class="search-button"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/Icon-14.svg" alt="icon"></button>
        </div>
        <div class="advan-search" id="advan-search">
            <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/settings-01.svg" alt="icon">
            <a href="<?php echo get_site_url(); ?>/search-book/" class="advanced-search"><?php _e('Advanced search', 'hello-elementor'); ?></a>
        </div>
    </div>
    <div class="container-boxed">
        <div class="container">
            <!-- Sidebar Left (25%) -->
            <div class="sidebar" id="sidebar">
                <div class="categories-book">
                    <div class="header-book">
                        <span class="icon"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/book-1.svg" alt="icon"></span><?php _e('Books categories', 'hello-elementor'); ?>
                    </div>
                    <div class="year-selection">
                        <select id="select-ics">
                            <option value=""><?php echo __('All', 'hello-elementor'); ?> </option>
                            <?php
                                // Lấy tất cả các ngành công nghiệp (subjects)
                                $documents = get_all_subjects();
    
                                // Kiểm tra dữ liệu trả về
                                if (!empty($documents)) {
                                    foreach ($documents as $document) {
                                ?>
                                        <option value="<?php echo esc_attr($document->code); ?>">
                                            <?php echo esc_html($document->subjects); ?>
                                        </option>
                                <?php
                                    }
                                } else {
                                    echo '<option value="">No subjects found</option>';
                                }
                                ?>
                        </select>
                    </div>
                </div>

                <div class="categories-author">
                    <div class="header-author">
                        <span class="icon"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/user-edit.svg" alt="icon"></span> <?php _e('Author', 'hello-elementor'); ?>
                    </div>
                    <input type="text" id="author-text" placeholder="<?php _e('Text', 'hello-elementor'); ?>">
                </div>
                <!-- <div class="categories-ics">
                        <div class="header-ics">
                            <span class="icon"><img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/building-07.svg" alt="icon"></span> ICS Code
                        </div>
                        
                        <div class="ics-selection">
                        <select id="select-ics">
                            <option value="">All</option>
                            <?php
                            // Gọi hàm để lấy tất cả dữ liệu ICS codes
                            $ics_codes = get_all_ics_codes();

                            // Kiểm tra và hiển thị các `nameInEnglish` duy nhất với giá trị là `icsCode`
                            if (! empty($ics_codes)) {
                                foreach ($ics_codes as $ics_code) : ?>
                                    <option value="<?php echo esc_attr($ics_code->icsCode); ?>">
                                        <?php echo esc_html($ics_code->nameInEnglish); ?>
                                    </option>
                                <?php endforeach;
                            } else {
                                echo '<option value="">No ICS codes found</option>';
                            }
                                ?>
                        </select>
                        </div>
                        
                    </div> -->

                <div class="categories-ics">
                    <div class="header-ics">
                        <span class="icon">
                            <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/calendar-1.svg" alt="icon">
                        </span> <?php _e('Published year', 'hello-elementor'); ?>
                    </div>

                    <div class="year-selection">
                            <select id="pub-year">
                                <option value="">Select year</option>
                                <?php
                                // Lấy năm hiện tại
                                $currentYear = date('Y');

                                // Hiển thị các năm từ 2000 đến năm hiện tại
                                for ($year = 2000; $year <= $currentYear; $year++): ?>
                                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                </div>

                <!-- <div class="categories-ics">
                        <div class="header-ics">
                            <span class="icon">
                                <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/translate-02.svg" alt="icon">
                            </span> Languages
                        </div>

                        <div class="language-selection">
                            <label>
                                <input type="checkbox" name="language" value="English" checked> English
                            </label>
                            <label>
                                <input type="checkbox" name="language" value="Vietnamese"> Vietnamese
                            </label>
                            <label>
                                <input type="checkbox" name="language" value="French"> French
                            </label>
                            <label>
                                <input type="checkbox" name="language" value="Spanish"> Spanish
                            </label>
                            <label>
                                <input type="checkbox" name="language" value="Chinese"> Chinese
                            </label>
                        </div>

                    </div>

                    <div class="categories-ics">
                        <div class="header-ics">
                            <span class="icon">
                                <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/bank-note-01.svg" alt="icon">
                            </span> Formats
                        </div>

                        <div class="formats-selection">
                            <label>
                                <input type="checkbox" name="formats" value="PDF" checked> PDF
                            </label>
                            <label>
                                <input type="checkbox" name="formats" value="Online"> Online
                            </label>
                            <label>
                                <input type="checkbox" name="formats" value="French"> Hard book print
                            </label>
                            <label>
                                <input type="checkbox" name="formats" value="Doc"> Doc
                            </label>
                        </div>
                    </div> -->

                <div class="categories-ics" style="display: none;">
                    <div class="header-ics">
                        <span class="icon">
                            <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/bank-note-01.svg" alt="icon">
                        </span> <?php _e('Filter by price', 'hello-elementor'); ?>
                    </div>

                    <div class="filter-body">
                        <input type="range" id="priceRange" min="0" max="300" value="0">
                        <div class="filter-flex">
                            <label for="priceRange"><?php _e('Price', 'hello-elementor'); ?>: <span id="priceValue">$0-300$</span></label>

                        </div>

                    </div>

                </div>

                <button class="refresh-button"><?php _e('Refresh', 'hello-elementor'); ?></button>
                <button class="filter-button"><?php _e('Search', 'hello-elementor'); ?></button>

            </div>
            <div class="drag-handle">
                <span class="arrow"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/right-arrow.png" alt="icon"></span>
            </div>

            <!-- Overlay -->
            <div class="overlay"></div>

            <!-- Main Content (75%) -->
            <div class="main-content">
                <div class="container-title">
                    <p><?php _e('List of Books', 'hello-elementor'); ?></p>
                    <!-- <div class="flex2">
                        <p>Showing  <span id="showing-book">1-25 of 251 results</span></p>
                        <div class="thanh-doc"></div>

                        <div class="sort-newest">
                            <select id="sort-order">
                                <option value="newest">Newest</option>
                                <option value="oldest">Oldest</option>
                             
                            </select>
                        </div>
                    </div> -->
                </div>

                <!-- <div class="standard-tabs">
                    <button class="tab-item active">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                       <a >New arrival</a> 
                    </button>
                    <button class="tab-item">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/check-verified-03-2.svg" alt="icon" class="icon1">
                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Icon-10.svg" alt="icon" class="icon2">
                        <a>Best Sellers</a>
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
                </div> -->

                <div class="product-list"></div>

                <div id="page-size-select-container">
                    <label for="page-size-select" style="    margin-bottom: 8px; font-family: Ford Antenna; font-size: 14px; font-weight: 400; line-height: 19.6px; text-align: left; color: #2C2C2C;"><?php _e('Number of products per page', 'hello-elementor'); ?></label>
                    <select id="page-size-select">
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <!-- Hiển thị phân trang -->
                <div class="custom-pagination">
                    <?php echo $pagination_links; ?>
                </div>

                <div id="loading-container">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
            </div>
        </div>
    </div>
</div>