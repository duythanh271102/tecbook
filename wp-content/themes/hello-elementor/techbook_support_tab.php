<?php

// Shortcode cho Tabs và Accordion
function tabs_with_accordion_shortcode() {
    ob_start();
    ?>
    <div class="tabs-container">
        <ul class="tab-list">
            <li class="active"><a href="#faqs">FAQs</a></li>
            <li><a href="#return-policy">Return Policy</a></li>
            <li><a href="#buying-guide">Buying Guide</a></li>
        </ul>

        <!-- Tab Nội Dung -->
        <div class="tab-content">
            <!-- FAQs Tab -->
            <div id="faqs" class="tab active">
                <h3>Shopping</h3>
                <div class="accordion">
                    <div class="accordion-item">
                        <div class="accordion-header">Tôi có thể hủy đơn hàng không?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>A placerat ac vestibulum integer vehicula suspendisse nostra aptent fermentum tempor a magna erat ligula parturient curae sem conubia vestibulum ac inceptos sodales condimentum cursus nunc mi consectetur condimentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">Tôi có thể thay đổi thông tin số điện thoại/địa chỉ nhận hàng sau khi đã đặt hàng không?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>Nội dung trả lời cho câu hỏi này...</p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">Tôi cần chờ bao lâu để nhận được đơn hàng?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>Nội dung trả lời cho câu hỏi này...</p>
                        </div>
                    </div>
                </div>


                <h3>Lorem ipsum dolor sit amet</h3>
                <div class="accordion">
                    <div class="accordion-item">
                        <div class="accordion-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>A placerat ac vestibulum integer vehicula suspendisse nostra aptent fermentum tempor a magna erat ligula parturient curae sem conubia vestibulum ac inceptos sodales condimentum cursus nunc mi consectetur condimentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>A placerat ac vestibulum integer vehicula suspendisse nostra aptent fermentum tempor a magna erat ligula parturient curae sem conubia vestibulum ac inceptos sodales condimentum cursus nunc mi consectetur condimentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>A placerat ac vestibulum integer vehicula suspendisse nostra aptent fermentum tempor a magna erat ligula parturient curae sem conubia vestibulum ac inceptos sodales condimentum cursus nunc mi consectetur condimentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>A placerat ac vestibulum integer vehicula suspendisse nostra aptent fermentum tempor a magna erat ligula parturient curae sem conubia vestibulum ac inceptos sodales condimentum cursus nunc mi consectetur condimentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>A placerat ac vestibulum integer vehicula suspendisse nostra aptent fermentum tempor a magna erat ligula parturient curae sem conubia vestibulum ac inceptos sodales condimentum cursus nunc mi consectetur condimentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Return Policy Tab -->
            <div id="return-policy" class="tab">
                <h3>Return Policy</h3>
                <div class="accordion">
                <div class="accordion-item">
                        <div class="accordion-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>A placerat ac vestibulum integer vehicula suspendisse nostra aptent fermentum tempor a magna erat ligula parturient curae sem conubia vestibulum ac inceptos sodales condimentum cursus nunc mi consectetur condimentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>A placerat ac vestibulum integer vehicula suspendisse nostra aptent fermentum tempor a magna erat ligula parturient curae sem conubia vestibulum ac inceptos sodales condimentum cursus nunc mi consectetur condimentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>A placerat ac vestibulum integer vehicula suspendisse nostra aptent fermentum tempor a magna erat ligula parturient curae sem conubia vestibulum ac inceptos sodales condimentum cursus nunc mi consectetur condimentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>A placerat ac vestibulum integer vehicula suspendisse nostra aptent fermentum tempor a magna erat ligula parturient curae sem conubia vestibulum ac inceptos sodales condimentum cursus nunc mi consectetur condimentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buying Guide Tab -->
            <div id="buying-guide" class="tab">
                <h3>Buying Guide</h3>
                <div class="accordion">
                    <div class="accordion-item">
                        <div class="accordion-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>A placerat ac vestibulum integer vehicula suspendisse nostra aptent fermentum tempor a magna erat ligula parturient curae sem conubia vestibulum ac inceptos sodales condimentum cursus nunc mi consectetur condimentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>A placerat ac vestibulum integer vehicula suspendisse nostra aptent fermentum tempor a magna erat ligula parturient curae sem conubia vestibulum ac inceptos sodales condimentum cursus nunc mi consectetur condimentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit?<span class="toggle-sign">+</span></div>
                        <div class="accordion-content">
                            <p>A placerat ac vestibulum integer vehicula suspendisse nostra aptent fermentum tempor a magna erat ligula parturient curae sem conubia vestibulum ac inceptos sodales condimentum cursus nunc mi consectetur condimentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tabs-container {
            width: 100%;
        }

        .tab-content {
            padding: 0 20px;
            border: 1px solid #EDEDED;
            border-radius: 8px;
        }
        .tab-list {
            display: flex;
            list-style: none;
            padding: 0;
            margin-bottom: 20px;
        }

        .tab-list li {
            margin-right: 10px;
        }

        .tab-list li a {
            display: block;
            padding: 10px 0;
            background-color: #ffffff;
            border: 1px solid #1E00AE;
            border-radius: 5px;
            color: #1E00AE;
            text-decoration: none;
            width: 270px;
            text-align: center;
            font-family: Ford Antenna;
            font-size: 18px;
            font-weight: 500;
            line-height: 36px;
        }
        .tab-list li.active a {
            background-color: #1E00AE;
            color: #fff;
        }

        .tab h3{
            font-family: Ford Antenna;
            font-size: 20px;
            font-weight: 500;
            line-height: 24px;
            margin-top: 30px;

        }

        .tab {
            display: none;
        }

        .tab.active {
            display: block;
        }


        .accordion-item {
            margin-bottom: 10px;
        }

        .accordion-header {
            padding: 20px;
            border: 1px solid #EDEDED;
            cursor: pointer;
            position: relative;
            font-family: Ford Antenna;
            font-size: 16px;
            font-weight: 400;
            line-height: 24px;
            border-radius: 8px;
        }

        .accordion-header.active{
            border: 1px solid #157FFF;
        }


        .accordion-content {
            display: none;
            padding: 10px;
            background-color: #fff;
        }


        .toggle-sign {
            position: absolute;
            right: 10px;
            font-size: 18px;
        }

        @media screen and (max-width: 1300px) {
            .tab-list li a {
            width: 200px;
        }
        }

        @media screen and (max-width: 700px) {
            .tab-list li a {
            width: 100px;
            padding: 0;
        }
        .tab-list li a {
        width: 100px;
        padding: 0;
        font-size: 12px;
    }
    .tab-list {
    margin-left: -10px;
}


        }
    </style>

    <script>
        jQuery(document).ready(function($) {
            // Tab Switching
            $('.tab-list li a').click(function(e) {
                e.preventDefault();
                var target = $(this).attr('href');
                // Remove active class from all tabs and tab contents
                $('.tab-list li').removeClass('active');
                $('.tab').removeClass('active');
                // Add active class to clicked tab and corresponding content
                $(this).parent().addClass('active');
                $(target).addClass('active');
            });

            $('.tab-list li:first-child a').trigger('click');

            // Accordion Toggle
            $('.accordion-header').click(function() {
                $(this).toggleClass('active');
                $(this).next('.accordion-content').slideToggle();

                // Update the sign
                var sign = $(this).find('.toggle-sign');
                if ($(this).hasClass('active')) {
                    sign.text('-');
                } else {
                    sign.text('+');
                }
            });
        });
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode('tabs_with_accordion', 'tabs_with_accordion_shortcode');