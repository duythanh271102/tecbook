<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
	if ( hello_elementor_display_header_footer() ) {
		if ( did_action( 'elementor/loaded' ) && hello_header_footer_experiment_active() ) {
			get_template_part( 'template-parts/dynamic-footer' );
		} else {
			get_template_part( 'template-parts/footer' );
		}
	}
}
?>

<?php 
include get_template_directory() . '/template-parts/techbook/cart/cart.php';
include get_template_directory() . '/template-parts/techbook/user/user.php';
include get_template_directory() . '/template-parts/techbook/lien-he.php';
include get_template_directory() . '/template-parts/techbook/menu/menu.php';
wp_footer();
 ?>

</body>
</html>
