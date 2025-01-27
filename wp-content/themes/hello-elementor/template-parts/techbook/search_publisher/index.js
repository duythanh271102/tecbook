
let pageIndex = 1;
let pageSize = parseInt($("#page-size-select").val()) || 10;
jQuery(document).ready(function ($) {

    var baseURL;
    if (window.location.hostname === 'localhost') {
        baseURL = '/techbook';
    } else {
        baseURL = '';
    }


    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    const replaceValue = getQueryParam("replace");
    const replacedByValue = getQueryParam("replacedBy") || getQueryParam("repalcedBy");
    const referencedStandardsValue = getQueryParam("referencedStandards");
    const referencingStandardsValue = getQueryParam("referencingStandards");
    const referenceValue = getQueryParam("reference");

    function parseCustomParam(param) {
        if (param && param.includes(":")) {
            const [ref, year] = param.split(":");
            return { ref: ref.trim(), year: year.trim() };
        } else if (param) {
            return { ref: param.trim(), year: null };
        }
        return null;
    }

    if (replaceValue) {
        const parsedParam = parseCustomParam(replaceValue);
        if (parsedParam) {
            $("#ref-number").val(decodeURIComponent(parsedParam.ref));
            if (parsedParam.year) {
                $("#pub-year").val(decodeURIComponent(parsedParam.year));
            }
        }
    } else if (replacedByValue) {
        const parsedParam = parseCustomParam(replacedByValue);
        if (parsedParam) {
            $("#ref-number").val(decodeURIComponent(parsedParam.ref));
            if (parsedParam.year) {
                $("#pub-year").val(decodeURIComponent(parsedParam.year));
            }
        }
    } else if (referencedStandardsValue) {
        const parsedParam = parseCustomParam(referencedStandardsValue);
        if (parsedParam) {
            $("#ref-number").val(decodeURIComponent(parsedParam.ref));
            if (parsedParam.year) {
                $("#pub-year").val(decodeURIComponent(parsedParam.year));
            }
        }
    } else if (referencingStandardsValue) {
        const parsedParam = parseCustomParam(referencingStandardsValue);
        if (parsedParam) {
            $("#ref-number").val(decodeURIComponent(parsedParam.ref));
            if (parsedParam.year) {
                $("#pub-year").val(decodeURIComponent(parsedParam.year));
            }
        }
    }

    if (replaceValue || replacedByValue || referencedStandardsValue || referencingStandardsValue) {
        setTimeout(function () {
            $(".btn-search").trigger("click");
        }, 1000);
    }


    if (referenceValue) {
        $("#ref-number").val(decodeURIComponent(referenceValue));
    }

    if (referenceValue) {
        setTimeout(() => {
            $(".btn-search").trigger("click");
        }, 1000);
    }

    // Lấy tham số từ URL
    const icsCodeValue = getQueryParam("icsCode");
    const nameInEnglishValue = getQueryParam("nameInEnglish");

    if (icsCodeValue) {
        const decodedIcsCode = decodeURIComponent(icsCodeValue);
        const decodedNameInEnglish = decodeURIComponent(nameInEnglishValue);
        const $select = $("#select-ics");

        if (!$select.find(`option[value="${decodedIcsCode}"]`).length) {
            $select.append(new Option(decodedIcsCode + " - " + decodedNameInEnglish, decodedIcsCode, true, true));
        } else {
            $select.val(decodedIcsCode);
        }

        $select.trigger("change");


        setTimeout(() => {
            $(".btn-search").trigger("click");
        }, 500);
    }

    $(window).on("beforeunload", function () {
        if (icsCodeValue) {
            $("#select-ics").find(`option[value="${icsCodeValue}"]`).remove();
        }
    });

    const startYear = 2000;
    const currentYear = new Date().getFullYear();

    function populateYearSelect(selectElement) {
        for (let year = startYear; year <= currentYear; year++) {
            const option = new Option(year, year, false, false);
            selectElement.append(option);
        }
    }

    const $minYearSelect = $('#pub-year-min');
    const $maxYearSelect = $('#pub-year-max');

    populateYearSelect($minYearSelect);
    populateYearSelect($maxYearSelect);

    $minYearSelect.select2({
        placeholder: "Min to",
        allowClear: true,
        width: 'style'
    });

    $maxYearSelect.select2({
        placeholder: "Max to",
        allowClear: true,
        width: 'style'
    });

    function validateYearRange() {
        const minYear = parseInt($minYearSelect.val());
        const maxYear = parseInt($maxYearSelect.val());

        if (minYear && maxYear && minYear > maxYear) {
            alert("Năm tối thiểu không thể lớn hơn năm tối đa.");
            $minYearSelect.val(null).trigger('change');
        }
    }

    $minYearSelect.on('change', validateYearRange);
    $maxYearSelect.on('change', validateYearRange);

    $('#select-publisher').select2({
        placeholder: "Select Publisher",
        allowClear: true,
        width: 'style'
    });

    $('#pub-year').select2({
        placeholder: "Select Year",
        allowClear: true,
        width: 'style',
        width: '100%'
    });

    $('#select-ics').select2({
        placeholder: "Select ICS Code",
        allowClear: true,
        width: 'style'
    });

    $('#select-lang').select2({
        placeholder: "Select Publisher",
        allowClear: true,
        width: 'style'
    });

    $('#select-status').select2({
        placeholder: "Select status",
        allowClear: true,
        width: 'style'
    });
    $('#by-technology-text').select2({
        placeholder: "Select technology",
        allowClear: true,
        width: 'style'
    });
    $('#by-industry-text').select2({
        placeholder: "Select industry",
        allowClear: true,
        width: 'style'
    });

    $('.btn-refresh').prop('disabled', true).addClass('disabled').removeClass('enabled');
    $('.icon1').prop('disabled', true).addClass('disabled').removeClass('enabled');

    function checkInputs() {
        let isFilled = false;
        $("#title-topics").text("");
        $("#topics").val("");

        $(".document-list").hide();
        $("#dem-so-luong").text(0);

        $('.search-box input[type="text"], .search-box textarea').each(function () {
            if ($(this).val().trim() !== '') {
                isFilled = true;
                return false;
            }
        });

        $('.search-box select').each(function () {
            if ($(this).val() !== null && $(this).val() !== '') {
                isFilled = true;
                return false;
            }
        });

        if ($('input[name="status"]:checked').val() !== 'most-recent') {
            isFilled = true;
        }

        if (isFilled) {
            $('.btn-refresh').prop('disabled', false).removeClass('disabled').addClass('enabled');
            $('.icon1').prop('disabled', false).removeClass('disabled').addClass('enabled');
        } else {
            $('.btn-refresh').prop('disabled', true).addClass('disabled').removeClass('enabled');
            $('.icon1').prop('disabled', true).addClass('disabled').removeClass('enabled');
        }
    }

    $('.search-box input, .search-box select, .search-box textarea').on('input change', function () {
        checkInputs();
    });

    $('.btn-refresh').on('click', function () {
        $('.search-box input[type="text"], .search-box textarea').val('');
        $('.search-box select').val(null).trigger('change');
        $('input[name="status"][value="most-recent"]').prop('checked', true).trigger('change');
        $(this).prop('disabled', true).addClass('disabled').removeClass('enabled');
    });

    checkInputs();







    $("#page-size-select").on("change", function () {
        pageSize = parseInt($(this).val());
        pageIndex = 1; // Đặt lại về trang đầu tiên
        fetchData(); // Tải dữ liệu mới với pageSize mới
    });
    $(".btn-search").on("click", function () {
        pageIndex = 1;
        fetchData();
        $(".document-list").show();
    });

    function fetchData() {
        $("#loading-container").show();


        const referenceNumber = $("#ref-number").val();
        const standardTitle = $("#std-title").val();
        const icsCode = $("#select-ics").val();
        const publishedDate = $("#pub-year").val();
        const replace = $("#replace-to-text").val();
        const repalcedBy = $("#replace-by-text").val();
        const referencedStandards = $("#referenced-standards-text").val();
        const referencingStandards = $("#referencing-standards-text").val();
        const byTechnology = $("#by-technology-text").val();
        const byIndustry = $("#by-industry-text").val();
        const status = $("#select-status").val();
        const standardby = $("#select-lang").val();
        const keyword = $("#keyword-search").val();
        const topics = $("#topics").val();


        const item = {
            icsCode: null
        };

        // Thêm các trường có giá trị 
        if (referenceNumber) item.referenceNumber = referenceNumber;
        if (standardTitle) item.standardTitle = standardTitle;
        if (icsCode) item.icsCode = icsCode;
        if (publishedDate) item.publishedDate = publishedDate;
        if (replace) item.replace = replace;
        if (repalcedBy) item.repalcedBy = repalcedBy;
        if (referencedStandards) item.referencedStandards = referencedStandards;
        if (referencingStandards) item.referencingStandards = referencingStandards;
        if (byTechnology) item.byTechnology = byTechnology;
        if (byIndustry) item.byIndustry = byIndustry;
        if (status) item.status = status;
        if (standardby) item.standardby = standardby;
        if (keyword) item.keyword = keyword;
        if (topics) item.topics = topics;


        const data = {
            tokenKey: "4XwMBElYC3xgZeIW0IZ1H42zyvDNM5h7",
            pageIndex: pageIndex,
            pageSize: pageSize,
            item: item

        };

        $.ajax({
            url: "https://115.84.178.66:8028/api/Standards/GetPaging",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function (response) {
                const standards = response.data.items || [];
                renderProducts(standards);
                const totalRows = response.data.totalRows || 0;

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
                        action: "save_standards_to_cache",
                        standards: standards
                    },
                    success: function (res) {
                        console.log("Dữ liệu đã được lưu vào database:", res);
                    },
                    error: function (err) {
                        console.error("Lỗi khi lưu dữ liệu vào database:", err);
                    }
                });
            },
            error: function (error) {
                console.error("Error fetching data: ", error);

                $("#loading-container").hide();
            }
        });
    }









    function renderProducts(standards) {
        let productHtml = '';

        if (standards.length > 0) {
            standards.forEach(standard => {
                const standardLink = `${baseURL}/detail/standard-${standard.id ? standard.id : ''}`;
                const productImageSrc = standard.idProduct
                    ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/standards/cover/${standard.idProduct}.jpg`
                    : `${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png`;

                // Calculate price range for each standard
                let prices = [];
                if (standard.ebookPrice && !isNaN(standard.ebookPrice)) {
                    prices.push(standard.ebookPrice * priceFactor);
                }
                if (standard.printPrice && !isNaN(standard.printPrice)) {
                    prices.push(standard.printPrice * priceFactor);
                }


                let priceDisplay;
                if (prices.length > 0) {
                    const minPrice = Math.min(...prices);
                    const maxPrice = Math.max(...prices);
                    if (minPrice === maxPrice) {
                        priceDisplay = `$${minPrice.toFixed(2)}`;
                    } else {
                        priceDisplay = `$${minPrice.toFixed(2)} - $${maxPrice.toFixed(2)}`;
                    }
                } else {
                    priceDisplay = '&nbsp;';
                }

                productHtml += `
                    <div class="product-item-search">
                        <a href="${standardLink}" class="product-link">
                            <img src="${productImageSrc}" alt="Product Image" class="product-image" 
                                    onerror="this.onerror=null; this.src='${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png';">
                        </a>
                        <div class="info-search">
                            <p class="product-group-search" style="color:blue">${standard.referenceNumber || '&nbsp;'}</p>
                            <a href="${standardLink}" style="color:#2C2C2C"><h3 class="product-title-search">${standard.standardTitle || '&nbsp;'}</h3></a>
                            <p class="product-group-search"><strong>Publisher: </strong> ${standard.standardby || '&nbsp;'}</p>
                            <p class="product-group-search"><strong>Date: </strong> ${standard.publishedDate || '&nbsp;'}</p>
                        </div>
                        
                    </div>
                `;
            });
        }
        else {
            productHtml = '<p>No products available at the moment.</p>';
        }

        $(".document-list").html(productHtml);
    }


    // function renderProducts(standards) {
    //     let productHtml = '';

    //     if (standards.length > 0) {
    //         standards.forEach(standard => {
    //             productHtml += `
    //                 // <a href="${baseURL}/detail/standard-${standard.id}" class="document-item">
    //                 // <div class="document-info">
    //                 //     <h3 class="document-title">${standard.referenceNumber || '&nbsp;'}</h3>
    //                 //     <p class="document-description">${standard.standardTitle || '&nbsp;'}</p>
    //                 //     <div class="document-meta">
    //                 //         <span>
    //                 //             <img src="${baseURL}/wp-content/uploads/2024/09/calendar.svg" alt="Date Icon">
    //                 //             Published Date: ${standard.publishedDate || '&nbsp;'}
    //                 //         </span>
    //                 //         <span>
    //                 //             <img src="${baseURL}/wp-content/uploads/2024/09/book-square.svg" alt="Pages Icon">
    //                 //             Pages: ${standard.pages || '&nbsp;'}
    //                 //         </span>
    //                 //         <span>
    //                 //             <img src="${baseURL}/wp-content/uploads/2024/09/Icon-7.svg" alt="Status Icon">
    //                 //             Status: ${standard.status || '&nbsp;'}
    //                 //         </span>
    //                 //     </div>
    //                 // </div>
    //                 //     <div class="document-action">
    //                 //         <img src="${baseURL}/wp-content/uploads/2024/09/Icon-8.svg" alt="Arrow Icon" class="icon-card">
    //                 //     </div>
    //                 // </a>
    //                 <div class="product-item-search product-item-book" data-book-id="${product.id}">
    //                     <a href="${baseURL}/detail/book-${product.id ? product.id : ''}" class="product-link">
    //                             <img 
    //                                 src="${product.isbn ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/${product.isbn}.jpg` : `${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png`}" 
    //                                 alt="Product Image" class="product-image" 
    //                                 onerror="
    //                                     let imgElement = this;
    //                                     let extensions = ['jpg', 'png', 'jpeg', 'webp', 'gif'];
    //                                     let currentExtensionIndex = 1; 
    //                                     let baseSrc = '${product.isbn ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/${product.isbn}` : ''}';

    //                                     function tryNextExtension() {
    //                                         if (currentExtensionIndex < extensions.length) {
    //                                             imgElement.src = baseSrc + '.' + extensions[currentExtensionIndex];
    //                                             currentExtensionIndex++;
    //                                         } else {
    //                                             imgElement.src = '${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png';
    //                                         }
    //                                     }

    //                                     imgElement.onerror = tryNextExtension;
    //                                     tryNextExtension();
    //                                 "
    //                             >
    //                         </a>

    //                         <a href="${baseURL}/detail/standard-${standard.id ? standard.id : ''}" class="product-link">
    //                                 <img src="<?= isset($document->idProduct) && !empty($document->idProduct) 
    //                                     ? 'https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/standards/cover/${product.isbn}.jpg' 
    //                                     : '${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png'; ?>" 
    //                                     alt="Product Image" class="product-image">
    //                             </a>


    //                     <div class="info-search">
    //                         <h3 class="product-title-search">${product.title || '&nbsp;'}</h3>
    //                         <p class="product-group-search"><strong>Author : </strong> ${product.author || '&nbsp;'}</p>
    //                         <p class="product-category-search"><strong>Subject : </strong> ${product.subjects || '&nbsp;'}</p>
    //                         <p class="product-price-search">
    //                             <strong>Price : </strong>
    //                             ${product.pricePrint ? `$${(product.pricePrint * priceFactor).toFixed(2)}` : '&nbsp;'}
    //                         </p>
    //                     </div>
    //                     <div class="button-search">
    //                         <button class="button-cart-search icon-cart">
    //                             <img src="${baseURL}/wp-content/uploads/2024/09/shopping-bag-02-3.svg" alt="Add to Cart"> Buy
    //                         </button>
    //                         <button class="button-wishlist-search icon-wishlist">
    //                             <img src="${baseURL}/wp-content/uploads/2024/09/Icon-13.svg" alt="Add to Favorites">Wishlist
    //                         </button>
    //                     </div>
    //                 </div>

    //             `;
    //         });
    //     } else {
    //         productHtml = '<p>No products available at the moment.</p>';
    //     }

    //     $(".document-list").html(productHtml);
    // }

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



    $(".char").on("click", function () {
        $("#loading-container").show();
        const letter = $(this).text();
        $("#lua-chon-topic").text(letter);
        $(".char").removeClass("active");
        $(this).addClass("active");

        $("#title-topics").text("");
        $("#topics").val("");

        $(".document-list").hide();
        $("#dem-so-luong").text(0);

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'fetch_topic_data',
                letter: letter
            },
            success: function (response) {
                if (response.success) {
                    const data = response.data;
                    if (data.length > 0) {
                        let output = '';
                        data.forEach(function (item) {
                            $("#loading-container").hide();
                            output += `<div class="result-item" data-code="${item.code}">${item.title}</div>`;
                        });
                        $("#results-container").html(output);
                    } else {
                        $("#loading-container").hide();
                        $("#results-container").html('<div class="no-data">No data found</div>');
                    }
                } else {
                    $("#loading-container").hide();
                    $("#results-container").html('<div class="no-data">No data found</div>');
                }
            },
            error: function (error) {
                console.log('Error fetching data:', error);
                $("#results-container").html('<div class="no-data">Error fetching data</div>');
            }
        });
    });

    $(document).on("click", ".result-item", function () {
        const selectedCode = $(this).data("code");
        const selectedTitle = $(this).text();

        $("#title-topics").text(selectedTitle);
        $("#topics").val(selectedCode);
        $(".document-list").show();

        pageIndex = 1;
        fetchData();
    });

});


