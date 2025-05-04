<?php

if (!defined('ABSPATH')) {
    exit;
}

class KBPSStaticPhotoManager {
    private static $base_uri = 'assets/images/';
    private static $base_path;

    public static function getImage($filename) {

        //kbps_log($filename);
        self::$base_path = KBPS_THEME_DIR . self::$base_uri;
        
        $imageSubDir = strtok($filename, '_');
        
        $full_path = self::$base_path . $imageSubDir . "/" . $filename;
        //kbps_log('File path: ' . $full_path);
        if (file_exists($full_path)) {
            
            $image_url = KBPS_THEME_URI . self::$base_uri . $imageSubDir . "/" . $filename;
            //kbps_log($image_url);
            echo '<img src="' . esc_url($image_url) . '" alt="wedding photo" class="kbps-thumbnail">';
            return;
        }

        echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/placeholders/missing_photo_placeholder.png') . '" alt="not found" class="kbps-thumbnail">';
    }
}
