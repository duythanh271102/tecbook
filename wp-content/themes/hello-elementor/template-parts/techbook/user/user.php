<?php
/**
 * The template for displaying footer.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


// Khởi tạo các biến cho lỗi và trạng thái đăng nhập
$user_login = '';
$login_error = '';
$login_failed = get_transient('login_failed');
if ($login_failed) {
    $login_error = get_transient('login_error');
    delete_transient('login_failed');
    delete_transient('login_error');
}

$reg_username = '';
$reg_email = '';
$registration_error = '';
$registration_failed = get_transient('registration_failed');
if ($registration_failed) {
    $registration_error = get_transient('registration_error');
    delete_transient('registration_failed');
    delete_transient('registration_error');
}

$registration_successful = get_transient('registration_successful');
if ($registration_successful) {
    delete_transient('registration_successful');
    // Xóa giá trị input
    $reg_username = '';
    $reg_email = '';
}

// Kiểm tra xem người dùng đã đăng nhập hay chưa
$is_user_logged_in = is_user_logged_in();

// Xử lý trạng thái khôi phục mật khẩu
$password_reset_failed = get_transient('password_reset_failed');
if ($password_reset_failed) {
    $password_reset_error = get_transient('password_reset_error');
    delete_transient('password_reset_failed');
    delete_transient('password_reset_error');
}

$password_reset_successful = get_transient('password_reset_successful');
if ($password_reset_successful) {
    delete_transient('password_reset_successful');
}
?>

<script>
    var loginFailed = <?php echo $login_failed ? 'true' : 'false'; ?>;
    var registrationFailed = <?php echo $registration_failed ? 'true' : 'false'; ?>;
    var registrationSuccessful = <?php echo $registration_successful ? 'true' : 'false'; ?>;
    var isUserLoggedIn = <?php echo $is_user_logged_in ? 'true' : 'false'; ?>;
    var redirectUrl = "<?php echo esc_js('#'); ?>";
    var passwordResetFailed = <?php echo $password_reset_failed ? 'true' : 'false'; ?>;
    var passwordResetSuccessful = <?php echo $password_reset_successful ? 'true' : 'false'; ?>;
    var passwordResetError = <?php echo json_encode($password_reset_error ?? ''); ?>;
</script>


<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/user/user.css">
<script src="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/user/user.js"></script>

<!-- Modal Overlay -->
<div id="modalOverlay1" class="modal-overlay1"></div>

<!-- Modal -->
<div id="loginModal1" class="modal1">
  <div class="modal-content1">
    <div class="header1">
      <div class="title1-header">
        <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/Vector.svg" alt="Cart Icon" class="cart-icon" />
        <span id="modalTitle">Sign in</span>
      </div>
      <div class="close-section">
        <p class="close-text">Close</p>
        <span class="close1">&times;</span>
      </div>
    </div>

    <!-- Form đăng nhập -->
    <div class="custom-login-form-container" id="loginFormContainer" >
        <?php if (!empty($login_error)): ?>
            <div class="login-error-message"><?php echo $login_error; ?></div>
        <?php endif; ?>

        <form name="loginform" id="loginform" action="" method="post">
            <div class="input-group">
                <label for="user_login">Username or email *</label>
                <input type="text" name="log" id="user_login" class="input" value="<?php echo esc_attr($user_login); ?>" size="20" autocomplete="username" placeholder="Text" />
            </div>

            <div class="input-group">
                <label for="user_pass">Password *</label>
                <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" autocomplete="current-password" placeholder="Text" />
            </div>

            <div class="input-group remember-me">
                <div class="remember-me-check">
                    <input name="rememberme" type="checkbox" id="rememberme" value="forever" />
                    <label for="rememberme">Remember me</label>
                </div>
                <a href="#" id="showPasswordResetForm" class="forgot-password-link">Forgot Password?</a>
            </div>
            <div class="button-group">
                <input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="SIGN IN" />
            </div>

            <div class="button-group">
                <button type="button" id="showRegisterForm" class="button-secondary">CREATE ACCOUNT</button>
            </div>
        </form>
    </div>

    <!-- Form đăng ký -->
    <div class="custom-register-form-container" id="registerFormContainer" style="display: none;">
        <?php if (!empty($registration_error)): ?>
            <div class="register-error-message"><?php echo $registration_error; ?></div>
        <?php endif; ?>

        <form name="registerform" id="registerform" action="" method="post">
            <div class="input-group">
                <label for="reg_username">Username *</label>
                <input type="text" name="reg_username" id="reg_username" class="input" value="<?php echo esc_attr($reg_username); ?>" size="20" autocomplete="username" placeholder="Text" />
            </div>

            <div class="input-group">
                <label for="reg_email">Email *</label>
                <input type="email" name="reg_email" id="reg_email" class="input" value="<?php echo esc_attr($reg_email); ?>" size="20" autocomplete="email" placeholder="Text" />
            </div>

            <div class="input-group">
                <label for="reg_password">Password *</label>
                <input type="password" name="reg_password" id="reg_password" class="input" value="" size="20" autocomplete="new-password" placeholder="Text" />
            </div>

            <div class="button-group">
                <input type="submit" name="wp-submit-register" id="wp-submit-register" class="button-primary" value="CREATE ACCOUNT" />
            </div>

            <div class="back-login">
                <p>Already have an account? <span id="showLoginForm" style= "color: #157FFF;  cursor:pointer;">Login</span></p>
            </div>
        </form>
    </div>


    <!-- Form Khôi Phục Mật Khẩu -->
    <div class="custom-password-reset-form-container" id="passwordResetFormContainer" style="display: none;">
        <?php if (!empty($password_reset_error)): ?>
            <div class="password-reset-message" style="color: red;"><?php echo $password_reset_error; ?></div>
        <?php endif; ?>
        <form name="passwordresetform" id="passwordresetform" action="" method="post">
            <?php wp_nonce_field('password_reset_nonce', 'password_reset_nonce_field'); ?>
            <div class="input-group">
                <label for="reset_email">Email *</label>
                <input type="email" name="reset_email" id="reset_email" class="input" value="" size="20" autocomplete="email" placeholder="Nhập email của bạn" />
            </div>

            <div class="button-group">
                <input type="submit" name="wp-submit-reset" id="wp-submit-reset" class="button-primary" value="RESET PASSWORD" />
            </div>

            <div class="button-group">
                <button type="button" id="showLoginFormFromReset" class="button-secondary">LOGIN</button>
            </div>
        </form>
    </div>

  </div>
</div>


