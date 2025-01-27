<?php
/**
 * The template for displaying footer.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$products = get_all_products();
$product_id = get_query_var('book_id');
$product = get_product_by_id( $product_id );
$product_data = prepare_product_data( $product );

$items_per_page = 5;
$current_page = get_query_var('paged') ? get_query_var('paged') : 1;
$total_products = count($products);
$total_pages = ceil($total_products / $items_per_page);
$offset = ($current_page - 1) * $items_per_page;

// Cắt mảng products để lấy sản phẩm cho trang hiện tại
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
    'prev_next' => true,
    'prev_text' => __('« Prev'),
    'next_text' => __('Next »'),
    'type'      => 'plain',
);

$pagination_links = paginate_links($pagination_args);


function enqueue_ajax_script() {
    ?>
    <script type="text/javascript">
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    </script>
    <?php
}
add_action('wp_head', 'enqueue_ajax_script');

?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/wishlist/index.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/wishlist/index.js"></script>

<div id="loading-container"> 
    <i class="fas fa-spinner"></i> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</div>

<div class="container-fullwidth wishlist-page">
    <div class="container-boxed">
        <div class="title-home">
            <a href="<?php echo home_url(); ?>/home/" id="home-link"><?php esc_html_e('Home', 'hello-elementor'); ?></a> > <span style="color: #1E00AE;"> <?php esc_html_e('Wishlist', 'hello-elementor'); ?> </span>
        </div>
    </div>

    <div class="container-boxed">
        <div class="container">
            <div class="main-content">
                <div class="wishlist-total">
                    <span><?php esc_html_e('Wishlist', 'hello-elementor'); ?> </span><span id="wishlist-count"></span>
                </div>

                <div class="product-list-wishlist" data_home_url="<?php echo home_url(); ?>">
                    <!-- Show wishlist book -->
                </div>
            </div>
        </div>
    </div>
</div>