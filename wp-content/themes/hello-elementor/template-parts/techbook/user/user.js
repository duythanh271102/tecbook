document.addEventListener('DOMContentLoaded', function() {
    // Lấy modal và overlay
    var modal = document.getElementById("loginModal1");
    var overlay = document.getElementById("modalOverlay1");
    var btns = document.querySelectorAll(".header-login-wp"); 
    var closeBtn = modal.querySelector(".close1");
    var closeText = modal.querySelector(".close-text");
    var loginFormContainer = document.getElementById("loginFormContainer");
    var registerFormContainer = document.getElementById("registerFormContainer");
    
    // Lấy các nút để chuyển đổi form
    var showRegisterFormBtn = document.getElementById("showRegisterForm");
    var showLoginFormBtn = document.getElementById("showLoginForm");

    var modalTitle = document.getElementById('modalTitle'); 
    
    // Hàm mở modal
    function openModal() {
        modal.classList.add('active');
        overlay.style.display = 'block';
    }
    
    // Hàm đóng modal
    function closeModal() {
        modal.classList.remove('active');
        overlay.style.display = 'none';
    }
    
    // Hiển thị form đăng nhập
    function showLoginForm() {
        loginFormContainer.style.display = 'block';
        registerFormContainer.style.display = 'none';
        if (modalTitle) {
            modalTitle.textContent = 'Sign in';
        }
    }
    
    // Hiển thị form đăng ký
    function showRegisterForm() {
        loginFormContainer.style.display = 'none';
        registerFormContainer.style.display = 'block';
        if (modalTitle) {
            modalTitle.textContent = 'Create Account';
        }
    }
    
    // Thêm sự kiện cho tất cả các nút có class 'header-login-wp'
    btns.forEach(function(btn){
        btn.addEventListener('click', function(e) {
            if (typeof isUserLoggedIn !== 'undefined' && isUserLoggedIn) {
                window.location.href = redirectUrl;
            } else {
                showLoginForm();
                openModal();
            }
        });
    });
    
    // Sự kiện đóng modal
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }
    
    if (closeText) {
        closeText.addEventListener('click', closeModal);
    }
    
    overlay.addEventListener('click', closeModal);
    
    // Sự kiện chuyển đổi form
    if (showRegisterFormBtn) {
        showRegisterFormBtn.addEventListener('click', function() {
            showRegisterForm();
        });
    }
    
    if (showLoginFormBtn) {
        showLoginFormBtn.addEventListener('click', function() {
            showLoginForm();
        });
    }
    
    // Kiểm tra nếu đăng nhập thất bại
    if (typeof loginFailed !== 'undefined' && loginFailed) {
        showLoginForm();
        openModal();
        
        var errorMessage = modal.querySelector(".login-error-message");
        if (errorMessage) {
            errorMessage.style.display = 'block';
        }
    }
    
    // Kiểm tra nếu đăng ký thất bại
    if (typeof registrationFailed !== 'undefined' && registrationFailed) {
        showRegisterForm();
        openModal();
        
        var regErrorMessage = modal.querySelector(".register-error-message");
        if (regErrorMessage) {
            regErrorMessage.style.display = 'block';
        }
    }
    
    if (typeof registrationSuccessful !== 'undefined' && registrationSuccessful) {
        showLoginForm();
        openModal();
        
        var successMessage = document.createElement('div');
        successMessage.className = 'login-success-message';
        successMessage.style.color = 'green';
        successMessage.style.marginBottom = '15px';
        successMessage.textContent = 'Congratulations, registration successful. Please log in.';
        
        var loginForm = document.getElementById('loginform');
        loginForm.parentNode.insertBefore(successMessage, loginForm);
    }







   //đặt mật khẩu

     var passwordResetFormContainer = document.getElementById('passwordResetFormContainer');
     var showPasswordResetFormLink = document.getElementById('showPasswordResetForm');
     var showLoginFormFromResetBtn = document.getElementById('showLoginFormFromReset');
 
     function showPasswordResetForm() {
         loginFormContainer.style.display = 'none';
         registerFormContainer.style.display = 'none';
         passwordResetFormContainer.style.display = 'block';
         if (modalTitle) {
             modalTitle.textContent = 'Reset Password';
         }
     }
 
     if (showPasswordResetFormLink) {
         showPasswordResetFormLink.addEventListener('click', function(e) {
             e.preventDefault();
             showPasswordResetForm();
         });
     }
 
     if (showLoginFormFromResetBtn) {
         showLoginFormFromResetBtn.addEventListener('click', function() {
             showLoginForm();
             passwordResetFormContainer.style.display = 'none';
         });
     }
 
     if (typeof passwordResetSuccessful !== 'undefined' && passwordResetSuccessful) {
         showLoginForm();
         openModal();
         var successMessage = document.createElement('div');
         successMessage.className = 'login-success-message';
         successMessage.style.color = 'green';
         successMessage.style.marginBottom = '15px';
         successMessage.textContent = 'Một email đã được gửi đến địa chỉ email của bạn với hướng dẫn đặt lại mật khẩu.';
         var loginForm = document.getElementById('loginform');
         loginForm.parentNode.insertBefore(successMessage, loginForm);
     }
 
     if (typeof passwordResetFailed !== 'undefined' && passwordResetFailed) {
         showPasswordResetForm();
         openModal();
         var errorMessage = passwordResetFormContainer.querySelector('.password-reset-message');
         if (errorMessage) {
             errorMessage.style.display = 'block';
             errorMessage.style.color = 'red';
             errorMessage.textContent = passwordResetError;
         }
     }
});
