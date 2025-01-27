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


<style>
        .floating-buttons {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 1000; 
        }

        .circle-button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .circle-button:hover {
            background-color: #0056b3;
        }

        .circle-button img {
            width: 35px;
            height: 35px;
        }

        .circle-button i {
            font-size: 24px;
        }

        #scroll-top {
            cursor: pointer;
        }
        @media screen and (max-width: 440px) {
        .circle-button img {
            width: 20px;
            height: 20px;
        }
        .circle-button {
            width: 40px;
            height: 40px;
        }
        }
    </style>

        <div class="floating-buttons">
        <a href="tel:0964648020" class="circle-button phone">
        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/call-calling.svg" alt="Zalo">
        </a>
        <a href="https://zalo.me/0964648020" target="_blank" class="circle-button zalo">
            <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/zalo.svg" alt="Zalo">
        </a>
        <a href="#" id="scroll-top" class="circle-button up">
        
        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/09/Icon-12.svg" alt="top">
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    jQuery(document).ready(function($) {
        $('#scroll-top').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 'smooth'); 
        });
    });
</script>