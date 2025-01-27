<?php
/**
 * Plugin Name: TechBook API
 * Description: Plugin để lấy và hiển thị danh sách sub-category từ API.
 * Version: 1.0
 * Author: Your Name
 */

// Chặn truy cập trực tiếp
if (!defined('ABSPATH')) {
    exit;
}

// Định nghĩa các hằng số cần thiết
define('TECHBOOKAPI_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Kích hoạt và hủy kích hoạt plugin
register_activation_hook(__FILE__, 'techbookapi_activate');
register_deactivation_hook(__FILE__, 'techbookapi_deactivate');

function techbookapi_activate() {
    techbookapi_create_database_table(); 
    techbook_create_books_table(); 
    techbook_create_publishers_table(); 
    techbook_create_standards_table();
    techbook_create_subjects_table();
    techbook_create_ics_codes_table();
    techbook_create_orders_table();
    techbook_create_industry_table();
    techbook_create_topics_table();
}


function techbookapi_deactivate() {
    // Thực hiện các thao tác khi hủy kích hoạt plugin
}


function techbookapi_create_database_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'techbookapi_items';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        api_url text NOT NULL,
        api_params text NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function techbook_create_books_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_books_cache';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        title TEXT DEFAULT NULL,
        author TEXT DEFAULT NULL,
        edition VARCHAR(255) DEFAULT NULL,
        documentStatus VARCHAR(255) DEFAULT NULL,
        publicationDate VARCHAR(255) DEFAULT NULL,
        publisher VARCHAR(255) DEFAULT NULL,
        doi VARCHAR(255) DEFAULT NULL,
        page INT DEFAULT NULL,
        isbn VARCHAR(255) DEFAULT NULL,
        subjectsCode VARCHAR(255) DEFAULT NULL,
        subjects TEXT DEFAULT NULL,
        abstract TEXT DEFAULT NULL,
        keywords TEXT DEFAULT NULL,
        pricePrint DECIMAL(10, 2) DEFAULT NULL,
        priceeBook DECIMAL(10, 2) DEFAULT NULL,
        previewPath VARCHAR(255) DEFAULT NULL,
        fullContentBookPath VARCHAR(255) DEFAULT NULL,
        createdDate DATETIME DEFAULT NULL,
        updatedDate DATETIME DEFAULT NULL,
        deleted BOOLEAN DEFAULT FALSE,
        newArrival BOOLEAN DEFAULT FALSE,
        bestSellers BOOLEAN DEFAULT FALSE,
        isFree BOOLEAN DEFAULT FALSE,
        specialOffer BOOLEAN DEFAULT FALSE,
        featured BOOLEAN DEFAULT FALSE,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Tải các file cần thiết
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/enqueue.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/functions.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/books-page.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/admin-page.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/shortcode.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/publishers-page.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/standards-page.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/subject-page.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/icscode-page.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/order-page.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/tag-book.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/tag-standard.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/tag-publisher.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/industry-page.php');
require_once(TECHBOOKAPI_PLUGIN_PATH . 'includes/topics-page.php');

function techbookapi_add_admin_menu() {
    $capability = (current_user_can('shop_manager')) ? 'shop_manager' : 'manage_options';

    add_menu_page(
        'TecBook API Settings',
        'TecBook API',
        $capability,
        'techbookapi',
        'techbookapi_admin_page',
        'dashicons-admin-generic',
        11
    );
}
add_action('admin_menu', 'techbookapi_add_admin_menu');











//publisher

function techbook_create_publishers_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_publishers';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        publisherCode VARCHAR(255) DEFAULT NULL,
        englishTitle VARCHAR(255) DEFAULT NULL,
        englishDescription TEXT DEFAULT NULL,
        vietnameseDescription TEXT DEFAULT NULL,
        abstract TEXT DEFAULT NULL,
        reference VARCHAR(255) DEFAULT NULL,
        keyword VARCHAR(255) DEFAULT NULL,
        relatedICSCode VARCHAR(255) DEFAULT NULL,
        avatarPath TEXT DEFAULT NULL,
        featured BOOLEAN DEFAULT FALSE,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}








//standards

function techbook_create_standards_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_standards'; 
    $charset_collate = $wpdb->get_charset_collate();

  
    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        idProduct VARCHAR(255) DEFAULT NULL,
        referenceNumber VARCHAR(255) DEFAULT NULL,
        standardTitle VARCHAR(255) DEFAULT NULL,
        `status` VARCHAR(255) DEFAULT NULL,
        referencedStandards TEXT DEFAULT NULL,
        referencingStandards TEXT DEFAULT NULL,
        equivalentStandards TEXT DEFAULT NULL,
        `replace` VARCHAR(255) DEFAULT NULL,
        repalcedBy VARCHAR(255) DEFAULT NULL,
        standardby VARCHAR(255) DEFAULT NULL,
        languages TEXT DEFAULT NULL,
        fullDescription TEXT DEFAULT NULL,
        ebookPrice VARCHAR(255) DEFAULT NULL,
        printPrice VARCHAR(255) DEFAULT NULL,
        bothPrice VARCHAR(255) DEFAULT NULL,
        currency VARCHAR(50) DEFAULT NULL,
        historicalEditions TEXT DEFAULT NULL,
        documentHistoryProductId VARCHAR(255) DEFAULT NULL,
        icsCode VARCHAR(255) DEFAULT NULL,
        topics TEXT DEFAULT NULL,
        keyword TEXT DEFAULT NULL,
        identicalStandards TEXT DEFAULT NULL,
        publishedDate VARCHAR(255) DEFAULT NULL,
        pages VARCHAR(255) DEFAULT NULL,
        byTechnology VARCHAR(255) DEFAULT NULL,
        byIndustry VARCHAR(255) DEFAULT NULL,
        previewPath TEXT DEFAULT NULL,
        coverPath TEXT DEFAULT NULL,
        fullPath TEXT DEFAULT NULL,
        deleted BOOLEAN DEFAULT FALSE,
        newArrival BOOLEAN DEFAULT FALSE,
        bestSellers BOOLEAN DEFAULT FALSE,
        isFree BOOLEAN DEFAULT FALSE,
        specialOffer BOOLEAN DEFAULT FALSE,
        featured BOOLEAN DEFAULT FALSE,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


