<?php
function hte_add_query_vars($vars) {
    $vars[] = 'book_id'; 
    $vars[] = 'standard_id';
    $vars[] = 'publisher_id';
    $vars[] = 'subject_id';
    return $vars;
}
add_filter('query_vars', 'hte_add_query_vars');

function hte_add_rewrite_rules() {
    add_rewrite_rule(
        '^detail/book-([0-9]+)/?', 
        'index.php?book_id=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^detail/standard-([0-9]+)/?', 
        'index.php?standard_id=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^detail/publisher-([0-9]+)/?', 
        'index.php?publisher_id=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^detail/subject-([0-9]+)/?', 
        'index.php?subject_id=$matches[1]',
        'top'
    );
}
add_action('init', 'hte_add_rewrite_rules');

function hte_load_template_for_books($template) {
    $book_id = get_query_var('book_id');
    $standard_id = get_query_var('standard_id');
    $publisher_id = get_query_var('publisher_id');
    $subject_id = get_query_var('subject_id');

    if (!empty($book_id)) {
        $new_template = locate_template('template-parts/book-detail.php');
        if (!empty($new_template)) {
            return $new_template; 
        }
    }

    if (!empty($standard_id)) {
        $new_template = locate_template('template-parts/standard-detail.php');
        if (!empty($new_template)) {
            return $new_template; 
        }
    }

    if (!empty($publisher_id)) {
        $new_template = locate_template('template-parts/publisher-detail.php');
        if (!empty($new_template)) {
            return $new_template; 
        }
    }

    if (!empty($subject_id)) {
        $new_template = locate_template('template-parts/subject-detail.php');
        if (!empty($new_template)) {
            return $new_template; 
        }
    }

    return $template;
}

add_filter('template_include', 'hte_load_template_for_books');

function hte_flush_rewrite_rules() {
    flush_rewrite_rules();
}
add_action('init', 'hte_flush_rewrite_rules');


add_action('pre_get_posts', function($query) {
    if (!is_admin() && $query->is_main_query()) {
        $book_id = get_query_var('book_id');
        $standard_id = get_query_var('standard_id');
        $publisher_id = get_query_var('publisher_id');
        $subject_id = get_query_var('subject_id');

        if (!empty($book_id) || !empty($standard_id) || !empty($publisher_id) || !empty($subject_id)) {
            $query->set('post_type', 'nonexistent_post_type'); 
            $query->set('posts_per_page', 0);
            $query->set('no_found_rows', true); 
            $query->set('ignore_sticky_posts', true); 
            $query->set('update_post_meta_cache', false); 
            $query->set('update_post_term_cache', false); 
        }
    }
});


function custom_login_footer_message() {
    echo '<div style="text-align:center; font-size:14px; color:#555; margin:20px auto; width:350px;">
            Quý khách hàng cần hỗ trợ vui lòng truy cập website <a href="https://htecom.vn" target="_blank">HTECOM.VN</a> 
            hoặc email <a href="mailto:support@htecom.vn">support@htecom.vn</a> hoặc gửi support ticket tại tài khoản CRM được cấp.
          </div>';
}
add_action('login_footer', 'custom_login_footer_message');
