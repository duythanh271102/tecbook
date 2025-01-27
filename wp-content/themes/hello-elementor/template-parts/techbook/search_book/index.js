let pageIndex = 1; // Biến theo dõi trang hiện tại
let pageSize = parseInt($("#page-size-select").val()) || 10; 
jQuery(document).ready(function($) {

    var baseURL;
    if (window.location.hostname === 'localhost') {
        baseURL = '/techbook';
    } else {
        baseURL = '';
    }
    // Initialize Select2 on all select elements
    $('#select-publisher, #select-ics, #select-lang, #pub-year').select2({
        width: '100%',
        placeholder: 'Select an option',
        allowClear: true
    });

    $('.btn-refresh').prop('disabled', true).addClass('disabled').removeClass('enabled');

    function checkInputs() {
        let isFilled = false;

        $('.search-box input[type="text"], .search-box textarea').each(function() {
            if ($(this).val().trim() !== '') {
                isFilled = true;
                return false;
            }
        });

        $('.search-box select').each(function() {
            if ($(this).val() !== null && $(this).val() !== '') {
                isFilled = true;
                return false;
            }
        });

        if (isFilled) {
            $('.btn-refresh').prop('disabled', false).removeClass('disabled').addClass('enabled');
            $('.icon1').prop('disabled', false).removeClass('disabled').addClass('enabled');
        } else {
            $('.btn-refresh').prop('disabled', true).addClass('disabled').removeClass('enabled');
            $('.icon1').prop('disabled', true).addClass('disabled').removeClass('enabled');
        }
    }

    $('.search-box input, .search-box select, .search-box textarea').on('input change', function() {
        checkInputs();
    });

    $('.btn-refresh').on('click', function() {
        if ($(this).prop('disabled')) return;

        $('.search-box input[type="text"], .search-box textarea').val('');
        $('.search-box select').val(null).trigger('change');

        $(this).prop('disabled', true).addClass('disabled').removeClass('enabled');
    });

    checkInputs();


    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    const titleValue = getQueryParam("title");
    
    if (titleValue) {
        $("#std-title").val(decodeURIComponent(titleValue));
    }
    
    if (titleValue ) {
        setTimeout(() => {
            $(".btn-search").trigger("click");
        }, 1000);
    }

    const codeValue = getQueryParam("code");
    const subjectValue = getQueryParam("subject"); 

    if (codeValue) {
        const decodedCode = decodeURIComponent(codeValue);
        const decodedSubject = decodeURIComponent(subjectValue);
        const $select = $("#select-ics");

        if (!$select.find(`option[value="${decodedCode}"]`).length) {
            $select.append(new Option(decodedSubject, decodedCode, true, true));
        } else {
            $select.val(decodedCode);
        }

        $select.trigger("change");
        setTimeout(() => {
            $(".btn-search").trigger("click");
        }, 500);
    }

    $(window).on("beforeunload", function () {
        $("#select-ics").find(`option[value="${codeValue}"]`).remove(); 
    });

    


    $("#page-size-select").on("change", function () {
        pageSize = parseInt($(this).val());
        pageIndex = 1; 
        fetchData(); 
    });

    $(".btn-search").on("click", function () {
        pageIndex = 1;
        fetchData(); 
    });

    function fetchData() {
        $("#loading-container").show();
    
        // Lấy các giá trị từ các trường input
        const title = $("#std-title").val();
        const author = $("#Author-text").val();
        const publisher = $("#select-publisher").val();
        const keyword = $("#keyword-search").val();
        const isbn = $("#ISBN-text").val();
        const subjects = $("#select-ics").val();
        const publicationDate = $("#pub-year").val();
       
    
        const item = {
            previewPath: "string",
            fullContentBookPath: "string",
            createdDate: "2024-10-18T02:23:04.487Z",
            updatedDate: "2024-10-18T02:23:04.487Z",
            deleted: true,
            newArrival: true,
            bestSellers: true,
            isFree: true,
            totalRows: 0
        };
    
        // Thêm các trường có giá trị 
        if (title) item.title = title;
        if (author) item.author = author;
        if (publisher) item.publisher = publisher;
        if (keyword) item.keywords = keyword;
        if (isbn) item.isbn = isbn;
        if (subjects) item.subjects = subjects;
        if (publicationDate) item.publicationDate = publicationDate;
    
        const data = {
            id: "string",
            tokenKey: "4XwMBElYC3xgZeIW0IZ1H42zyvDNM5h7",
            intValue: 0,
            boolValue: true,
            stringValue: "string",
            pageIndex: pageIndex,
            pageSize: pageSize,
            orderBy: "string",
            orderWay: "string",
            item: item 
        };
    
        $.ajax({
            url: "https://115.84.178.66:8028/api/Documents/GetPaging",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function (response) {
                const products = response.data.items || [];
                const totalRows = response.data.totalRows || 0;
                renderProducts(products);
                // Hiển thị phân trang nếu có nhiều hơn 12 kết quả
            if (totalRows > pageSize) {
                renderPagination(totalRows, pageSize);
                $(".custom-pagination").show(); // Hiển thị phân trang nếu số lượng sản phẩm lớn hơn pageSize
            } else {
                $(".custom-pagination").hide(); // Ẩn phân trang nếu không đủ sản phẩm
            }
                $("#dem-so-luong").text(response.data.totalRows);
    
                $("#loading-container").hide();
    
            
                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: {
                        action: "save_books_to_cache",
                        books: products
                    },
                    success: function(res) {
                        if (res.success) {
                            console.log("Dữ liệu đã được lưu vào database:", res.result);
                        } else {
                            console.error("Lỗi khi lưu dữ liệu vào database:", res.result);
                        }
                    },
                    error: function(err) {
                        console.error("Lỗi khi gửi yêu cầu AJAX:", err);
                    }
                });  
            },
            error: function (error) {
                console.error("Error fetching data: ", error);
                $("#loading-container").hide();
            }
        });
    }
    

    function renderProducts(products) {
        let productHtml = '';
    
        if (products.length > 0) {
            products.forEach(product => {
                productHtml += `
                    <div class="product-item-search product-item-book" data-book-id="${product.id}">
                        <a href="${baseURL}/detail/book-${product.id ? product.id : ''}" class="product-link">
                                <img 
                                    src="${product.isbn ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/${product.isbn}.jpg` : `${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png`}" 
                                    alt="Product Image" class="product-image" 
                                    onerror="
                                        let imgElement = this;
                                        let extensions = ['jpg', 'png', 'jpeg', 'webp', 'gif'];
                                        let currentExtensionIndex = 1; 
                                        let baseSrc = '${product.isbn ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/${product.isbn}` : ''}';

                                        function tryNextExtension() {
                                            if (currentExtensionIndex < extensions.length) {
                                                imgElement.src = baseSrc + '.' + extensions[currentExtensionIndex];
                                                currentExtensionIndex++;
                                            } else {
                                                imgElement.src = '${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png';
                                            }
                                        }

                                        imgElement.onerror = tryNextExtension;
                                        tryNextExtension();
                                    "
                                >
                            </a>


                        <div class="info-search">
                            <a href="${baseURL}/detail/book-${product.id ? product.id : ''}"  style="color:#2C2C2C"><h3 class="product-title-search">${product.title || '&nbsp;'}</h3></a>
                            <p class="product-group-search"><strong>Author : </strong> ${product.author || '&nbsp;'}</p>
                            <p class="product-category-search"><strong>Publisher : </strong> ${product.publisher || '&nbsp;'}</p>
                            <p class="product-category-search"><strong>Date : </strong> ${product.publicationDate || '&nbsp;'}</p>
                        </div>
                        
                    </div>
                `;
            });
        } else {
            productHtml = '<p>No products available at the moment.</p>';
        }
    
        $(".product-list").html(productHtml);
    }
    
    
    function renderPagination(totalRows, pageSize) {
        const totalPages = Math.ceil(totalRows / pageSize);
        let paginationHtml = '';
    
        if (totalPages <= 1) return; 
    
        paginationHtml += `<button class="btn-page ${pageIndex === 1 ? 'active' : ''}" data-page="1">1</button>`;
    
        if (pageIndex > 3) {
            paginationHtml += `<span class="pagination-ellipsis">...</span>`;
        }
    
        for (let i = Math.max(2, pageIndex - 1); i <= Math.min(totalPages - 1, pageIndex + 1); i++) {
            paginationHtml += `<button class="btn-page ${i === pageIndex ? 'active' : ''}" data-page="${i}">${i}</button>`;
        }
    
        if (pageIndex < totalPages - 2) {
            paginationHtml += `<span class="pagination-ellipsis">...</span>`;
        }
    
        paginationHtml += `<button class="btn-page ${pageIndex === totalPages ? 'active' : ''}" data-page="${totalPages}">${totalPages}</button>`;
    
        $(".custom-pagination").html(paginationHtml);
    
        // Gán sự kiện click cho các nút phân trang
        $(".btn-page").on("click", function () {
            pageIndex = parseInt($(this).data("page")); 
            fetchData(); 
        });
    }
      
});


{/* <div class="button-search">
    <button class="button-cart-search icon-cart">
        <img src="${baseURL}/wp-content/uploads/2024/09/shopping-bag-02-3.svg" alt="Add to Cart"> Buy
    </button>
    <button class="button-wishlist-search icon-wishlist">
        <img src="${baseURL}/wp-content/uploads/2024/09/Icon-13.svg" alt="Add to Favorites">Wishlist
    </button>
</div> */}