//subject
function techbook_create_subjects_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_subjects';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        code VARCHAR(255) DEFAULT NULL,
        subjects VARCHAR(255) DEFAULT NULL,
        notes TEXT DEFAULT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}



//icscode
function techbook_create_ics_codes_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_ics_codes';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        icsCode VARCHAR(255) NOT NULL,
        nameInEnglish TEXT DEFAULT NULL,
        nameInVietnamese TEXT DEFAULT NULL,
        ralatedToBookSubjects TEXT DEFAULT NULL,
        keyword TEXT DEFAULT NULL,
        fatherICSCode TEXT DEFAULT NULL,
        PRIMARY KEY (icsCode)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('after_setup_theme', 'techbook_create_ics_codes_table');





//order

function techbook_create_orders_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'techbook_order';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        full_name VARCHAR(255) NOT NULL,
        phone_number VARCHAR(20) NOT NULL,
        email VARCHAR(255) DEFAULT NULL,
        address TEXT NOT NULL,
        note TEXT DEFAULT NULL,
        products JSON NOT NULL,
        total_amount DECIMAL(10, 2) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        order_status ENUM('new', 'viewed', 'shipped', 'canceled', 'delivered') DEFAULT 'new',
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('after_setup_theme', 'techbook_create_orders_table');



// industry

function techbook_create_industry_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_industry';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        subjectCode VARCHAR(255) NOT NULL,
        icsCodeFather VARCHAR(255) DEFAULT NULL,
        englishTitle VARCHAR(255) DEFAULT NULL,
        vietnameseTitle VARCHAR(255) DEFAULT NULL,
        relatedICSCode VARCHAR(255) DEFAULT NULL,
        keyword VARCHAR(255) DEFAULT NULL,
        PRIMARY KEY (subjectCode)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


// topics

function techbook_create_topics_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tecbook_topics';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        code VARCHAR(255) NOT NULL,
        title VARCHAR(255) DEFAULT NULL,
        PRIMARY KEY (code)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}







//tag 

function techbook_add_main_menu() {
    $capability = (current_user_can('shop_manager')) ? 'shop_manager' : 'manage_options';

    add_menu_page(
        'Tecbook',
        'Tecbook',
        $capability,
        'techbook_orders_page',
        'techbook_orders_page',
        'dashicons-cart',
        10
    );

    add_submenu_page(
        'techbook_orders_page',
        'Orders',
        'Orders',
        $capability,
        'techbook_orders_page',
        'techbook_orders_page'
    );

    add_submenu_page(
        'techbook_orders_page',
        'Books',
        'Books',
        $capability,
        'techbook_books_page',
        'techbook_books_page'
    );

    add_submenu_page(
        'techbook_orders_page',
        'Standards',
        'Standards',
        $capability,
        'techbook_standards_page',
        'techbook_standards_page'
    );

    add_submenu_page(
        'techbook_orders_page',
        'ICS Codes',
        'ICS Codes',
        $capability,
        'techbook_ics_codes_page',
        'techbook_ics_codes_page'
    );

    add_submenu_page(
        'techbook_orders_page',
        'Subjects',
        'Subjects',
        $capability,
        'techbook_subjects_page',
        'techbook_subjects_page'
    );

    add_submenu_page(
        'techbook_orders_page',
        'Publishers',
        'Publishers',
        $capability,
        'techbook_publishers_page',
        'techbook_publishers_page'
    );

    add_submenu_page(
        'techbook_orders_page',
        'Books Tag',
        'Books Tag',
        $capability,
        'techbook_books_tag_page',
        'techbook_books_tag_page'
    );

    add_submenu_page(
        'techbook_orders_page',
        'Standards Tag',
        'Standards Tag',
        $capability,
        'techbook_standards_tag_page',
        'techbook_standards_tag_page'
    );

    add_submenu_page(
        'techbook_orders_page',
        'Publishers Tag',
        'Publishers Tag',
        $capability,
        'techbook_publishers_tag_page',
        'techbook_publishers_tag_page'
    );
}
add_action('admin_menu', 'techbook_add_main_menu');
















function get_api_base_url() {
    return 'https://115.84.178.66:8028/api';
}

// Hàm trả về tokenKey
function get_api_token() {
    return '4XwMBElYC3xgZeIW0IZ1H42zyvDNM5h7';
}

