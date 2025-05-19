<?php
/**
 * Functions and definitions
 */

if (!defined('ABSPATH')) {
    exit;
}

// 1. Определяем константы
define('KBPS_THEME_DIR', trailingslashit(get_template_directory()));
define('KBPS_THEME_URI', trailingslashit(esc_url(get_template_directory_uri())));
define('KBPS_DEBUG', true);

// 2. Подключаем файлы с проверкой их существования
$required_files = [
    'helpers/KBPSLogger.php',
    'helpers/KBPSDebugMessage.php',
    'inc/KBPSThemeSetup.php',
    'inc/KBPSAdmin.php',
    'inc/KBPSThemeAssets.php',
    'inc/KBPSPageManager.php',
    'inc/KBPSThemeCore.php',
    'inc/KBPSAjax.php',
    'inc/KBPSCakeRequestPostType.php',
    'inc/KBPSCakePostType.php',
    'inc/KBPSFillingPostType.php',
    'inc/KBPSCakePostManager.php',
    'inc/KBPSStaticPhotoManager.php',
    'inc/KBPSFillingPostManager.php',
];



foreach ($required_files as $file) {
    $file_path = KBPS_THEME_DIR . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    } else {
        wp_die(sprintf('Error: file not found %s', $file));
    }
}

global $kbpsCore, $kbpsSetup, $kbpsAssets, $kbpsAjax;
$kbpsCore = new KBPSThemeCore();
$kbpsSetup = new KBPSThemeSetup();
$KBPSAdmin = new KBPSAdmin();
$kbpsAssets = new KBPSThemeAssets();
$kbpsAjax = new KBPSAjax();
$kbpsCakeRequest = new KBPSCakeRequestPostType();
$kbpsCake = new KBPSCakePostType();
$kbpsFilling = new KBPSFillingPostType();
KBPSCakePostManager::init();



//sm('Test debug message'));
//KBPSLogger::get_instance()->info('Test Logger');

register_activation_hook(__FILE__, function() {
    if (class_exists('KBPSLogger')) {
        KBPSLogger::get_instance()->info('Theme Activated');
    }
});

flush_rewrite_rules();


add_filter('single_template', function($template) {
    global $post;
    
    if ($post->post_type === 'cake') {
        $new_template = locate_template(array('single-cake.php'));
        if (!empty($new_template)) {
            return $new_template;
        }
    }
    
    return $template;
});


//sm('Test');

/* Log template
add_filter('template_include', function($template) {

    kbps_log('Template loaded', [
        'template' => $template
    ]);

    return $template;
}, 1000);
*/

// Cake image size in slider
add_filter('kbps_cake_image_size', function($size, $post_id) {
    return is_front_page() ? 'medium' : 'large';
}, 10, 2);


// global $_wp_additional_image_sizes;
// echo '<pre>';
// print_r($_wp_additional_image_sizes);
// echo '</pre>';

