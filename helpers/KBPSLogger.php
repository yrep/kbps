<?php
if (!defined('ABSPATH')) {
    exit; // Защита от прямого вызова
}

class KBPSLogger {
    private static $instance = null;
    private $log_dir;
    private $log_file = 'theme.log';
    private $max_file_size = 5 * 1024 * 1024; // 5MB

    private function __construct() {
        $this->log_dir = KBPS_THEME_DIR . 'logs/';
        $this->ensure_log_directory();
    }

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function ensure_log_directory() {
        if (!file_exists($this->log_dir)) {
            wp_mkdir_p($this->log_dir);
        }
    }

    public function log($message, $context = [], $type = 'info') {
        if (!defined('KBPS_DEBUG') || KBPS_DEBUG === false) {
            return;
        }
        
        $log_path = $this->log_dir . $this->log_file;
        
        if (file_exists($log_path) && filesize($log_path) > $this->max_file_size) {
            $this->rotate_logs();
        }

        $log_entry = sprintf(
            "[%s] %s: %s %s\n",
            date('Y-m-d H:i:s'),
            strtoupper($type),
            $message,
            !empty($context) ? json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : ''
        );

        file_put_contents($log_path, $log_entry, FILE_APPEND);
    }


    private function rotate_logs() {
        $current_log = $this->log_dir . $this->log_file;
        $backup_log = $this->log_dir . 'theme-' . date('Y-m-d-His') . '.log';
        
        if (file_exists($current_log)) {
            rename($current_log, $backup_log);
        }
    }

    public function info($message, $context = []) {
        $this->log($message, $context, 'info');
    }

    public function warning($message, $context = []) {
        $this->log($message, $context, 'warning');
    }

    public function error($message, $context = []) {
        $this->log($message, $context, 'error');
    }

    public function debug($message, $context = []) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $this->log($message, $context, 'debug');
        }
    }
}

if (!function_exists('kbps_log')) {
    function kbps_log($message, $context = [], $type = 'info') {
        return KBPSLogger::get_instance()->log($message, $context, $type);
    }
}
