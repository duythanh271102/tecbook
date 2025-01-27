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

// Mặc định mở tab đầu tiên
document.addEventListener("DOMContentLoaded", function() {
    document.querySelector(".tab-link").click();
});


jQuery(document).ready(function($) {
    
    var home_url;
    if (window.location.hostname === 'localhost') {
        home_url = '/techbook';
    } else {
        home_url = '';
    }
    const buttonIcon1 = $('#butoon-book-icon3');

    if (buttonIcon1.length && idProduct) {
        buttonIcon1.on('click', function () {
            const pdfUrl = `https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/standards/preview/${idProduct}.pdf`;
            window.open(pdfUrl, '_blank');
        });
    }

    const documentHistoryDiv = $('#document-history');
    if (documentHistoryDiv.length) {
        let ids;
        try {
            ids = JSON.parse(documentHistoryDiv.attr('data-ids'));
        } catch (error) {
            console.error('Error parsing JSON from data-ids:', error);
            return;
        }

        function fetchProductData(id) {
            const requestBody = {
                tokenKey: '4XwMBElYC3xgZeIW0IZ1H42zyvDNM5h7',
                pageIndex: 1 ,
                pageSize : 10,
                item: { idProduct: String(id) }
            };

            return fetch('https://115.84.178.66:8028/api/Standards/GetPaging', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(requestBody)
            })
            .then(response => {
                if (!response.ok) {
                    console.error(`Fetch request failed for ID ${id} with status ${response.status}`);
                    return null;
                }
                return response.json();
            })
            .then(data => {
                console.log("test :",data);
                if (data && data.data) {
                    return data.data;
                    
                }
                return null;
            })
            .catch(error => {
                console.error(`Error fetching product data for ID ${id}:`, error);
                return null;
            });
        }


        Promise.all(ids.map(id => fetchProductData(id)))
            .then(products => {
                products = products.filter(product => product !== null);

                if (products.length > 0) {
                    renderProducts(products);
                    saveProductsToDatabase(products);
                } else {
                    documentHistoryDiv.html('<p>No related products found.</p>');
                }
            });
    }

    function renderProducts(products) {
    
        let html = '';
    
        products.forEach((productData, index) => {
            const items = productData?.items || [];  
            items.forEach((product, itemIndex) => {
    
                html += `
                    <a href="${home_url}/detail/standard-${product.id ? parseInt(product.id) : ''}" class="document-item">
                        <div class="document-info">
                            <h3 class="document-title">
                                ${product.referenceNumber && product.referenceNumber.trim() !== '' ? escapeHtml(product.referenceNumber) : ''}
                            </h3>
                            <p class="document-description">
                                ${product.standardTitle && product.standardTitle.trim() !== '' ? escapeHtml(product.standardTitle) : ''}
                            </p>
                            <div class="document-meta">
                                ${product.publishedDate ? `
                                    <span>
                                        <img src="${home_url}/wp-content/uploads/2024/09/calendar.svg" alt="Date Icon">
                                        Published Date: 
                                        ${escapeHtml(product.publishedDate)}
                                    </span>
                                ` : ''}
                                ${product.pages ? `
                                    <span>
                                        <img src="${home_url}/wp-content/uploads/2024/09/book-square.svg" alt="Pages Icon">
                                        Pages: 
                                        ${escapeHtml(product.pages)}
                                    </span>
                                ` : ''}
                                ${product.status ? `
                                    <span>
                                        <img src="${home_url}/wp-content/uploads/2024/09/Icon-7.svg" alt="Status Icon" class="status-icon1">
                                    Status: 
                                        ${escapeHtml(product.status)}
                                    </span>
                                ` : ''}
                            </div>
                        </div>
                        <div class="document-action">
                            <img src="${home_url}/wp-content/uploads/2024/09/Icon-8.svg" alt="Arrow Icon" class="icon-card">
                        </div>
                    </a>
                `;
            });
        });

        documentHistoryDiv.html(html);
    }
    
    
    

    function saveProductsToDatabase(products) {
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "save_standards_to_cache",
                standards: products
            },
            success: function(res) {
                console.log("Data successfully saved to the database:", res);
            },
            error: function(err) {
                console.error("Error saving data to the database:", err);
            }
        });
    }

    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
});