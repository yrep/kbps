<?php

if (!defined('ABSPATH')) {
    exit;
}

class StaticPhotoManager {
    private static $base_uri = 'assets/images/wedding/';
    private static $base_path;

    private static $extensions = ['jpeg', 'jpg', 'webp', 'png'];

    public static function getImage($filename) {
        self::$base_path = KBPS_THEME_URI . self::$base_uri;

        foreach (self::$extensions as $ext) {
            $full_path = self::$base_path . $filename . '.' . $ext;

            if (file_exists($full_path)) {
                $image_url = KBPS_THEME_URI . self::$base_uri . $filename . '.' . $ext;
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($filename) . '" class="kbps-thumbnail">';
                return;
            }
        }

        echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/placeholders/missing_photo_placeholder.png') . '" alt="not found" class="kbps-thumbnail">';
    }
}
