$(document).ready(function() {
    var total = 0;

    function getCartItemsFromLocalStorage() {
        const data = localStorage.getItem('cartItems');
        return data ? JSON.parse(data) : [];
    }

    function setCartItemsToLocalStorage(cartItems) {
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
    }

    // Show data and display sidebar cart
    var baseURL;
    if (window.location.hostname === 'localhost') {
        baseURL = '/techbook';
    } else {
        baseURL = '';   
    }

    // Ajax
    window.loadCartItemsFromServer = function(cartItems, callback) {
        const productIds = cartItems.map(item => item.id);

        if (typeof ajax_object === 'undefined' || !ajax_object.ajaxurl) {
            console.error('AJAX object or AJAX URL is not defined.');
            callback([]); 
            return;
        } else {
            console.log('Ajax called');
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

    // Html cart page
    function renderCartList() {
        const cartContainer = $(".list-item-cart");
        const cartItems = getCartItemsFromLocalStorage();

        // Check the display of the update cart button and the payment form
        if (cartItems.length === 0) { 
            $('.btn-update-cart').hide(); 
            $('.main-content.checkout-cart').hide(); 
            $('#loading-container').hide();
        } else {
            $('.btn-update-cart').show(); 
            $('.main-content.checkout-cart').show(); 
        }

        if (cartItems.length === 0) {
            cartContainer.html(`
                <div class="empty-cart">
                    <img src="${baseURL}/wp-content/uploads/2024/09/shopping-cart-remove-02.svg" alt="Empty Cart" />
                    <p>No products in the cart</p>
                </div>
            `);
        } else {
            // loadCartItemsFromServer(cartItems, function (books, standardBooks) {
            //     let cartHTML = `
            //         <table class="cart-table">
            //             <thead>
            //                 <tr>
            //                     <th>Product</th>
            //                     <th>Price</th>
            //                     <th>Quantity</th>
            //                     <th>Subtotal</th>
            //                     <th></th>
            //                 </tr>   
            //             </thead>
            //         <tbody>
            //     `;

            //     const convertedBooks = books.map(book => ({
            //         ...book,
            //         printPrice: parseFloat(book.pricePrint) || 0,
            //         ebookPrice: parseFloat(book.priceeBook) || 0 
            //     }));    

            //     const allItems = [...convertedBooks, ...standardBooks];
            //     allItems.forEach(function (item) {
            //         const cartItem = cartItems.find(itemInCart => String(itemInCart.id) === String(item.id));

            //         if (cartItem && Array.isArray(cartItem.priceTypes)) {
            //             cartItem.priceTypes.forEach(priceTypeObj => {
            //                 const price = priceTypeObj.priceType === "price_print" ? (item.printPrice || 0) : (item.ebookPrice || 0);
            //                 const quantity = priceTypeObj.quantity || 0;
            //                 const subtotal = price * quantity;
            //                 const standard = standardBooks.find(book => book.idProduct === item.idProduct);
            //                 let output = '';
            //                 let linkProduct = '';

            //                 if (standard) {
            //                     const publisherImage = standard.idProduct
            //                         ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/standards/cover/${standard.idProduct}.jpg`
            //                         : `${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png`;

            //                     output += `
            //                         <img src="${publisherImage}" alt="Product Image" class="product-image" 
            //                             onerror="this.onerror=null; this.src='${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png';">
            //                     `;

            //                     linkProduct += `
            //                         <a href="${baseURL}/detail/standard-${item.id}">
            //                     `;
            //                 } else {
            //                     const book = books.find(book => book.id === item.id); 
            //                     if (book) {
            //                         const bookImage = book.isbn
            //                             ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/${book.isbn}.jpg`
            //                             : `${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png`;

            //                         output += `
            //                             <img src="${bookImage}" alt="Book Image" class="book-image" 
            //                             onerror="
            //                                 let imgElement = this;
            //                                 let extensions = ['jpg', 'png', 'jpeg', 'webp', 'gif'];
            //                                 let currentExtensionIndex = 1; 
            //                                 let baseSrc = '${book.isbn ? `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/${book.isbn}` : ''}';

            //                                 function tryNextExtension() {
            //                                     if (currentExtensionIndex < extensions.length) {
            //                                         imgElement.src = baseSrc + '.' + extensions[currentExtensionIndex];
            //                                         currentExtensionIndex++;
            //                                     } else {
            //                                         imgElement.src = '${baseURL}/wp-content/uploads/2024/09/Rectangle-17873.png';
            //                                     }
            //                                 }

            //                                 imgElement.onerror = tryNextExtension;
            //                                 tryNextExtension();
            //                             ">
            //                         `;      

            //                         linkProduct += `
            //                             <a href="${baseURL}/detail/book-${item.id}">
            //                         `;
            //                     }
            //                 }

            //                 cartHTML += `
            //                     <tr class="cart-item-row product-item-book" data-book-id="${item.id}" data-price-type="${priceTypeObj.priceType}">
            //                         <td class="item-td-cart cart-item-product" data-title="Product">
            //                             ${linkProduct}
            //                                 ${output}
            //                                 <div class="cart-item-info">
            //                                     <p class="cart-item-cate">${item.subjects || item.referenceNumber || 'N/A'}</p>
            //                                     <p class="cart-item-title">${item.title || item.standardTitle || 'Untitled'}</p>
            //                                     <p class="cart-item-author">${item.author || ''}</p>
            //                                 </div>
            //                             </a>
            //                         </td>
            //                         <td class="item-td-cart price cart-item-price" data-price="Price">$${price.toFixed(2)}</td>
            //                         <td class="item-td-cart cart-item-quantity" data-quantity="Quantity">
            //                             <input type="number" min="0" class="qty-input" value="${quantity}" data-book-quantity="quantity_${priceTypeObj.priceType}" data-id="${item.id}" data-price-type="${priceTypeObj.priceType}">
            //                         </td>
            //                         <td class="item-td-cart price cart-item-subtotal" data-subtotal="Subtotal">$${subtotal.toFixed(2)}</td>
            //                         <td class="btn-cart-remove">
            //                             <div class="icon-cart-remove" data-book-id="${item.id}" data-price-type="${priceTypeObj.priceType}">
            //                                 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            //                                     <path d="M18 6L6 18M6 6L18 18" stroke="#2C2C2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            //                                 </svg>
            //                             </div>
            //                         </td>
            //                     </tr>
            //                 `;
            //             });
            //         }
            //     });

            //     cartHTML += `
            //             </tbody>
            //         </table>
            //     `;
            //     cartContainer.html(cartHTML);
            //     $('#loading-container').hide();
            // });
            loadCartItemsFromServer(cartItems, function (books, standardBooks) {
                let cartHTML = `
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>   
                        </thead>
                    <tbody>
                `;

                const convertedBooks = books.map(book => ({
                    ...book,
                    printPrice: parseFloat(book.pricePrint) || 0,
                    ebookPrice: parseFloat(book.priceeBook) || 0 
                }));    

                const allItems = [...convertedBooks, ...standardBooks];
                allItems.forEach(function (item) {
                    const cartItem = cartItems.find(itemInCart => String(itemInCart.id) === String(item.id));

                    if (cartItem && Array.isArray(cartItem.priceTypes)) {
                        cartItem.priceTypes.forEach(priceTypeObj => {
                            const price = parseFloat(priceTypeObj.price) || 0;
                            const quantity = priceTypeObj.quantity || 0;
                            const subtotal = price * quantity;
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
                                    <a href="${baseURL}/detail/standard-${item.id}">
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
                                        <a href="${baseURL}/detail/book-${item.id}">
                                    `;
                                }
                            }

                            cartHTML += `
                                <tr class="cart-item-row product-item-book" data-book-id="${item.id}" data-price-type="${priceTypeObj.priceType}">
                                    <td class="item-td-cart cart-item-product" data-title="Product">
                                        ${linkProduct}
                                            ${output}
                                            <div class="cart-item-info">
                                                <p class="cart-item-cate">${item.publisher || item.referenceNumber || 'N/A'}</p>
                                                <p class="cart-item-title">${item.title || item.standardTitle || 'Untitled'}</p>
                                                <p class="cart-item-author">${item.author || ''}</p>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="item-td-cart price cart-item-price" data-price="Price">$${price.toFixed(2)}</td>
                                    <td class="item-td-cart cart-item-quantity" data-quantity="Quantity">
                                        <input type="number" min="0" class="qty-input" value="${quantity}" data-book-quantity="quantity_${priceTypeObj.priceType}" data-id="${item.id}" data-price-type="${priceTypeObj.priceType}">
                                    </td>
                                    <td class="item-td-cart price cart-item-subtotal" data-subtotal="Subtotal">$${subtotal.toFixed(2)}</td>
                                    <td class="btn-cart-remove">
                                        <div class="icon-cart-remove" data-book-id="${item.id}" data-price-type="${priceTypeObj.priceType}">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18 6L6 18M6 6L18 18" stroke="#2C2C2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                });

                cartHTML += `
                        </tbody>
                    </table>
                `;
                cartContainer.html(cartHTML);
                $('#loading-container').hide();
            });
        }
    }

    function totalPrice(callback) {
        const cartItems = getCartItemsFromLocalStorage();  
        let total = 0;

        if (cartItems.length === 0) {
            if (callback) callback(total); 
            return;
        }

        loadCartItemsFromServer(cartItems, function(books, standardBooks) {
            const convertedBooks = books.map(book => ({
                ...book,
                printPrice: parseFloat(book.pricePrint) || 0,
                ebookPrice: parseFloat(book.priceeBook) || 0 
            }));

            const allItems = [...convertedBooks, ...standardBooks];

            allItems.forEach(function(item) {
                const cartItem = cartItems.find(itemInCart => String(itemInCart.id) === String(item.id));

                if (cartItem && cartItem.priceTypes && Array.isArray(cartItem.priceTypes)) {
                    let itemTotal = 0;

                    cartItem.priceTypes.forEach(priceType => {
                        let price = priceType.price || 0;

                        if (price === 0) {
                            if (priceType.priceType === 'price_print') {
                                price = item.printPrice || 0;
                            } else if (priceType.priceType === 'price_ebook') {
                                price = item.ebookPrice || 0;
                            }
                        }

                        const quantity = priceType.quantity || 0;
                        const subTotal = price * quantity;
                        itemTotal += subTotal;
                    });

                    total += itemTotal;
                }
            });

            if (callback) callback(total);
        });
    }

    // Show total sidebar cart
    function renderCartSidebar() {
        const cartItems = getCartItemsFromLocalStorage();
        var cartSidebar = $(".list-info");

        totalPrice(function(total) {
            var cartHTML = `
                <div class="cart-summary">
                    <div class="cart-total"><span class="label-total">Total:</span> <span class="total-price">$${total.toFixed(2)}</span></div>
                </div>
            `;                 
            cartSidebar.html(cartHTML);
        });
    }

    renderCartList();
    renderCartSidebar();

    // Function update new quantity
    function updateCartQuantity() {
        let cartItems = getCartItemsFromLocalStorage();

        $('.qty-input').each(function () {
            const quantityInput = $(this);
            const productId = quantityInput.data('id');
            const priceType = quantityInput.data('price-type');
            const currentQuantity = parseInt(quantityInput.data('current-quantity'), 10) || 0;
            let newQuantity = parseInt(quantityInput.val(), 10);

            if (isNaN(newQuantity) || newQuantity < 0) {
                alert('Invalid! The quantity cannot be a negative number.');
                quantityInput.val(currentQuantity);
                return;
            }

            const cartItem = cartItems.find(item => String(item.id) === String(productId));

            if (cartItem && Array.isArray(cartItem.priceTypes)) {
                const priceTypeObjIndex = cartItem.priceTypes.findIndex(type => type.priceType === priceType);

                if (priceTypeObjIndex !== -1) {
                    if (newQuantity === 0) {
                        cartItem.priceTypes.splice(priceTypeObjIndex, 1);
                        $(`tr[data-book-id="${productId}"][data-price-type="${priceType}"]`).remove();
                    } else {
                        cartItem.priceTypes[priceTypeObjIndex].quantity = newQuantity;
                    }
                }

                if (cartItem.priceTypes.length === 0) {
                    const cartItemIndex = cartItems.findIndex(item => String(item.id) === String(productId));
                    if (cartItemIndex !== -1) {
                        cartItems.splice(cartItemIndex, 1);
                        $(`tr[data-book-id="${productId}"]`).remove();
                    }
                }
            }
        });

        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        updateCartSubtotals(cartItems); // Call function update subtotal width new quantity
    }

    // Function update subtotal for event update new qantity
    function updateCartSubtotals(cartItems) {
        cartItems.forEach(item => {
            item.priceTypes.forEach(priceTypeObj => {
                const price = priceTypeObj.priceType === "price_print" ? item.printPrice : item.ebookPrice;
                const subtotal = price * priceTypeObj.quantity;

                const cartRow = $(`tr[data-book-id="${item.id}"][data-price-type="${priceTypeObj.priceType}"]`);
                cartRow.find('.cart-item-subtotal').text(`$${subtotal.toFixed(2)}`);
            });
        });
    }

    // Function click remove item in care page
    $(document).on('click', '.icon-cart-remove', function (e) {
        e.preventDefault();
        $('#loading-container').show();

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
        updateCartQuantity();
        updateCartQuantityDisplay();
        attachCloseEventHandlers();
        renderCartModal();
        renderCartSidebar();
        renderCartList();
    });

    // Click button Update cart
    $(document).on('click', '.btn-update-cart', function(e) {
        $('#loading-container').show();
        e.preventDefault();
        
        updateCartQuantity();  
        updateCartQuantityDisplay();  
        renderCartModal();
        renderCartSidebar();
        renderCartList();
    });

    // Function to calculate sum using data in local storage
    function calculateTotalFromLocalStorage() {
      const cartItems = getCartItemsFromLocalStorage();

      const total = cartItems.reduce((total, item) => {
        const itemTotal = item.priceTypes.reduce((subtotal, priceType) => {
          return subtotal + (priceType.price * priceType.quantity);
        }, 0);
        return total + itemTotal;
      }, 0);

      return total;
    }

    $(document).on('click', '#orderButton', function(e) {
        e.preventDefault();
        $('#loading-container').show();

        const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        const fullname = document.getElementById('fullname').value;
        const phone = document.getElementById('phone').value;
        const email = document.getElementById('email').value;
        const address = document.getElementById('address').value;
        const note = document.getElementById('note').value || '';
        const totalAmount = calculateTotalFromLocalStorage();
        const orderStatus = 'new';

        if (!fullname || !phone || !email || !address || cartItems.length === 0) {
            alert('Please fill in all information.');
            $('#loading-container').hide();
            return;
        }   

        const data = {
            cartItems: cartItems,
            fullname: fullname,
            phone: phone,
            email: email,
            address: address,
            note: note,
            total_amount: totalAmount,  
            order_status: orderStatus
        };

        jQuery.ajax({
            url: ajax_objectt.ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'save_order_to_database',
                cartItems: data.cartItems,
                fullname: data.fullname,
                phone: data.phone,
                email: data.email,
                address: data.address,
                note: data.note,
                total_amount: data.total_amount,
                order_status: data.order_status
            },
            success: function(response) {
                if (response.success) {     
                    clearCartItems();
                    window.location.href = baseURL + '/order-successful';
                } else {
                    alert('Order failed: ' + response.data.message);
                    $('#loading-container').hide();
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
});

function clearCartItems() {
    localStorage.removeItem('cartItems');
}   

// Load page
$(window).on('load', function() {
    $('#loading-container').hide();
});