function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tab-link");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

document.addEventListener("DOMContentLoaded", function() {
    document.querySelector(".tab-link").click();
});

// ====================== Add to cart ======================= // 

// Get items in Localstorage
function getCartItemsFromLocalStorage() {
    const data = localStorage.getItem('cartItems');
    return data ? JSON.parse(data) : [];
}

function setCartItemsToLocalStorage(items) {
    localStorage.setItem('cartItems', JSON.stringify(items));
}

function handleCartClick(button) {
    const $productItem = $(button).closest('.product-item-book');
    const productId = $productItem.data('book-id');
    const productName = $productItem.data('book-name');
    const priceType = $(button).data('book-price');
    const price = $(button).data('book-pricebook');
    const $quantityInput = $productItem.find('.product-quantity');
    let quantity = $quantityInput.length ? parseInt($quantityInput.val(), 10) : 0;

    if (isNaN(quantity) || quantity < 1) {
        return;
    }

    // if (!productId || !productName || !priceType || price === null) {
    //     console.error("Product ID, name, price type, or price not found.");
    //     return;
    // }

    let storedCartItems = getCartItemsFromLocalStorage();
    const existingProductIndex = storedCartItems.findIndex(item => item.id === productId);

    if (existingProductIndex === -1) {
        storedCartItems.push({
            id: productId,
            name: productName,
            priceTypes: [   
                {
                    priceType: priceType,
                    price: price,
                    quantity: quantity
                }
            ]
        });

        $(button).addClass('added');
    } else {
        const priceTypeIndex = storedCartItems[existingProductIndex].priceTypes.findIndex(pt => pt.priceType === priceType);

        if (priceTypeIndex === -1) {
            storedCartItems[existingProductIndex].priceTypes.push({
                priceType: priceType,
                price: price,
                quantity: quantity
            });
        } else {
            storedCartItems[existingProductIndex].priceTypes[priceTypeIndex].quantity += quantity;
        }

        $(button).addClass('added');
    }
    setCartItemsToLocalStorage(storedCartItems);
}

// Update number new quantity to local storage
function updateQuantitiesInLocalStorage(button) {
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    const $productItem = $(button).closest('.product-item-book');
    const productId = $productItem.data('book-id');
    const productName = $productItem.data('book-name');
    const priceType = $(button).data('book-price'); 
    const price = $(button).data('book-pricebook');
    const quantityInput = $productItem.find(`.qty-input[data-book-quantity="quantity_${priceType}"]`);
    const quantity = parseInt(quantityInput.val(), 10);

    if (!Number.isInteger(quantity) || quantity <= 0) {
        alert('Invalid! Order quantity must be greater than 0.');
        return;
    } else {
        alert("Order successful!");
    }

    const storedItemIndex = cartItems.findIndex(item => item.id === productId);

    if (storedItemIndex > -1) {
        const priceTypeIndex = cartItems[storedItemIndex].priceTypes.findIndex(pt => pt.priceType === priceType);

        if (priceTypeIndex > -1) {
            if (quantity > 0) {
                cartItems[storedItemIndex].priceTypes[priceTypeIndex].quantity = quantity;
            } else {
                cartItems[storedItemIndex].priceTypes.splice(priceTypeIndex, 1);
            }
        } else if (quantity > 0) {
            cartItems[storedItemIndex].priceTypes.push({ priceType: priceType, price: price, quantity: quantity });
        }

        if (cartItems[storedItemIndex].priceTypes.length === 0) {
            cartItems.splice(storedItemIndex, 1);
        }
    } else if (quantity > 0) {
        cartItems.push({
            id: productId,
            name: productName,
            priceTypes: [   
                {
                    priceType: priceType,
                    price: price,
                    quantity: quantity
                }
            ]
        });
    }

    localStorage.setItem('cartItems', JSON.stringify(cartItems));
}

// Load quantities in localstorage display to input quantity in load page
function loadQuantitiesFromLocalStorage() {
    const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

    cartItems.forEach(item => {
        const $productItem = $(`.product-item-book[data-book-id="${item.id}"]`);
        if ($productItem.length > 0 && Array.isArray(item.priceTypes)) {
            item.priceTypes.forEach(priceTypeItem => {
                const quantityInput = $productItem.find(`.qty-input[data-book-quantity="quantity_${priceTypeItem.priceType}"]`);
                if (quantityInput.length > 0) {
                    quantityInput.val(priceTypeItem.quantity);
                }
            });
        }
    });
}

// Call function loadQuantitiesFromLocalStorage when the page is loaded
$(document).ready(function () {
    loadQuantitiesFromLocalStorage();
});

$(document).ready(function () {
    $('.btn-cart-detail').on('click', function (event) {
        event.preventDefault();

        const button = this;
        handleCartClick(button);
        updateQuantitiesInLocalStorage(button);
    });
});