// Kiểm tra xem nút có tồn tại trên màn hình không
$(document).ready(function() {

    // Lấy các phần tử cần thiết
    const $modal = $('#uniqueModal123');
    const $openModalBtn = $('.header-mobile-wp');
    const $closeModalBtn = $('.closeButton789');

    $openModalBtn.on('click', function() {
        $modal.css('display', 'block');
        setTimeout(function() {
            $modal.addClass('showModal');
        }, 10);  
    });
 
    $closeModalBtn.on('click', function() {
        $modal.removeClass('showModal');
        setTimeout(function() {
            $modal.css('display', 'none');
        }, 400); 
    });

    $(window).on('click', function(event) {
        if ($(event.target).is($modal)) {
            $modal.removeClass('showModal');
            setTimeout(function() {
                $modal.css('display', 'none');
            }, 400);
        }
    });
});

