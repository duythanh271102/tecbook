<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'TECBOOK_STANDARDS_TABLE', $wpdb->prefix . 'tecbook_standards' );

// Hàm lấy tất cả dữ liệu từ bảng standards
function get_all_standards() {
    global $wpdb;
    $table_name = TECBOOK_STANDARDS_TABLE;
    $results = $wpdb->get_results( "SELECT * FROM $table_name" );
    return $results;
}

// Hàm lấy một standard theo id
function get_standard_by_id( $standard_id ) {
    global $wpdb;
    $table_name = TECBOOK_STANDARDS_TABLE;
    $standard_id = intval( $standard_id );
    $query = $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $standard_id );
    $standard = $wpdb->get_row( $query );
    return $standard;
}

// Hàm chuẩn bị dữ liệu standard để hiển thị hoặc trả về
function prepare_standard_data( $standard ) {
    $price_factor = floatval(get_option('techbookapi_price_factor', 1)); 
    if ( $standard ) {
        $data = array(
            'id'                        => intval( $standard->id ),
            'idProduct'                => ! empty( $standard->idProduct ) ? $standard->idProduct : '',
            'referenceNumber'           => ! empty( $standard->referenceNumber ) ? $standard->referenceNumber : '',
            'standardTitle'             => ! empty( $standard->standardTitle ) ? $standard->standardTitle : '',
            'status'                    => ! empty( $standard->status ) ? $standard->status : '',
            'referencedStandards'       => ! empty( $standard->referencedStandards ) ? $standard->referencedStandards : '',
            'referencingStandards'      => ! empty( $standard->referencingStandards ) ? $standard->referencingStandards : '',
            'equivalentStandards'       => ! empty( $standard->equivalentStandards ) ? $standard->equivalentStandards : '',
            'replace'                   => ! empty( $standard->replace ) ? $standard->replace : '',
            'replacedBy'                => ! empty( $standard->replacedBy ) ? $standard->replacedBy : '',
            'standardby'                => ! empty( $standard->standardby ) ? $standard->standardby : '',
            'languages'                 => ! empty( $standard->languages ) ? $standard->languages : '',
            'fullDescription'           => ! empty( $standard->fullDescription ) ? $standard->fullDescription : '',
            'ebookPrice'                => ! empty( $standard->ebookPrice ) ? floatval( $standard->ebookPrice ) * $price_factor : 0,
            'printPrice'                => ! empty( $standard->printPrice ) ? floatval( $standard->printPrice ) * $price_factor : 0,
            'bothPrice'                 => ! empty( $standard->bothPrice ) ? floatval( $standard->bothPrice ) * $price_factor : 0,
            'currency'                  => ! empty( $standard->currency ) ? $standard->currency : '',
            'historicalEditions'        => ! empty( $standard->historicalEditions ) ? $standard->historicalEditions : '',
            'documentHistoryProductId' => ! empty( $standard->documentHistoryProductId ) ? $standard->documentHistoryProductId : '',
            'icsCode'                   => ! empty( $standard->icsCode ) ? $standard->icsCode : '',
            'keyword'                   => ! empty( $standard->keyword ) ? $standard->keyword : '',
            'identicalStandards'        => ! empty( $standard->identicalStandards ) ? $standard->identicalStandards : '',
            'publishedDate'             => ! empty( $standard->publishedDate ) ?  $standard->publishedDate : '',
            'pages'                     => ! empty( $standard->pages ) ? intval( $standard->pages ) : 0,
            'byTechnology'              => ! empty( $standard->byTechnology ) ? $standard->byTechnology : '',
            'byIndustry'                => ! empty( $standard->byIndustry ) ? $standard->byIndustry : '',
            'previewPath'               => ! empty( $standard->previewPath ) ? $standard->previewPath : '',
            'coverPath'                 => ! empty( $standard->coverPath ) ? $standard->coverPath : '',
            'fullPath'                  => ! empty( $standard->fullPath ) ? $standard->fullPath : '',
        );
    } else {
        // Giá trị mặc định khi không tìm thấy standard
        $data = array(
            'id'                        => 0,
            'idStandard'                => '',
            'referenceNumber'           => '',
            'standardTitle'             => '',
            'status'                    => '',
            'referencedStandards'       => '',
            'referencingStandards'      => '',
            'equivalentStandards'       => '',
            'replaceStandard'           => '',
            'replacedByStandard'        => '',
            'standardby'                => '',
            'languages'                 => '',
            'fullDescription'           => '',
            'ebookPrice'                => 0,
            'printPrice'                => 0,
            'bothPrice'                 => 0,
            'currency'                  => '',
            'historicalEditions'        => '',
            'documentHistoryProductId' => '',
            'icsCode'                   => '',
            'keyword'                   => '',
            'identicalStandards'        => '',
            'publishedDate'             => '',
            'pages'                     => 0,
            'byTechnology'              => '',
            'byIndustry'                => '',
            'previewPath'               => '',
            'coverPath'                 => '',
            'fullPath'                  => '',
        );
    }

    return $data;
}

// Hàm xử lý AJAX
function save_standards_to_cache() {
    // Kiểm tra và lấy dữ liệu gửi lên
    if ( isset($_POST['standards']) ) {
        $standards = $_POST['standards'];

        // Gọi hàm lưu dữ liệu vào database
        hte_save_standards_to_cache($standards);

        wp_send_json_success('Dữ liệu đã được lưu thành công.');
    } else {
        wp_send_json_error('Không có dữ liệu để lưu.');
    }

    wp_die();
}
add_action('wp_ajax_save_standards_to_cache', 'save_standards_to_cache');
add_action('wp_ajax_nopriv_save_standards_to_cache', 'save_standards_to_cache');





