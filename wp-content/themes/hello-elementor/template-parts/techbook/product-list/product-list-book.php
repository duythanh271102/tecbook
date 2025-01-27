<?php
/**
 * The template for displaying footer.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<style>





.product-image {
    width: 200px;
    height: 183px;
    margin: 12px 0;
    border-radius: 5px;
    padding: 0px 30px;
    background-color: #fff; 
    display: flex;
    justify-content: center;
    align-items: center;
    object-fit: cover; 
}
.discount {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 50px;
    color: #fff;
    border-radius: 5px;
    margin: -10px;
    height: 25px;
    transition: all 0.3s ease;
    position: relative;
}
.product-item:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-color: #157FFF;
    cursor: pointer;
}

.has-discount {
    background-color: #FF2E00;
}

.no-discount {
    background-color: transparent;
}

.product-category, .product-title, .product-group, .product-price {
    min-height: 20px;
}

.product-category {
    color: #157FFF;
    margin-top: -10px;
    font-family: Ford Antenna;
    font-size: 12px;
    font-weight: 300;
    line-height: 36px;
}

.product-title {
    font-family: Ford Antenna;
    font-size: 16px;
    font-weight: 500;
    line-height: 24px;
    margin-bottom: 5px;
    color: #2c2c2c;
}
a.product-link {
    text-decoration: none;
}
.product-group {
    font-family: Ford Antenna;
    font-size: 12px;
    font-weight: 300;
    line-height: 21px;
    color: #7E7E7E;
    margin-bottom: 5px;
}

.product-price {
    font-family: Ford Antenna;
    font-size: 16px;
    font-weight: 500;
    line-height: 27px;
    margin-bottom: 5px;
}

.product-icons-list-book{
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-right: -10px;
}
.icon-list-book1{
    display: none;
    border-right: 1px solid #EDEDE8;
    padding-right: 20px;
}
.icon-list-book2{
    display: none;
}

.product-item:hover .icon-list-book1,
.product-item:hover .icon-list-book2{
    display: flex;
}
.product-item:hover .product-image {
    clip-path: inset(0 0 20% 0); 
    height: 183px;
}

.product-item:hover .product-category{
    margin-top: -50px;
}

.icon-wishlist-page .icon-wishlist svg,
.icon-wishlist-page .icon-wishlist svg > path {
    fill: #FF2E00;
    stroke: #FF2E00;
}

.product-icons-list-book .icon-wishlist {
    cursor: pointer; 
}

.product-icons-list-book .icon-wishlist:hover svg > path {
    transition: all 0.3s ease;
}

.product-icons-list-book .icon-wishlist:hover svg > path {
    stroke: #FF2E00;
}

.product-icons-list-book .icon-cart.added svg > path ,
.product-icons-list-book .icon-wishlist.added svg > path {
    stroke: #FF2E00;
}

.product-item {
    flex: 0 0 208px;
    box-sizing: border-box;
    padding: 10px;
    background-color: #fff;
    border-radius: 5px;
    border: 1px solid #EDEDED;
    text-decoration: none !important;
    color: #2c2c2c;
}

.product-category,
.product-title,
.product-group,
.product-price {
    min-height: 20px;
    /* display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2; 
    overflow: hidden;
    text-overflow: ellipsis;*/
    white-space: normal; 
    line-height: 1.5; 
    /* max-height: 4em;  */
    visibility: visible; 
}
.product-group,
.product-price{
    min-height: 20px;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2; 
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal; 
    line-height: 1.5; 
    max-height: 4em; 
    visibility: visible;  
}

.product-category:empty::before,
.product-title:empty::before,
.product-group:empty::before,
.product-price:empty::before,
.product-image:empty::before  {
    content: '\00a0'; 
    visibility: hidden; 
}
 
@media screen and (max-width: 440px){
    .product-item {
        flex: 0 0 160px !important; 
        box-sizing: border-box;

    }   
    .product-image{
        height: 140px !important;
        padding: 0px 10px !important;
        width: 120px !important;
    }
    .product-category {
        font-size: 10px !important;
    }
    .product-title {
        font-size: 12px !important;
    }
    .product-group{
        font-size: 10px !important;
    }
    .product-price{
        font-size: 12px !important;
    }
}

</style>

<?php 
$price_factor = floatval(get_option('techbookapi_price_factor', 1)); 
$adjusted_pricePrint = isset($product->pricePrint) ? $product->pricePrint * $price_factor : null;
$adjusted_ebookPrice = isset($product->ebookPrice) ? $product->ebookPrice * $price_factor : null;
?>

