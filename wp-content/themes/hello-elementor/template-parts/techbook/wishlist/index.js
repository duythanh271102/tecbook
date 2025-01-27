
// Js click added btn wishlist
document.addEventListener('DOMContentLoaded', function() {
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
                this.classList.add('added');
            } else {
                storedProducts = storedProducts.filter(id => id !== productId);
                localStorage.setItem('productIds', JSON.stringify(storedProducts));
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
});

$(document).ready(function() {
    var baseURL;
    if (window.location.hostname === 'localhost') {
        baseURL = '/techbook';
    } else {
        baseURL = '';
    }

    function loadWishlist() {
        const productIds = JSON.parse(localStorage.getItem('productIds')) || [];

        if (productIds.length > 0) {
            $.ajax({
                url: ajax_object.ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_books_by_ids',
                    productIds: productIds
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const books = response.data.books || [];
                        const standardBooks = response.data.standardBooks || [];
                        const wishlistCount = books.length + standardBooks.length;

                        $('#wishlist-count').text(`(${wishlistCount})`);

                        let output = '<div class="product-list-wrap">';
                        const home_url = $('.product-list-wishlist').attr('data_home_url');

                        // Show books
                        if (books.length) {
                            books.forEach(function(book) {
                                output += `
                                    <a href="${home_url}/detail/book-${book.id}" class="product-item product-item-book item-product-wishlist" data-book-id="${book.id}">
                                        <div class="product-wrap">
                                                <img 
                                                    src="${book.isbn ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/${book.isbn}.jpg` : `${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png`}" 
                                                    alt="book Image" class="book-image" 
                                                    onerror="
                                                        let imgElement = this;
                                                        let extensions = ['jpg', 'png', 'jpeg', 'webp', 'gif'];
                                                        let currentExtensionIndex = 1; 
                                                        let baseSrc = '${book.isbn ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/${book.isbn}` : ''}';

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
                                            <div class="product-content">
                                                <div class="product-category">${book.publisher || '&nbsp;'}</div>
                                                <h3 class="product-title">${book.title || '&nbsp;'}</h3>
                                                <div class="product-author">${book.author || '&nbsp;'}</div>
                                            </div>
                                        </div>
                                        <div class="product-icons-list-book icon-wishlist-page">
                                            <button class="icon-wishlist btn-remove-wishlist" data-book-id="${book.id}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
                                                    <path d="M15.1111 1.41016C18.6333 1.41016 21 4.76266 21 7.89016C21 14.2239 11.1778 19.4102 11 19.4102C10.8222 19.4102 1 14.2239 1 7.89016C1 4.76266 3.36667 1.41016 6.88889 1.41016C8.91111 1.41016 10.2333 2.43391 11 3.33391C11.7667 2.43391 13.0889 1.41016 15.1111 1.41016Z" stroke="#2C2C2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </a>
                                `;
                            }); 
                        } 

                        // Show standardBooks
                        if (standardBooks.length) {
                            standardBooks.forEach(function(publisher) { 
                                const publisherImage = publisher.idProduct
                                    ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/standards/cover/${publisher.idProduct}.jpg`
                                    : `${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png`;
                                output += `
                                    <a href="${home_url}/detail/standard-${publisher.id}" class="product-item product-item-standard item-product-wishlist" data-book-id="${publisher.id}">
                                        <div class="product-wrap">
                                            <img src="${publisherImage}" alt="Product Image" class="product-image" onerror="this.onerror=null; this.src='${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png';">
                                            <div class="product-content">
                                                <div class="product-category">${publisher.referenceNumber || '&nbsp;'}</div>
                                                <h3 class="product-title">${publisher.standardTitle || '&nbsp;'}</h3>
                                                <div class="product-price">${publisher.ebookPrice || '&nbsp;'}</div>
                                            </div>
                                        </div>
                                        <div class="product-icons-list-book icon-wishlist-page">
                                            <button class="icon-wishlist btn-remove-wishlist" data-book-id="${publisher.id}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
                                                    <path d="M15.1111 1.41016C18.6333 1.41016 21 4.76266 21 7.89016C21 14.2239 11.1778 19.4102 11 19.4102C10.8222 19.4102 1 14.2239 1 7.89016C1 4.76266 3.36667 1.41016 6.88889 1.41016C8.91111 1.41016 10.2333 2.43391 11 3.33391C11.7667 2.43391 13.0889 1.41016 15.1111 1.41016Z" stroke="#2C2C2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </a>
                                `;
                            });
                        }   

                        output += '</div>';
                        $('.product-list-wishlist').html(output);
                        $('#loading-container').hide();
                    } else {
                        $('.product-list-wishlist').html('<p>There are no products in the Wishlist.</p>');
                        $('#wishlist-count').text(`(0)`);
                        $('#loading-container').hide(); 
                    }
                },
                error: function(xhr, status, error) {
                    alert('There are no products in the Wishlist. Please try again later.');
                    $('#wishlist-count').text(`(0)`);
                    $('#loading-container').hide();
                }
            });
        } else {
            $('.product-list-wishlist').html('<p>There are no products in the Wishlist.</p>');
            $('#wishlist-count').text(`(0)`);
            $('#loading-container').hide();
        }
    }

    loadWishlist();

    // Remove item wishlist page
    $(document).on('click', '.btn-remove-wishlist', function(e) {
        $('#loading-container').show();
        e.preventDefault();
        const productId = $(this).data('book-id');

        let productIds = JSON.parse(localStorage.getItem('productIds')) || [];
        productIds = productIds.filter(id => id !== productId.toString());
        localStorage.setItem('productIds', JSON.stringify(productIds));

        loadWishlist();
    });
});

// Load page
$(window).on('load', function() {
    $('#loading-container').hide();
});