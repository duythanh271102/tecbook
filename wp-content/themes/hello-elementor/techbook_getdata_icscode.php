<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Định nghĩa tên bảng ICS codes
define( 'TECBOOK_ICS_CODES_TABLE', $wpdb->prefix . 'tecbook_ics_codes' );

// Hàm lấy tất cả dữ liệu từ bảng ics_codes
function get_all_ics_codes() {
    global $wpdb;
    $table_name = TECBOOK_ICS_CODES_TABLE;
    $results = $wpdb->get_results( "SELECT * FROM $table_name" );
    return $results;
}

// Hàm lấy một ICS code theo mã icsCode
function get_ics_code_by_code( $ics_code ) {
    global $wpdb;
    $table_name = TECBOOK_ICS_CODES_TABLE;
    $query = $wpdb->prepare( "SELECT * FROM $table_name WHERE icsCode = %s", $ics_code );
    $result = $wpdb->get_row( $query );
    return $result;
}

// Hàm chuẩn bị dữ liệu ICS code để hiển thị hoặc trả về
function prepare_ics_code_data( $ics_code_data ) {
    if ( $ics_code_data ) {
        $data = array(
            'icsCode'              => ! empty( $ics_code_data->icsCode ) ? $ics_code_data->icsCode : '',
            'nameInEnglish'        => ! empty( $ics_code_data->nameInEnglish ) ? $ics_code_data->nameInEnglish : '',
            'nameInVietnamese'     => ! empty( $ics_code_data->nameInVietnamese ) ? $ics_code_data->nameInVietnamese : '',
            'ralatedToBookSubjects'=> ! empty( $ics_code_data->ralatedToBookSubjects ) ? $ics_code_data->ralatedToBookSubjects : '',
            'keyword'              => ! empty( $ics_code_data->keyword ) ? $ics_code_data->keyword : '',
            'fatherICSCode'        => ! empty( $ics_code_data->fatherICSCode ) ? $ics_code_data->fatherICSCode : '',
        );
    } else {
        // Giá trị mặc định nếu không tìm thấy ICS code
        $data = array(
            'icsCode'              => '',
            'nameInEnglish'        => '',
            'nameInVietnamese'     => '',
            'ralatedToBookSubjects'=> '',
            'keyword'              => '',
            'fatherICSCode'        => '',
        );
    }

    return $data;
}
