<?php
/**
 * The template for displaying Product Detail.
 *
 * @package HelloElementor
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
?>

<main id="content" <?php post_class( 'book-detail site-main' ); ?>>

	<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
		<div class="page-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</div>
	<?php endif; ?>

	<div class="page-content">
	<?php 
		include 'techbook/detail_book/index.php';
		the_content();
	?>
<?php wp_link_pages(); ?>

<?php if ( has_tag() ) : ?>
<div class="post-tags">
	<?php the_tags( '<span class="tag-links">' . esc_html__( 'Tagged ', 'hello-elementor' ), ', ', '</span>' ); ?>
</div>
<?php endif; ?>
</div>

<?php comments_template(); ?>

</main>

<?php
get_footer();

