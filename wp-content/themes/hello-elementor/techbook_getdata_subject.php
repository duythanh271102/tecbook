<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'TECBOOK_SUBJECTS_TABLE', $wpdb->prefix . 'tecbook_subjects' );

// Hàm lấy tất cả dữ liệu từ bảng subjects
function get_all_subjects() {
    global $wpdb;
    $table_name = TECBOOK_SUBJECTS_TABLE;
    $results = $wpdb->get_results( "SELECT * FROM $table_name" );
    return $results;
}

// Hàm lấy một subject theo id
function get_subject_by_id( $subject_id ) {
    global $wpdb;
    $table_name = TECBOOK_SUBJECTS_TABLE;
    $subject_id = intval( $subject_id );
    $query = $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $subject_id );
    $subject = $wpdb->get_row( $query );
    return $subject;
}

// Hàm chuẩn bị dữ liệu subject để hiển thị hoặc trả về
function prepare_subject_data( $subject ) {
    if ( $subject ) {
        $data = array(
            'id'        => intval( $subject->id ),
            'code'      => ! empty( $subject->code ) ? $subject->code : '',
            'subjects'  => ! empty( $subject->subjects ) ? $subject->subjects : '',
            'notes'     => ! empty( $subject->notes ) ? $subject->notes : '',
        );
    } else {
        // Giá trị mặc định nếu không tìm thấy subject
        $data = array(
            'id'        => 0,
            'code'      => '',
            'subjects'  => '',
            'notes'     => '',
        );
    }

    return $data;
}
