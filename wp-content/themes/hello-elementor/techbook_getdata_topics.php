<?php

add_action('wp_ajax_fetch_topic_data', 'fetch_topic_data');
add_action('wp_ajax_nopriv_fetch_topic_data', 'fetch_topic_data');

function fetch_topic_data()
{
    global $wpdb;

    $letter = isset($_POST['letter']) ? sanitize_text_field($_POST['letter']) : '';
    $results = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM wp_tecbook_topics WHERE title LIKE %s", $letter . '%')
    );
    if (!empty($results)) {
        $response = [];
        foreach ($results as $result) {
            $response[] = [
                'title' => $result->title,
                'code' => $result->code,
            ];
        }
        wp_send_json_success($response);
    } else {

        wp_send_json_error('No data found');
    }
    wp_die();
}
