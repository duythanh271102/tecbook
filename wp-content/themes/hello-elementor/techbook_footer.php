<?php

function custom_wpcf7_success_message_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var forms = document.querySelectorAll('.wpcf7-form');
        forms.forEach(function(form) {
            form.addEventListener('wpcf7mailsent', function() {
                setTimeout(function() {
                    var successMessage = form.querySelector('.wpcf7-response-output');
                    if (successMessage) {
                        successMessage.style.display = 'block'; 
                        successMessage.style.opacity = '1';
                        successMessage.style.backgroundColor = 'green';
                        successMessage.style.color = '#fff';
                        successMessage.style.transition = 'opacity 1s ease-in-out';
                        setTimeout(function() {
                            successMessage.style.opacity = '0';
                            setTimeout(function() {
                                successMessage.style.display = 'none';
                            }, 1000);
                        }, 3000); 
                    }
                }, 0);
            });
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'custom_wpcf7_success_message_script');

add_action('wp_footer', 'custom_cf7_loading_overlay_script');
function custom_cf7_loading_overlay_script() {
    ?>
    <style>
    #loading-overlay {
        display: none; 
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255, 255, 255, 0.8); 
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    #loading-overlay .loading-icon {
        color: #1e00ae;
        font-size: 48px;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var forms = document.querySelectorAll('.wpcf7-form');
        var loadingOverlay = document.createElement('div');
        loadingOverlay.id = 'loading-overlay';
        loadingOverlay.innerHTML = '<div class="loading-icon"><i class="fas fa-spinner fa-spin"></i></div>';
        document.body.appendChild(loadingOverlay);

        forms.forEach(function(form) {
            var submitButton = form.querySelector('#cf7-submit, .submit-button');
            if (submitButton) {
                submitButton.addEventListener('click', function() {
                    loadingOverlay.style.display = 'flex';
                });
            }

            form.addEventListener('wpcf7mailsent', function() {
                loadingOverlay.style.display = 'none';
            });

            form.addEventListener('wpcf7invalid', function() {
                loadingOverlay.style.display = 'none';
            });

            form.addEventListener('wpcf7mailfailed', function() {
                loadingOverlay.style.display = 'none';
            });

            form.addEventListener('wpcf7spam', function() {
                loadingOverlay.style.display = 'none';
            });
        });
    });
    </script>
    <?php
}

add_filter('wpcf7_validate_email*', 'custom_cf7_email_validation', 20, 2);
function custom_cf7_email_validation($result, $tag) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tag = new WPCF7_FormTag($tag);
        $name = $tag->name;
        if ($name === 'email-747' || $name === 'your-email') {
            $emailValue = isset($_POST[$name]) ? trim($_POST[$name]) : '';
            if (strpos($emailValue, '.') === 0 || substr($emailValue, -1) === '.') {
                $result->invalidate($tag, "Please enter a valid email address (No '.' at start/end).");
            } else if (strpos($emailValue, '-') !== false) {
                $result->invalidate($tag, "Please enter a valid email address (No '-' allowed).");
            } else if (preg_match('/[^a-zA-Z0-9.@]/', $emailValue)) {
                $result->invalidate($tag, "Please enter a valid email address (Invalid characters).");
            }
        }
    }
    return $result;
}