<div class="product-item product-item-book" data-book-id="<?php echo $product->id; ?>">

    <a href="<?php echo home_url(); ?>/detail/book-<?php echo isset($product->id) ? intval($product->id) : ''; ?>" class="product-link">
        <img 
            src="<?php echo isset($product->isbn) ? 'https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/' . $product->isbn . '.jpg' : home_url() . '/wp-content/uploads/2024/09/Rectangle-17873.png'; ?>" 
            alt="Product Image" class="product-image"
            onerror="
                let imgElement = this;
                let extensions = ['jpg', 'png', 'jpeg', 'webp', 'gif'];
                let currentExtensionIndex = 1; 
                let baseSrc = '<?php echo isset($product->isbn) ? 'https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/books/cover/' . $product->isbn : ''; ?>';

                function tryNextExtension() {
                    if (currentExtensionIndex < extensions.length) {
                        imgElement.src = baseSrc + '.' + extensions[currentExtensionIndex];
                        currentExtensionIndex++;
                    } else {
                        imgElement.src = '<?php echo home_url(); ?>/wp-content/uploads/2024/09/Rectangle-17873.png';
                    }
                }

                imgElement.onerror = tryNextExtension;
                tryNextExtension();
            ">
    

    <p class="discount <?= isset($product->discount) && !empty($product->discount) ? 'has-discount' : 'no-discount'; ?>">
        <?= isset($product->discount) && !empty($product->discount) ? $product->discount : '&nbsp;'; ?>
    </p>

    

    <!-- <p class="product-category"><?= isset($product->subjects) && !empty($product->subjects) ? $product->subjects : '&nbsp;'; ?></p> -->

    <h3 class="product-title"><?= isset($product->title) && !empty($product->title) ? $product->title : '&nbsp;'; ?></h3>

    </a>

    <p class="product-group"><?= isset($product->author) && !empty($product->author) ? $product->author : '&nbsp;'; ?></p>

    <!-- Hiển thị giá điều chỉnh -->
    <!-- <p class="product-price">
        <?php 
        if (isset($adjusted_pricePrint)) {
            echo number_format($adjusted_pricePrint, 2) . ' $';
        } elseif (isset($adjusted_ebookPrice)) {
            echo number_format($adjusted_ebookPrice, 2) . ' $';
        } else {
            echo ' ';
        }
        ?>
    </p> -->

    <div class="product-icons-list-book">
        <!-- <div class="icon-list-book1 icon-action icon-cart">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25" fill="none">
                <path d="M16.0004 9.41016V6.41016C16.0004 4.20102 14.2095 2.41016 12.0004 2.41016C9.79123 2.41016 8.00037 4.20102 8.00037 6.41016V9.41015M3.59237 10.7621L2.99237 17.1621C2.82178 18.9818 2.73648 19.8917 3.03842 20.5944C3.30367 21.2118 3.76849 21.7222 4.35839 22.0439C5.0299 22.4102 5.94374 22.4102 7.77142 22.4102H16.2293C18.057 22.4102 18.9708 22.4102 19.6423 22.0439C20.2322 21.7222 20.6971 21.2118 20.9623 20.5944C21.2643 19.8917 21.179 18.9818 21.0084 17.1621L20.4084 10.7621C20.2643 9.2255 20.1923 8.45719 19.8467 7.87632C19.5424 7.36474 19.0927 6.95527 18.555 6.7C17.9444 6.41016 17.1727 6.41016 15.6293 6.41016L8.37142 6.41016C6.82806 6.41016 6.05638 6.41016 5.44579 6.7C4.90803 6.95527 4.45838 7.36474 4.15403 7.87632C3.80846 8.45719 3.73643 9.2255 3.59237 10.7621Z" stroke="#2C2C2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div> -->
        <div class="icon-list-book2 icon-action icon-wishlist">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
                <path d="M15.1111 1.41016C18.6333 1.41016 21 4.76266 21 7.89016C21 14.2239 11.1778 19.4102 11 19.4102C10.8222 19.4102 1 14.2239 1 7.89016C1 4.76266 3.36667 1.41016 6.88889 1.41016C8.91111 1.41016 10.2333 2.43391 11 3.33391C11.7667 2.43391 13.0889 1.41016 15.1111 1.41016Z" stroke="#2C2C2C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>
</div>

