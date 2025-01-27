<?php
/**
 * The template for displaying footer.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/cart/cart.css">
<script src="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/cart/cart.js"></script>
<?php  wp_enqueue_script('index', get_template_directory_uri() . '/template-parts/techbook/cart_page/index.js', array('jquery'), null, true);
wp_localize_script('index', 'ajax_objectt', [
    'ajaxurl' => admin_url('admin-ajax.php')
]); ?>

<!-- Modal -->
<div id="cartModal" class="modal">
    <div class="modal-content">
    	       
    </div>	
</div>
<div id="modalOverlay" class="overlay"></div>	