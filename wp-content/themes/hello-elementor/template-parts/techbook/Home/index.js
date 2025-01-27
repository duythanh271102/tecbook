$(document).ready(function() {

    // Sidebar Left
    $("#publisher-btn").on("click", function() {
        $("#publisher-content").addClass("active");
        $("#books-content").removeClass("active");
        $(this).addClass("active");
        $("#books-btn").removeClass("active");
    });

    $("#books-btn").on("click", function() {
        $("#books-content").addClass("active");
        $("#publisher-content").removeClass("active");
        $(this).addClass("active");
        $("#publisher-btn").removeClass("active");
    });


    //chữ cái

    $('#loading-container').show();

    $.ajax({
        url: ajax_object.ajaxurl,
        method: 'POST',
        data: {
            action: 'filter_publishers_by_letter',
            letter: 'A'
        },
        success: function(data) {
            $('#loading-container').hide();
            $('.publishers-list').html(data).fadeIn();
        },
        error: function() {
            $('#loading-container').hide();
            alert('Có lỗi xảy ra khi tải dữ liệu.');
        }
    });

    $('.letters-list .letter').click(function(event) {
        event.preventDefault();

        const selectedLetter = $(this).text();

        $('.publishers-list').hide();
        $('#loading-container').show();

        $.ajax({
            url: ajax_object.ajaxurl,
            method: 'POST',
            data: {
                action: 'filter_publishers_by_letter',
                letter: selectedLetter
            },
            success: function(data) {
                $('#loading-container').hide();
                $('.publishers-list').html(data).fadeIn();
            },
            error: function() {
                $('#loading-container').hide();
                alert('Có lỗi xảy ra khi tải dữ liệu.');
            }
        });
    });

    $(window).on('load', function() {
        $('#loading-container').hide();
    });

    // Sidebar Right

    // Slide
    let currentSlide = 0;
    const $slides = $('.slide');
    const totalSlides = $slides.children().length;
    const $dots = $('.dot');

    function showSlide(slideIndex) {
        if (slideIndex >= totalSlides) {
            currentSlide = 0;
        } else if (slideIndex < 0) {
            currentSlide = totalSlides - 1;
        } else {
            currentSlide = slideIndex;
        }

        const offset = -(100 * currentSlide);
        $slides.css('transform', `translateX(${offset}%)`);
        $dots.removeClass('active');
        $dots.eq(currentSlide).addClass('active');
    }

    setInterval(() => {
        showSlide(currentSlide + 1);
    }, 3000);

    $dots.each(function(index) {
        $(this).on('click', function() {
            showSlide(index);
        });
    });



    // Tabs
    const $tabs = $('.tab-item');

    $tabs.on('click', function() {
        $tabs.removeClass('active');
        $(this).addClass('active');
    });




    $('.product-list1, .product-list2, .product-list3, .product-list4').each(function(index, productList) {
        const $productList = $(productList);
        
        // Kiểm tra nếu là list 1 hoặc list 4, dùng 'product-item-publisher', ngược lại dùng 'product-item'
        const $products = (index === 0 || index === 3) 
            ? $productList.find('.product-item-publisher') 
            : $productList.find('.product-item');
        
        const $prevBtn = $(`#prev-btn${index + 1}`);
        const $nextBtn = $(`#next-btn${index + 1}`);
    
        const productWidth = $products.outerWidth(true);
        const totalProducts = $products.length;
        let visibleProducts = $(window).width() < 1024 ? 2 : 5;
        let currentIndex = 0;
    
        $productList.css('width', productWidth * totalProducts + 'px');
    
        function updateButtons() {
            $prevBtn.toggle(currentIndex !== 0);
            $nextBtn.toggle(currentIndex < totalProducts - visibleProducts);
        }
    
        $nextBtn.on('click', function() {
            if (currentIndex < totalProducts - visibleProducts) {
                currentIndex++;
                updateCarousel();
            }
        });
    
        $prevBtn.on('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });
    
        function updateCarousel() {
            const translateValue = -(currentIndex * (productWidth + 10));
            $productList.css({
                'transform': `translateX(${translateValue}px)`,
                'transition': 'transform 0.5s ease-in-out'
            });
            updateButtons();
        }
    
        updateButtons();
    
        $(window).resize(function() {
            visibleProducts = $(window).width() < 1024 ? 2 : 5;
            updateButtons();
            updateCarousel();
        });
    });
    



    $('.carousel1').each(function() {
        const $carousel = $(this);
        const $productList = $carousel.find('.product-list');
        const $products = $productList.find('.product-item-publisher');
        const $prevBtn = $carousel.find('.prev-btn');
        const $nextBtn = $carousel.find('.next-btn');
        
        let visibleProducts = $(window).width() <= 1024 ? 2 : 6;
        let currentIndex = 0;
        const productWidth = 236;
        
        $productList.css('width', productWidth * $products.length + 'px');
        
        function updateButtons() {
            if (currentIndex === 0) {
                $prevBtn.hide();
            } else {
                $prevBtn.show();
            }
    
            if (currentIndex >= $products.length - visibleProducts) {
                $nextBtn.hide();
            } else {
                $nextBtn.show();
            }
        }
    
        $nextBtn.on('click', function() {
            if (currentIndex < $products.length - visibleProducts) {
                currentIndex++;
                updateCarousel();
            }
        });
    
        $prevBtn.on('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });
    
        function updateCarousel() {
            const translateValue = -(currentIndex * (productWidth + 10));
            $productList.css({
                'transform': `translateX(${translateValue}px)`,
                'transition': 'transform 0.5s ease-in-out'
            });
            updateButtons();
        }
    
        updateButtons();
    
        $(window).resize(function() {
            visibleProducts = $(window).width() <= 1024 ? 2 : 6;
            updateButtons();
            updateCarousel();
        });
    
    });

    $('.carousel1-book').each(function() {
        const $carousel = $(this);
        const $productList = $carousel.find('.product-list-book');
        const $products = $productList.find('.product-item');
        const $prevBtn = $carousel.find('.prev-btn');
        const $nextBtn = $carousel.find('.next-btn');
        
        let visibleProducts = $(window).width() <= 1024 ? 2 : 6;
        let currentIndex = 0;
        const productWidth = 236;
        
        $productList.css('width', productWidth * $products.length + 'px');
        
        function updateButtons() {
            if (currentIndex === 0) {
                $prevBtn.hide();
            } else {
                $prevBtn.show();
            }
    
            if (currentIndex >= $products.length - visibleProducts) {
                $nextBtn.hide();
            } else {
                $nextBtn.show();
            }
        }
    
        $nextBtn.on('click', function() {
            if (currentIndex < $products.length - visibleProducts) {
                currentIndex++;
                updateCarousel();
            }
        });
    
        $prevBtn.on('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });
    
        function updateCarousel() {
            const translateValue = -(currentIndex * (productWidth + 10));
            $productList.css({
                'transform': `translateX(${translateValue}px)`,
                'transition': 'transform 0.5s ease-in-out'
            });
            updateButtons();
        }
    
        updateButtons();
    
        $(window).resize(function() {
            visibleProducts = $(window).width() <= 1024 ? 2 : 6;
            updateButtons();
            updateCarousel();
        });
    
    });
    
    
    
    
    
    



    // Phần 2
    $('#standards1-content').show();
    $('#Books1').removeClass('active');
    $('#Standards1').on('click', function() {
        $('#standards1-content').show();
        $('#books1-content').hide();
        $(this).addClass('active');
        $('#Books1').removeClass('active');
    });
    $('#Books1').on('click', function() {
        $('#books1-content').show();
        $('#standards1-content').hide();
        $(this).addClass('active');
        $('#Standards1').removeClass('active');
    });




    



    //reponsive
     function checkScreenWidth() {
        if ($(window).width() <= 1024) {
            $('.drag-handle').show();
            $('.drag-handle').off('click').on('click', function() {
                $('.sidebar').addClass('active');
                $('.overlay').show();
                $('body').addClass('sidebar-open');
                $('.drag-handle').hide();
            });
    
            $('.overlay').off('click').on('click', function() {
                $('.sidebar').removeClass('active');
                $('.overlay').hide();
                $('body').removeClass('sidebar-open');
                $('.drag-handle').show();
            });
        } else {
            $('.drag-handle').hide();
            $('.overlay').hide();
            $('.sidebar').removeClass('active').css('left', '0');
            $('body').removeClass('sidebar-open');
        }
    }
    
    checkScreenWidth();

    $(window).resize(function() {
        checkScreenWidth();
    });
    




    $("#books").addClass("selected").css("color", "red");
    $("#product-book").show();
    $("#product-standards").hide();

    // Handle button click
    $(".filter-btn").on("click", function () {
        // Remove 'selected' class and reset styles for all buttons
        $(".filter-btn").removeClass("selected").css("color", "");

        // Add 'selected' class and change color for clicked button
        $(this).addClass("selected").css("color", "red");

        // Show or hide content based on button clicked
        if ($(this).attr("id") === "books") {
            $("#product-book").show();
            $("#product-standards").hide();
        } else if ($(this).attr("id") === "standards") {
            $("#product-standards").show();
            $("#product-book").hide();
        }
    });
});
