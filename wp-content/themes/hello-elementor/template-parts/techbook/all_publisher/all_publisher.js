let pageIndex = 1;
let pageSize = 20;
var letter = '';
jQuery(document).ready(function($) {


    $(".custom-pagination").hide();
    var baseURL;
    if (window.location.hostname === 'localhost') {
        baseURL = '/techbook';
    } else {
        baseURL = '';
    }
    var categories = [
        'AAMA', 'IEEE', 'WRC', 'WRI', 'TCVN', 'QCVN', 'CIE', 'CODEX STAN',
        'IEC', 'ISO', 'ITU', 'CISPR', 'ĐLVN', 'EN', 'ASTM', 'BS', 'ANSI',
        'DIN', 'JIS', 'KS', 'ASME', 'API', 'NFPA', 'AASHTO', 'UL', 'AGA'
    ];

    categories.forEach(function(category) {
        $('.modal-content-publisher .categories').append(
            '<button class="category-item">' + category + '</button>'
        );
    });

    var modal = $('#bookCategoryModal');
    var selectedOption = $('.selected-option');
    var searchCategory = $('.search-category');

    searchCategory.on('click', function(event) {
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

    $(document).on('click', function(event) {
        if (!$(event.target).closest('.modal-book, .search-category').length) {
            modal.hide();
        }
    });

    $(document).on('click', '.category-item', function() {
        var category = $(this).text();
        selectedOption.text(category);
        modal.hide();
    });

    $('.view-all').on('click', function(event) {
        event.preventDefault();
        alert('View all categories');
    });

    $('.letter').on('click', function() {
        $('.letter').removeClass('active');
        $(this).addClass('active');
        $('#jump-to').addClass('inactive');
    });

    $('#jump-to').on('click', function() {
        $('.letter').removeClass('active');
        $(this).removeClass('inactive');    
    });


    var letter = '';

    function loadPublishersByLetter(letter, page) {
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_publishers_by_letter',
                letter: letter,
                page: page
            },
            success: function(response) {
                $('.organization-list').html(response);
            },
            error: function(error) {
                console.log('Error:', error);
            },
            complete: function() {
                $('#loading-container').hide();
            }
        });
    }

    loadPublishersByLetter('', 1);

    $('.letter').on('click', function() {
        $('#loading-container').show();
        letter = $(this).text().trim();
        loadPublishersByLetter(letter, 1);
    });

    $(document).on('click', '.page-num', function() {
        $('#loading-container').show();
        var page = $(this).data('page');
        loadPublishersByLetter(letter, page);
    });

    $('#jump-to').on('click', function() {
        $('#loading-container').show();
        letter = ''; 
        loadPublishersByLetter('', 1);
    });
    //reposive

    function checkScreenWidth() {
        if ($(window).width() <= 1224) {
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




    $(".search-button").on("click", function() {
        $(".jump-bar").hide();
        pageIndex = 1; 
        pageSize =50;
        fetchData();
    });
    
    function fetchData() {
        $("#loading-container").show();
    
        const keyword = $(".search-input").val() || ''; // Đảm bảo keyword là chuỗi rỗng nếu không nhập
    
        const data = {
            tokenKey: "4XwMBElYC3xgZeIW0IZ1H42zyvDNM5h7",
            pageIndex: pageIndex,
            pageSize: pageSize,
            keyword: keyword
        };
    
        $.ajax({
            url: "https://115.84.178.66:8028/api/Publishers/GetPaging",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function(response) {
                const organizations = response.data.items || [];
                const totalRows = response.data.totalRows || 0;
    
                renderProducts(organizations);
    
                if (totalRows > pageSize) {
                    renderPagination(totalRows, pageSize);
                    $(".custom-pagination").show();
                } else {
                    $(".custom-pagination").hide();
                }
    
                $("#loading-container").hide();
            },
            error: function(error) {
                console.error("Lỗi khi lấy dữ liệu: ", error);
                $("#loading-container").hide();
            }
        });
    }
    
    function renderProducts(organizations) {
        let productHtml = '';
    
        if (organizations.length > 0) {
            organizations.forEach(organization => {
                productHtml += `
                    <a href="${baseURL}/detail/publisher-${organization.id ? organization.id : ''}" class="organization-card">
                        <div class="card-content">
                            <div class="image-organization">
                                <img src="${organization.avatarPath && organization.avatarPath !== '' 
                                            ? 'https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/' + organization.avatarPath 
                                            : baseURL + '/wp-content/uploads/2024/09/Rectangle-17873.png'}" 
                                     alt="${organization.abbreviation ? organization.abbreviation + ' Logo' : 'Default Logo'}">
                            </div>
    
                            <div class="description">
                                <p style="font-family: Ford Antenna; font-size: 16px; font-weight: 500; line-height: 25.5px; letter-spacing: 0.015em; text-align: left;">
                                    ${organization.englishTitle 
                                        ? (() => {
                                            const parts = organization.englishTitle.split(' - ');
                                            return parts.length === 2 
                                                ? `<span style="color: #1E00AE;">${parts[0]}</span> - ${parts[1]}`
                                                : organization.englishTitle;
                                        })()
                                        : 'N/A'}
                                </p>
                                <p>${organization.englishDescription ? organization.englishDescription : 'No title available'}</p>
                            </div>
                            <div class="document-action">
                                <img src="${baseURL}/wp-content/uploads/2024/09/Icon-8.svg" alt="Arrow Icon" class="icon-card">
                            </div>
                        </div>
                    </a>
                `;
            });
        } else {
            productHtml = '<p>Hiện không có sản phẩm nào.</p>';
        }
    
        $(".organization-list").html(productHtml);
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

        if (totalPages > 1) {
            paginationHtml += `<button class="btn-page ${pageIndex === totalPages ? 'active' : ''}" data-page="${totalPages}">${totalPages}</button>`;
        }
    
        $(".custom-pagination").html(paginationHtml);
    
        // Xử lý sự kiện click trên các nút phân trang
        $(".btn-page").on("click", function () {
            pageIndex = parseInt($(this).data("page"));
            fetchData(); // Hàm này sẽ gọi dữ liệu cho trang hiện tại
        });
    }
    
    



    
});
