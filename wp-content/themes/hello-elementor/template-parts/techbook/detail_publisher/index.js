$(document).ready(function() {
    let pageSize = parseInt($("#page-size-select").val()) || 10; 
    var pageIndex = 1;
    var totalPages = 1;
    var priceFactor = parseFloat(ajax_object.priceFactor) || 1;

    // Hàm render (hiển thị) các sản phẩm nổi bật vào carousel
    function renderFeaturedPublications(standards) {
        var html = '';

        standards.forEach(function(standard) {
            html += generateProductHTML(standard); // Tạo HTML cho từng sản phẩm
        });

        $(".product-list").html(html); 
    }

    function calculateVisibleProducts() {
        const windowWidth = $(window).width();
    
        if (windowWidth >= 1920) {
            return 6; 
        } else if (windowWidth >= 1492) {
            return 5;  
        } else if (windowWidth >= 1243) {
            return 4;  
        } else if (windowWidth >= 999) {
            return 3;  
        } else if (windowWidth >= 748) {
            return 2; 
        } else {
            return 1;  
        }
    }

    // Hàm khởi tạo carousel
    function initializeCarousel() {
        const $carousel = $('.carousel1');
        const $productList = $carousel.find('.product-list');
        const $products = $productList.find('.product-item-publisher');
        const $prevBtn = $('#prev-btn-deatail');
        const $nextBtn = $('#next-btn-deatail');

        console.log("Product numbers:", $products.length);
    
        if ($products.length === 0) {
            console.warn('Không tìm thấy sản phẩm nào. Vui lòng thêm các phần tử có class "product-item-publisher" vào HTML.');
            $(".product-list").html('<p>No Standard.</p>');
            $("#prev-btn-deatail").hide();
            $("#next-btn-deatail").hide();
            
            return;
        }
    
        const productWidth = $products.eq(0).outerWidth(true);
        let visibleProducts = calculateVisibleProducts();  
        let currentIndex = 0;
    
        $productList.css('width', productWidth * $products.length + 'px');
    
        function updateButtons() {
            $prevBtn.show();
            $nextBtn.show();
    
            if (currentIndex === 0) {
                $prevBtn.hide();
            }
    
            if (currentIndex >= $products.length - visibleProducts) {
                $nextBtn.hide();
            }
    
            if ($products.length <= visibleProducts) {
                $prevBtn.hide();
                $nextBtn.hide();
            }
        }
    
        function updateCarousel() {
            const translateValue = -(currentIndex * productWidth);
            $productList.css('transform', `translateX(${translateValue}px)`);
            console.log('Giá trị transform:', translateValue);
            updateButtons();
        }
    
        $nextBtn.off('click').on('click', function() {
            if (currentIndex < $products.length - visibleProducts) {
                currentIndex++;
                console.log('Đã nhấn nút Next, currentIndex:', currentIndex);
                updateCarousel();
            }
        });
    
        $prevBtn.off('click').on('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                console.log('Đã nhấn nút Prev, currentIndex:', currentIndex);
                updateCarousel();
            }
        });
    
        $(window).resize(function() {
            visibleProducts = $(window).width() <= 1024 ? 2 : 10;
            updateCarousel();
        });
    
        updateButtons();
        updateCarousel();
    }
    
    // Hàm tạo HTML cho một sản phẩm
    function generateProductHTML(standard) {
        var productImage = standard.idProduct
            ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/standards/cover/${standard.idProduct}.jpg`
            : `${siteUrl}/wp-content/uploads/2024/09/Rectangle-17873.png`;

        var prices = [];
        if (standard.ebookPrice && !isNaN(standard.ebookPrice)) prices.push(standard.ebookPrice * priceFactor);
        if (standard.printPrice && !isNaN(standard.printPrice)) prices.push(standard.printPrice * priceFactor);
        if (standard.bothPrice && !isNaN(standard.bothPrice)) prices.push(standard.bothPrice * priceFactor);

        var priceText = '&nbsp;';
        if (prices.length > 0) {
            var minPrice = Math.min(...prices).toFixed(2);
            var maxPrice = Math.max(...prices).toFixed(2);
            priceText = minPrice === maxPrice ? `${minPrice}$` : `${minPrice}$ - ${maxPrice}$`;
        }

        return `
            <div class="product-item-publisher product-item-book" data-book-id="${standard.idProduct}">
                <p class="discount ${standard.discount ? 'has-discount' : 'no-discount'}">
                    ${standard.discount || '&nbsp;'}
                </p>

                <a href="${siteUrl}/detail/standard-${standard.id}" class="product-link">
                    <img src="${productImage}" alt="Product Image" class="product-image">
                </a>

                <h3 class="product-title">${standard.referenceNumber || '&nbsp;'}</h3>
                <p class="product-group">${standard.standardby || '&nbsp;'}</p>

                <div class="product-icons-list-book">
                    <div class="icon-list-book2 icon-action icon-wishlist">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
                        <path d="M15.1111 1.41016C18.6333 1.41016 21 4.76266 21 7.89016C21 14.2239 11.1778 19.4102 11 19.4102C10.8222 19.4102 1 14.2239 1 7.89016C1 4.76266 3.36667 1.41016 6.88889 1.41016C8.91111 1.41016 10.2333 2.43391 11 3.33391C11.7667 2.43391 13.0889 1.41016 15.1111 1.41016Z" stroke="#2C2C2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        `;
    }

    fetchPublications(pageIndex);

    $("#page-size-select").on("change", function () {
        pageSize = parseInt($(this).val());
        pageIndex = 1; 
        fetchPublications(pageIndex);
    });

    $(document).on("click", ".page-link", function(e) {
        e.preventDefault();
        $("#loading-container").show();
        var page = $(this).data("page");
        pageIndex = page;
        fetchPublications(pageIndex);
        $('html, body').animate({
            scrollTop: $(".product-list1").offset().top
        }, 500);
    });

    function fetchPublications(pageIndex) {
        var data = {
            tokenKey: tokenKey,
            pageIndex: pageIndex,
            pageSize: pageSize,
            item: {
                standardby: publisherCode
            }
        };

        $("#loading-container").show();

        $.ajax({
            url: apiUrl,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function(response) {
                if (response && response.data && response.data.items) {
                    var standards = response.data.items;
                    var totalRows = response.data.totalRows;
                    totalPages = Math.ceil(totalRows / pageSize);
                    renderPublications(standards);
                    renderPagination(pageIndex, totalPages);

                    $.ajax({
                        url: ajaxurl,
                        type: "POST",
                        data: {
                            action: "save_standards_to_cache",
                            standards: standards
                        },
                        success: function(res) {
                            console.log("Dữ liệu đã được lưu vào database:", res);
                            $("#loading-container").hide();
                        },
                        error: function(err) {
                            console.error("Lỗi khi lưu dữ liệu vào database:", err);
                        }
                    });
        
                } else {
                    $(".product-list1").html('<p>No products available at the moment.</p>');
                    $(".custom-pagination").empty();
                    $("#loading-container").hide();
                }
            },
            error: function(error) {
                console.error("Error fetching publications: ", error);
                $(".product-list1").html('<p>Error fetching data.</p>');
                $(".custom-pagination").empty();
                $("#loading-container").hide();
            }
        });
    }

    // Function to render Publications into the product list
    function renderPublications(standards) {
        var html = '';

        standards.forEach(function(standard) {
            html += generateProductHTML(standard);
        });

        $(".product-list1").html(html);

        // Code start Click add wishlist
        const initialProducts = JSON.parse(localStorage.getItem('productIds')) || [];
        const wishlists = document.querySelectorAll('.icon-wishlist');

        wishlists.forEach(wishlist => {
            wishlist.addEventListener('click', function(event) {
                event.preventDefault(); 
                const productId = this.closest('.product-item-book').getAttribute('data-book-id');

                if (!productId) {
                    console.error("Product ID not found.");
                    return;
                }

                let storedProducts = JSON.parse(localStorage.getItem('productIds')) || [];

                if (!storedProducts.includes(productId)) {
                    storedProducts.push(productId);
                    localStorage.setItem('productIds', JSON.stringify(storedProducts));
                    alert("Product added to wishlist!");
                    this.classList.add('added');
                } else {
                    storedProducts = storedProducts.filter(id => id !== productId);
                    localStorage.setItem('productIds', JSON.stringify(storedProducts));
                    alert("Product removed from wishlist!");
                    this.classList.remove('added');
                }
            });
        });

        wishlists.forEach(wishlist => {
            const productItem = wishlist.closest('.product-item-book');

            if (productItem) {
                const productId = productItem.getAttribute('data-book-id');
                if (initialProducts.includes(productId)) {
                    wishlist.classList.add('added'); 
                }
            }
        });
        // End.
    }

    // Function to render pagination
    function renderPagination(currentPage, totalPages) {
        var paginationHtml = '';

        if (totalPages <= 1) {
            $(".custom-pagination").empty();
            return;
        }

        // Page numbers
        for (var i = 1; i <= totalPages; i++) {
            if (i == currentPage) {
                paginationHtml += `<span class="current">${i}</span>`;
            } else if (i == 1 || i == totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                paginationHtml += `<a href="#" class="page-link" data-page="${i}">${i}</a>`;
            } else if (i == currentPage - 2 || i == currentPage + 2) {
                paginationHtml += `<span class="dots">...</span>`;
            }
        }

        $(".custom-pagination").html(paginationHtml);
    }
});