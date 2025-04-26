<?php

if (!defined('ABSPATH')) {
    exit;
}

class KBPSStaticPhotoManager {
    private static $base_uri = 'assets/images/wedding/';
    private static $base_path;

    public static function getImage($filename) {
        self::$base_path = KBPS_THEME_DIR . self::$base_uri;
        $full_path = self::$base_path . $filename;

        kbps_log($full_path);

        if (file_exists($full_path)) {
            kbps_log('File exists');
            $image_url = KBPS_THEME_URI . self::$base_uri . $filename;
            echo '<img src="' . esc_url($image_url) . '" alt="wedding photo" class="kbps-thumbnail">';
            return;
        }

        echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/placeholders/missing_photo_placeholder.png') . '" alt="not found" class="kbps-thumbnail">';
    }
}
