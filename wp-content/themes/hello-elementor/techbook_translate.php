<?php

function enqueue_select2_script() {
    wp_enqueue_script('select2', 'path-to-your-select2.js', array('jquery'), null, true);
    wp_localize_script('select2', 'select2Translations', array(
        'all' => __('All', 'hello-elementor') ,
        'select_year' => __('Select Year', 'hello-elementor') 
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_select2_script');

