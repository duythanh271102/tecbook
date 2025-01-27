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

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/menu/menu.css">

<script src="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/menu/menu.js"></script>


<body>
    <div id="uniqueModal123" class="hiddenModal">
        <div class="modalContent456">
            <span class="closeButton789">&times;</span>
            <ul>
            <li><a href="<?php echo home_url(); ?>/home">Home</a></li>
            <li><a href="<?php echo home_url(); ?>/book">Book</a></li>
            <li><a href="<?php echo home_url(); ?>/publisher">Publisher</a></li>
            <li><a href="<?php echo home_url(); ?>/about-us">About Us</a></li>
            <li><a href="<?php echo home_url(); ?>/all-blog">Blog</a></li>
            <li><a href="<?php echo home_url(); ?>/contact">Contact</a></li>
            <!-- <li class="dropdown123">
                <a href="#">USD <span><img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Symbol-2.svg" alt="icon" class="menu-drop-down"></span></a>
                <ul class="dropdown123-content">
                    <li><a href="#">China</a></li>
                    <li><a href="#">Vietnam</a></li>
                </ul>
            </li>
            <li class="dropdown123">
                <a href="#">English <span><img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Symbol-2.svg" alt="icon" class="menu-drop-down"></span></a>
                <ul class="dropdown123-content">
                    <li><a href="#">Vietnam</a></li>
                    <li><a href="#">China</a></li>
                </ul>
            </li> -->
            <li><a href="<?php echo home_url(); ?>/wishlist">Wishlist</a></li>
            <div class="button__search">
                <button  class="button__search__book">Search book</button>
                <button class="button__search__publisher">Search standards</button>
            </div>

        </ul>
        </div>
    </div>
        
</body>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.button__search__book').on('click', function() {
            window.location.href = "<?php echo home_url(); ?>/search-book/";
        });
        $('.button__search__publisher').on('click', function() {
            window.location.href = "<?php echo home_url(); ?>/search-publisher/";
        });
    });
</script>
