
document.addEventListener('DOMContentLoaded', function() {
    function getCartItemsFromLocalStorage() {
        const data = localStorage.getItem('cartItems');
        return data ? JSON.parse(data) : [];
    }
    const initialCartItems = getCartItemsFromLocalStorage();

    // Show data and display sidebar cart
    var baseURL;
    if (window.location.hostname === 'localhost') {
        baseURL = 'http://localhost/techbook';
    } else {
        baseURL = '';
    }

    var modal = $("#cartModal");
    var overlay = $("#modalOverlay");

    // Ajax
    window.loadCartItemsFromServer = function(cartItems, callback) {
        const productIds = cartItems.map(item => item.id);

        if (typeof ajax_object === 'undefined' || !ajax_object.ajaxurl) {
            console.error('AJAX object or AJAX URL is not defined.');
            callback([]); 
            return;
        } else {
            console.log('Ajax called!');
        }

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
                    const books = response.data.books || [];
                    const standardBooks = response.data.standardBooks || [];

                    if (response.success) {
                        callback(books, standardBooks);
                    } else {
                        console.error('Failed to load books from server.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading books from server:', error);
                }
            });
        } else {
            callback([]);   
        }
    };

    function renderCartModal() {
        var modalContent = $(".modal-content");
        var cartItems = getCartItemsFromLocalStorage();
        var headerHTML = `
            <div class="header1">
                <div class="title1-header">
                    <img src="${baseURL}/wp-content/uploads/2024/09/Icon-11.svg" alt="Cart Icon" class="cart-icon" /> Cart
                </div>
                <div class="close-section">
                    <p class="close-text">Close</p>
                    <span class="close">&times;</span>
                </div>
            </div>
        `;

        if (cartItems.length === 0) {
            modalContent.html(`
                ${headerHTML}
                <div class="empty-cart">
                    <img src="${baseURL}/wp-content/uploads/2024/09/shopping-cart-remove-02.svg" alt="Empty Cart" />
                    <p>No products in the cart</p>
                </div>
            `);
            attachCloseEventHandlers();
            $('#loading-container').hide();
        } else {
            loadCartItemsFromServer(cartItems, function (books, standardBooks) {
                var cartHTML = `${headerHTML} <div class="cart-items">`;
                var total = 0;

                const localStorageData = JSON.parse(localStorage.getItem("cartItems")) || [];

                const convertedBooks = books.map(book => ({
                    ...book,
                    printPrice: parseFloat(book.pricePrint) || 0,
                    ebookPrice: parseFloat(book.priceeBook) || 0
                }));

                var allItems = [...convertedBooks, ...standardBooks];
                allItems.forEach(function (item) {
                    const cartItem = localStorageData.find(itemInCart => String(itemInCart.id) === String(item.id));

                    if (cartItem && cartItem.priceTypes && Array.isArray(cartItem.priceTypes)) {
                        let itemTotal = 0;
                        let priceTypeHTML = cartItem.priceTypes.map(priceType => {
                            let price = parseFloat(priceType.price) || 0;
                            let quantity = parseInt(priceType.quantity, 10) || 0; 
                            let subTotal = price * quantity;
                            itemTotal += subTotal;
                        }).join('');

                        total += itemTotal;

                        const standard = standardBooks.find(book => book.idProduct === item.idProduct);
                        let output = '';
                        let linkProduct = '';

                        if (standard) {
                            const publisherImage = standard.idProduct
                                ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/standards/cover/${standard.idProduct}.jpg`
                                : `${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png`;

                            output += `
                                <img src="${publisherImage}" alt="Product Image" class="product-image" 
                                    onerror="this.onerror=null; this.src='${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png';">
                            `;

                            linkProduct += `
                                <a class="cart-item" href="${baseURL}/detail/standard-${item.id}" data-book-id="${item.id}">
                            `;
                        } else {
                            const book = books.find(book => book.id === item.id); 
                            if (book) {
                                const bookImage = book.isbn
                                    ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/${book.isbn}.jpg`
                                    : `${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png`;

                                output += `
                                    <img src="${bookImage}" alt="Book Image" class="book-image" 
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
                                    ">
                                `;    
                                linkProduct += `
                                    <a class="cart-item" href="${baseURL}/detail/book-${item.id}" data-book-id="${item.id}">
                                `;
                            }
                        }

                        cartItem.priceTypes.forEach(priceTypeObj => {
                            const price = parseFloat(priceTypeObj.price) || 0;

                            cartHTML += `
                                ${linkProduct}
                                    <div class="cart-item-image">
                                        ${output}   
                                    </div>
                                    <div class="cart-item-details">
                                        <p class="cart-item-cate">${item.publisher || item.referenceNumber}</p>
                                        <p class="cart-item-title">${item.title || item.standardTitle}</p>
                                        <p class="cart-item-quantity">${priceTypeObj.quantity || 0} x $${price.toFixed(2)}</p>
                                    </div>
                                    <div class="btn-cart-remove">
                                        <div class="icon-cart-remove" data-book-id="${item.id}" data-price-type="${priceTypeObj.priceType}">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18 6L6 18M6 6L18 18" stroke="#2C2C2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            `;
                        });
                    }
                });

                cartHTML += `
                    </div>
                    <div class="cart-total">
                        <p>Total:</p> 
                        <span>$${total.toFixed(2)}</span>
                    </div>      
                    <div class="cart-button">
                        <a href="${baseURL}/cart" class="view-cart-btn">View cart</a>
                    </div>
                `;

                modalContent.html(cartHTML);
                attachCloseEventHandlers();
                $('#loading-container').hide();
            });
        }
        // $('#loading-container').hide();
    }
    window.renderCartModal = renderCartModal;

    // Function event close for modal and overlay
    function attachCloseEventHandlers() {
        var closeModal = $(".close");
        var overlay = $(".overlay");

        if (closeModal.length) {
            closeModal.off("click").on("click", function() {
                modal.removeClass("active");
                overlay.hide();
            });
        }

        if (overlay.length) {
            overlay.off("click").on("click", function() {
                modal.removeClass("active");
                overlay.hide();
            });
        }
    }
    window.attachCloseEventHandlers = attachCloseEventHandlers;

    // Total quantity
    function getTotalQuantity() {
        const cartItems = getCartItemsFromLocalStorage();
        
        return cartItems.reduce((total, item) => {
            if (item.priceTypes && Array.isArray(item.priceTypes)) {
                const itemQuantity = item.priceTypes.reduce((subtotal, priceType) => subtotal + (priceType.quantity || 0), 0);
                return total + itemQuantity;
            }
            return total; 
        }, 0);
    }

    // Total quantity btn cart in header
    function updateCartQuantityDisplay() {
        const totalQuantity = getTotalQuantity(); 
        
        // Update cart for desktop
        const desktopCartElement = document.querySelector('.header-cart.desktop');
        if (desktopCartElement) {
            let quantityDivDesktop = desktopCartElement.querySelector('.total-number-product');
            
            if (!quantityDivDesktop) {
                quantityDivDesktop = document.createElement('div');
                quantityDivDesktop.classList.add('total-number-product');
                desktopCartElement.appendChild(quantityDivDesktop);
            }

            if (totalQuantity > 0) {
                quantityDivDesktop.textContent = `${totalQuantity}`;
            } else {
                quantityDivDesktop.textContent = `0`;
            }
        }

        // Update cart for mobile
        const mobileCartElement = document.querySelector('.header-cart.mobile');
        if (mobileCartElement) {
            let quantityDivMobile = mobileCartElement.querySelector('.total-number-product');
            
            if (!quantityDivMobile) {
                quantityDivMobile = document.createElement('div');
                quantityDivMobile.classList.add('total-number-product');
                mobileCartElement.appendChild(quantityDivMobile);
            }

            if (totalQuantity > 0) {
                quantityDivMobile.textContent = `${totalQuantity}`;
            } else {
                quantityDivMobile.textContent = `0`;
            }
        }
    }

    window.updateCartQuantityDisplay = updateCartQuantityDisplay;
    updateCartQuantityDisplay();
    renderCartModal();

    var cartIcon = $(".header-cart");   
    if (cartIcon.length) {
        cartIcon.on("click", function() {
            modal.addClass("active");
            overlay.show();
        });
    } else {
        console.error("Cart icon not found!");
    }

    // Click add to cart
    $(document).on('click', '.add-to-cart.btn-cart-detail', function(e) {
        // $('#loading-container').show();
        e.preventDefault();
        
        updateCartQuantityDisplay();
        renderCartModal();
    });

    // Function click remove item in care page
    $(document).on('click', '.icon-cart-remove', function (e) {
        $('#loading-container').show();
        e.preventDefault();

        const productId = $(this).data('book-id').toString(); 
        const priceTypeToRemove = $(this).data('price-type'); 
        let cartItems = getCartItemsFromLocalStorage();

        cartItems = cartItems.map(item => {
            if (item.id.toString() === productId) {
                item.priceTypes = item.priceTypes.filter(pt => pt.priceType !== priceTypeToRemove);
            }
            return item;
        }).filter(item => item.priceTypes.length > 0);

        localStorage.setItem('cartItems', JSON.stringify(cartItems));

        updateCartQuantityDisplay();
        renderCartModal();
    });
});