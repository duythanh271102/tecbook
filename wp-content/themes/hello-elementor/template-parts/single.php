<?php
/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

while ( have_posts() ) :
	the_post();
	?>

<main id="content" <?php post_class( 'site-main' ); ?>>

	<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
		<div class="page-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</div>
	<?php endif; ?>

	<div class="page-content">
		<?php if(get_the_ID()== 250){
			include 'techbook/detail_publisher/index.php';
		} else if(get_the_ID()== 75){
			include 'techbook/Home/Home.php';
		}else if(get_the_ID()== 937){
			include 'techbook/Home/Home.php';
		} else if(get_the_ID()== 656){
			include 'techbook/wishlist/index.php';
			the_content();
		} else if(get_the_ID()== 427){
			include 'techbook/cart_page/index.php';
			the_content();
		} else if(get_the_ID()== 321){
			include 'techbook/search_publisher/index.php';
			the_content();
		}else if(get_the_ID()== 332){
			include 'techbook/search_book/index.php';
			the_content();
		}else if(get_the_ID()== 77){
			include 'techbook/all_book/all_book.php';
			the_content();
		}else if(get_the_ID()== 944){
			include 'techbook/all_book/all_book.php';
			the_content();
		}else if(get_the_ID()== 79){
			include 'techbook/all_publisher/all_publisher.php';
			the_content();
		}else if(get_the_ID()== 962){
			include 'techbook/all_publisher/all_publisher.php';
			the_content();
		}else if(get_the_ID()== 372){
			include 'techbook/detail_book/index.php';
			the_content();
		}else if(get_the_ID()== 386){
			include 'techbook/detail_standards/index.php';
			the_content();
		}
		else{
			the_content();
		}
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
endwhile;
