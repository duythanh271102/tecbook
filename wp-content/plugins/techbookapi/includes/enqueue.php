<?php
function techbookapi_enqueue_admin_scripts() {
    wp_enqueue_style('techbookapi-admin-style', plugins_url('assets/admin-style.css', __FILE__));
    wp_enqueue_script('techbookapi-admin-script', plugins_url('assets/admin-script.js', __FILE__), array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'techbookapi_enqueue_admin_scripts');
