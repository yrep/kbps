<?php
get_header();

$current_page = get_queried_object();
if (trim(strip_tags($current_page->post_content)) !== '') {
    echo apply_filters('the_content', $current_page->post_content);
} else {
    get_template_part('template-parts/default-content');
}

get_footer();
?>