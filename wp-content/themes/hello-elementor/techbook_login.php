<?php

// Xử lý form đăng nhập
// Handle custom login form
function handle_custom_login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log']) && isset($_POST['pwd'])) {
        $user_login    = sanitize_text_field($_POST['log']);
        $user_password = $_POST['pwd'];
        $remember_me   = isset($_POST['rememberme']) ? true : false;

        $creds = array(
            'user_login'    => $user_login,
            'user_password' => $user_password,
            'remember'      => $remember_me,
        );

        $user = wp_signon($creds);

        if (is_wp_error($user)) {
            set_transient('login_failed', true, 60);
            set_transient('login_error', $user->get_error_message(), 60);

            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        } else {
            // Fire the wp_login action
            do_action('wp_login', $user->user_login, $user);

            // Redirect based on user role
            if (user_can($user, 'administrator')) {
                wp_redirect(admin_url());
            } else {
                wp_redirect(home_url());
            }
            exit;
        }
    }
}
add_action('init', 'handle_custom_login');


// Xử lý form đăng ký
function handle_custom_registration() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reg_username']) && isset($_POST['reg_email']) && isset($_POST['reg_password'])) {
        $reg_username = sanitize_user($_POST['reg_username']);
        $reg_email = sanitize_email($_POST['reg_email']);
        $reg_password = $_POST['reg_password'];

        $registration_failed = false;
        $registration_error = '';

        if (empty($reg_username) || empty($reg_email) || empty($reg_password)) {
            $registration_error = 'Vui lòng điền đầy đủ các trường.';
            $registration_failed = true;
        } else {
            if (username_exists($reg_username)) {
                $registration_error = 'Tên người dùng đã tồn tại.';
                $registration_failed = true;
            } elseif (email_exists($reg_email)) {
                $registration_error = 'Email đã tồn tại.';
                $registration_failed = true;
            } else {
                $user_id = wp_create_user($reg_username, $reg_password, $reg_email);
                if (is_wp_error($user_id)) {
                    $registration_error = $user_id->get_error_message();
                    $registration_failed = true;
                } else {
                    // Đăng ký thành công
                    set_transient('registration_successful', true, 60);

                    wp_redirect($_SERVER['REQUEST_URI']);
                    exit;
                }
            }
        }

        if ($registration_failed) {
            set_transient('registration_failed', true, 60);
            set_transient('registration_error', $registration_error, 60);

            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }
    }
}
add_action('init', 'handle_custom_registration');

// Xử lý khôi phục mật khẩu
function handle_custom_password_reset() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_email'])) {
        if (!isset($_POST['password_reset_nonce_field']) || !wp_verify_nonce($_POST['password_reset_nonce_field'], 'password_reset_nonce')) {
            // Nonce không hợp lệ
            $password_reset_error = 'Security check failed. Please try again.';
            set_transient('password_reset_failed', true, 60);
            set_transient('password_reset_error', $password_reset_error, 60);
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }

        $reset_email = sanitize_email($_POST['reset_email']);
        $password_reset_failed = false;
        $password_reset_error = '';
        $password_reset_successful = false;

        if (empty($reset_email)) {
            $password_reset_error = 'Vui lòng nhập địa chỉ email của bạn.';
            $password_reset_failed = true;
        } elseif (!email_exists($reset_email)) {
            $password_reset_error = 'Không tìm thấy người dùng với địa chỉ email này.';
            $password_reset_failed = true;
        } else {
            $user = get_user_by('email', $reset_email);
            $reset_key = get_password_reset_key($user);

            if (is_wp_error($reset_key)) {
                $password_reset_error = 'Đã xảy ra lỗi khi tạo liên kết đặt lại mật khẩu.';
                $password_reset_failed = true;
            } else {
                $reset_url = network_site_url("wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode($user->user_login), 'login');

                $message = "Ai đó đã yêu cầu đặt lại mật khẩu cho tài khoản sau:\n\n";
                $message .= 'Tên trang web: ' . get_bloginfo('name') . "\n\n";
                $message .= 'Tên đăng nhập: ' . $user->user_login . "\n\n";
                $message .= "Nếu đây là một nhầm lẫn, hãy bỏ qua email này và không có gì xảy ra.\n\n";
                $message .= "Để đặt lại mật khẩu của bạn, hãy truy cập liên kết sau:\n\n";
                $message .= $reset_url . "\n";

                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

                $title = sprintf('[%s] Đặt lại mật khẩu', $blogname);

                if (!wp_mail($reset_email, $title, $message)) {
                    $password_reset_error = 'Không thể gửi email đặt lại mật khẩu.';
                    $password_reset_failed = true;
                } else {
                    $password_reset_successful = true;
                }
            }
        }

        if ($password_reset_failed) {
            set_transient('password_reset_failed', true, 60);
            set_transient('password_reset_error', $password_reset_error, 60);
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }

        if ($password_reset_successful) {
            set_transient('password_reset_successful', true, 60);
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }
    }
}
add_action('init', 'handle_custom_password_reset');