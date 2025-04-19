<?php
// вывод кастомной ширины лого

add_action('wp_head', function() {
    $logo_width = get_theme_mod('kbps_logo_width', 300);
    ?>
    <style type="text/css">
        .custom-logo {
            max-width: <?php echo esc_attr($logo_width); ?>px;
            height: auto;
        }
        .custom-logo-link {
            display: inline-block;
        }
    </style>
    <?php
});