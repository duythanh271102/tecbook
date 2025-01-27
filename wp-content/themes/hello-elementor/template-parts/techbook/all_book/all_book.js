
let pageIndex = 1;
let pageSize = parseInt($("#page-size-select").val()) || 10;

jQuery(document).ready(function ($) {
    var baseURL;
    if (window.location.hostname === 'localhost') {
        baseURL = '/techbook';
    } else {
        baseURL = '';
    }

    $('#select-ics').select2({
        width: '100%',
        placeholder: select2Translations.all,
        allowClear: true
    });

    $('#pub-year').select2({
        placeholder: select2Translations.select_year,
        allowClear: true,
        width: 'style',
         width: '100%'
    });

    var priceRange = document.getElementById('priceRange');
    var priceValue = document.getElementById('priceValue');

    priceRange.addEventListener('input', function () {
        priceValue.innerText = '$' + priceRange.value;
    });

    var categories = [
        'AASHTO Collection', 'Aerodynamics', 'Biological engineering',
        'Chemistry and Chemical Engineering Discipline', 'Civil Engineering Discipline',
        'Earth Sciences Discipline', 'General Electronic Engineering', 'Fluid Dynamics',
        'Highway Transportation', 'Process Safety', 'Process Safety', 'Process Safety'
        , 'Process Safety', 'Process Safety', 'Process Safety', 'Process Safety'
        , 'Process Safety', 'Process Safety', 'Process Safety', 'Process Safety'
        , 'Process Safety', 'Process Safety', 'Process Safety', 'Process Safety'
        , 'Process Safety', 'Process Safety', 'Process Safety', 'Process Safety'
    ];

    categories.forEach(function (category) {
        $('.modal-content-book .categories').append(
            '<label class="category-checkbox">' +
            '<input type="checkbox" value="' + category + '"> ' + category +
            '</label>'
        );
    });

    var modal = $('#bookCategoryModal');
    var selectedOption = $('.selected-option');
    var searchCategory = $('.search-category-book');

    searchCategory.on('click', function (event) {
        event.stopPropagation();
        var offset = $(this).offset();
        var width = $(this).outerWidth();
        var height = $(this).outerHeight();
        var modalWidth = modal.outerWidth();
        var leftPosition = offset.left + width - modalWidth;

        modal.css({
            top: offset.top + height + 'px',
            left: leftPosition + 'px',
            position: 'absolute'
        }).show();
    });

    $(document).on('click', function (event) {
        if (!$(event.target).closest('.modal-book, .search-category-book').length) {
            modal.hide();
        }
    });

    $(document).on('click', '.category-checkbox input', function () {

    });

    $('.view-all').on('click', function (event) {
        event.preventDefault();
        alert('View all categories');
    });

    //reposive
    function checkScreenWidth() {
        if ($(window).width() <= 1224) {
            $('.drag-handle').show();
            $('.drag-handle').off('click').on('click', function () {
                $('.sidebar').addClass('active');
                $('.overlay').show();
                $('body').addClass('sidebar-open');
                $('.drag-handle').hide();
            });

            $('.overlay').off('click').on('click', function () {
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

    $(window).resize(function () {
        checkScreenWidth();
    });


    $('.refresh-button').prop('disabled', true).addClass('disabled').removeClass('enabled');
    function checkInputs() {
        let isFilled = false;
        $('.sidebar input[type="text"], .sidebar textarea').each(function () {
            if ($(this).val().trim() !== '') {
                isFilled = true;
                return false;
            }
        });
        $('.sidebar select').each(function () {
            if ($(this).val() !== null && $(this).val() !== '') {
                isFilled = true;
                return false;
            }
        });
        if (isFilled) {
            $('.refresh-button').prop('disabled', false).removeClass('disabled').addClass('enabled');
        } else {
            $('.refresh-button').prop('disabled', true).addClass('disabled').removeClass('enabled');
        }
    }
    $('.sidebar input, .sidebar select, .sidebar textarea').on('input change', function () {
        checkInputs();
    });
    $('.refresh-button').on('click', function () {
        if ($(this).prop('disabled')) return;
        $('.sidebar input[type="text"], .sidebar textarea').val('');
        $('.sidebar select').val(null).trigger('change');
        $(this).prop('disabled', true).addClass('disabled').removeClass('enabled');
    });
    checkInputs();


    $("#page-size-select").on("change", function () {
        pageSize = parseInt($(this).val());
        pageIndex = 1;
        fetchData();
    });

    $(".search-button").on("click", function () {
        pageIndex = 1;
        fetchData();
    });

    $(".filter-button").on("click", function () {
        pageIndex = 1;
        fetchData();
    });

    function fetchData() {
        $("#loading-container").show();

        const title = $(".search-input").val();
        const subjects = $("#select-ics").val();
        const author = $("#author-text").val();
        const publicationDate = $("#pub-year").val();
        const pricePrint = $("#priceValue").text().replace('$', '');

        const item = {};

        if (title) item.title = title;
        if (subjects) item.subjects = subjects;
        if (author) item.author = author;
        if (publicationDate) item.publicationDate = publicationDate;
        if (pricePrint) item.pricePrint = parseFloat(pricePrint);

        const data = {
            tokenKey: "4XwMBElYC3xgZeIW0IZ1H42zyvDNM5h7",
            pageIndex: pageIndex,
            pageSize: pageSize,
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

                if (totalRows > pageSize) {
                    renderPagination(totalRows, pageSize);
                    $(".custom-pagination").show();
                } else {
                    $(".custom-pagination").hide();
                }

                $("#loading-container").hide();

                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: {
                        action: "save_books_to_cache",
                        books: products
                    },
                    success: function (res) {
                        if (res.success) {
                            console.log("Data has been successfully saved to the database:", res.result);
                        } else {
                            console.error("Error saving data to the database:", res.result);
                        }
                    },
                    error: function (err) {
                        console.error("Error sending AJAX request:", err);
                    }
                });
            },
            error: function (error) {
                console.error("Error retrieving data: ", error);
                $("#loading-container").hide();
            }
        });
    }

    function renderProducts(products) {
        let productHtml = '';

        if (products.length > 0) {
            products.forEach(product => {
                const productHTML = `
                <div class="product-item product-item-book" data-book-id="${product.id}">
                    <p class="discount ${product.discount ? 'has-discount' : 'no-discount'}">
                    ${product.discount ? product.discount : '&nbsp;'}
                    </p>

                    <a href="${baseURL}/detail/book-${product.id ? product.id : ''}" class="product-link">
                        <img src="${product.isbn ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/${product.isbn}.jpg` : `${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png`}" 
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

                        <h3 class="product-title">${product.title ? product.title : '&nbsp;'}</h3>
                    </a>
                    <p class="product-group">${product.author ? product.author : '&nbsp;'}</p>

                    <div class="product-icons-list-book">
                        <div class="icon-list-book2 icon-action icon-wishlist">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
                            <path d="M15.1111 1.41016C18.6333 1.41016 21 4.76266 21 7.89016C21 14.2239 11.1778 19.4102 11 19.4102C10.8222 19.4102 1 14.2239 1 7.89016C1 4.76266 3.36667 1.41016 6.88889 1.41016C8.91111 1.41016 10.2333 2.43391 11 3.33391C11.7667 2.43391 13.0889 1.41016 15.1111 1.41016Z" stroke="#2C2C2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
                `;

                productHtml += productHTML;
            });
        } else {
            productHtml = '<p>No products available.</p>';
        }

        $(".product-list").html(productHtml);

        // Code start Click add wishlist
        const initialProducts = JSON.parse(localStorage.getItem('productIds')) || [];
        const wishlists = document.querySelectorAll('.icon-wishlist');

        wishlists.forEach(wishlist => {
            wishlist.addEventListener('click', function (event) {
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

        $(".btn-page").on("click", function () {
            pageIndex = parseInt($(this).data("page"));
            fetchData();
        });
    }

    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
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
        $(".filter-button").trigger("click");
    }, 500);
}

$(window).on("beforeunload", function () {
    $("#select-ics").find(`option[value="${codeValue}"]`).remove(); 
});



});

jQuery(window).on('load', function () {
    jQuery('.filter-button').click();
});