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

    /**
     * Универсальный метод логирования
     * 
     * @param mixed $data Данные для логирования (строка, число, массив, объект и т.д.)
     * @param string|null $title Опциональный заголовок записи
     * @param string $type Тип сообщения (info, error, debug и т.д.)
     */
    public function log($data, ?string $title = null, string $type = 'info'): void
    {
        if (!defined('KBPS_DEBUG') || KBPS_DEBUG === false) {
            return;
        }

        if (!file_exists($this->log_dir)) {
            mkdir($this->log_dir, 0755, true);
        }

        $log_path = $this->log_dir . $this->log_file;
        
        // Ротация логов при превышении размера
        if (file_exists($log_path) && filesize($log_path) > $this->max_file_size) {
            $this->rotate_logs();
        }

        // Преобразование данных в строку
        $message = $this->convertToString($data);
        
        // Формирование заголовка
        $header = $title ? "[{$title}] " : '';
        
        // Формирование записи лога
        $log_entry = sprintf(
            "[%s] %s: %s%s\n",
            date('Y-m-d H:i:s'),
            strtoupper($type),
            $header,
            $message
        );

        file_put_contents($log_path, $log_entry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Преобразует любые данные в строку
     */
    private function convertToString($data): string
    {
        switch (true) {
            case is_string($data):
                return $data;
            case is_bool($data):
                return $data ? 'true' : 'false';
            case is_null($data):
                return 'null';
            case is_scalar($data):
                return (string)$data;
            case is_object($data) && method_exists($data, '__toString'):
                return (string)$data;
            case $data instanceof Throwable:
                return sprintf(
                    "Exception: %s\nFile: %s:%d\nStack trace:\n%s",
                    $data->getMessage(),
                    $data->getFile(),
                    $data->getLine(),
                    $data->getTraceAsString()
                );
            default:
                return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }


    private function rotate_logs() {
        $current_log = $this->log_dir . $this->log_file;
        $backup_log = $this->log_dir . 'theme-' . date('Y-m-d-His') . '.log';
        
        if (file_exists($current_log)) {
            rename($current_log, $backup_log);
        }
    }

    public function info($data, $title = '') {
        $this->log($data, $title, 'info');
    }

    public function warning($data, $title = '') {
        $this->log($data, $title, 'warning');
    }

    public function error($data, $title = '') {
        $this->log($data, $title, 'error');
    }

    public function debug($data, $title = '') {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $this->log($data, $title, 'debug');
        }
    }
}

if (!function_exists('kbps_log')) {
    function kbps_log($data, $title = '', $type = 'info') {
        return KBPSLogger::get_instance()->log($data, $title, $type);
    }
}
