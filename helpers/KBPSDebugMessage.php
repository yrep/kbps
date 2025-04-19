<?php

if (!defined('ABSPATH')) {
    exit;
}

class KBPSDebugMessage {
    private static $debug_enabled = false;

    public function __construct() {
        if (defined('KBPS_DEBUG') && KBPS_DEBUG) {
            self::$debug_enabled = true;
            add_action('wp_footer', [$this, 'output_debug_message_footer'], 10);
            add_action('wp_head', [$this, 'output_debug_message_head'], 10);
        }
    }

    public static function sm($message, $header = true) {
        if (!self::$debug_enabled) {
            return;
        }

        if (is_array($message) || is_object($message)) {
            ob_start();
            var_dump($message);
            $message = ob_get_clean();
        }

        if ($header) {
            add_action('wp_head', function () use ($message) {
                echo '<div class="debug-message" style="background-color: #ff0; padding: 10px;">' . esc_html($message) . '</div>';
            }, 999);
        } else {
            add_action('wp_footer', function () use ($message) {
                echo '<div class="debug-message" style="background-color: #ff0; padding: 10px;">' . esc_html($message) . '</div>';
            }, 999);
        }
    }

    public function output_debug_message_head() {}

    public function output_debug_message_footer() {}
}

new KBPSDebugMessage();

if (!function_exists('sm')) {
    function sm($message, $header = true) {
        KBPSDebugMessage::sm($message, $header);
    }
}
