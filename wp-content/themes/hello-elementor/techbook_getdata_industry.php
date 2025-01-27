<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Định nghĩa tên bảng ICS codes
define( 'TECBOOK_INDUSTRY_TABLE', $wpdb->prefix . 'tecbook_industry' );

function get_all_technology() {
    global $wpdb;
    $table_name = TECBOOK_INDUSTRY_TABLE;

    $results = $wpdb->get_results( 
        "SELECT SubjectCode, englishTitle 
        FROM $table_name 
        WHERE SubjectCode BETWEEN '01' AND '01.10'"
    );
    if ($wpdb->last_error) {
        error_log("Database query error: " . $wpdb->last_error);
    }

    return $results;
}


function get_all_industry() {
    global $wpdb;
    $table_name = TECBOOK_INDUSTRY_TABLE;

    $results = $wpdb->get_results( 
        "SELECT SubjectCode, englishTitle 
        FROM $table_name 
        WHERE SubjectCode BETWEEN '02' AND '02.10'"
    );
    if ($wpdb->last_error) {
        error_log("Database query error: " . $wpdb->last_error);
    }

    return $results;
}


