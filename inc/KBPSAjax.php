<?php
class KBPSAjax {
    public function __construct() {
        add_action('wp_ajax_kbps_process_order_form', array($this, 'process_order_form'));
        add_action('wp_ajax_nopriv_kbps_process_order_form', array($this, 'process_order_form'));
    }

    public function process_order_form() {
        // Проверка nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'kbps_order_nonce')) {
            wp_send_json_error(array(
                'message' => 'Chyba ověření bezpečnosti.',
                'debug' => 'Invalid nonce'
            ), 400);
        }

        // Проверка наличия всех обязательных полей
        $required_fields = array(
            'cake-model' => 'Model dortu',
            'name' => 'Jméno',
            'mobile' => 'Telefonní číslo',
            'email' => 'Email',
            'wedding-date' => 'Datum svatby',
            'venue' => 'Místo svatby',
            'guests' => 'Počet hostů'
        );

        foreach ($required_fields as $field => $label) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                wp_send_json_error(array(
                    'message' => sprintf('Pole "%s" je povinné.', $label),
                    'debug' => 'Missing field: ' . $field
                ), 400);
            }
        }

        // Санитизация данных
        $order_data = array(
            'cake_model' => sanitize_text_field($_POST['cake-model']),
            'name' => sanitize_text_field($_POST['name']),
            'mobile' => sanitize_text_field($_POST['mobile']),
            'email' => sanitize_email($_POST['email']),
            'wedding_date' => sanitize_text_field($_POST['wedding-date']),
            'venue' => sanitize_text_field($_POST['venue']),
            'guests' => absint($_POST['guests'])
        );

        // Валидация email
        if (!is_email($order_data['email'])) {
            wp_send_json_error(array(
                'message' => 'Neplatná emailová adresa.',
                'debug' => 'Invalid email'
            ), 400);
        }

        // Валидация номера телефона
        if (!preg_match('/^[0-9]{10,15}$/', $order_data['mobile'])) {
            wp_send_json_error(array(
                'message' => 'Neplatné telefonní číslo.',
                'debug' => 'Invalid phone number'
            ), 400);
        }

        // Сохранение заказа в кастомный тип поста
        $post_data = array(
            'post_title'   => 'Objednávka od ' . $order_data['name'] . ' (' . date('Y-m-d H:i:s') . ')',
            'post_type'    => 'cake_request',
            'post_status'  => 'publish',
            'meta_input'   => array(
                'kbps_cake_model'    => $order_data['cake_model'],
                'kbps_name'          => $order_data['name'],
                'kbps_mobile'        => $order_data['mobile'],
                'kbps_email'         => $order_data['email'],
                'kbps_wedding_date'  => $order_data['wedding_date'],
                'kbps_venue'         => $order_data['venue'],
                'kbps_guests'        => $order_data['guests'],
                'kbps_status'        => 'new', // Установка статуса по умолчанию
                'kbps_email_sent'    => false,
                'kbps_error'         => ''
            )
        );

        $post_id = wp_insert_post($post_data, true);

        if (is_wp_error($post_id)) {
            wp_send_json_error(array(
                'message' => 'Chyba při ukládání objednávky.',
                'debug' => $post_id->get_error_message()
            ), 500);
        }

        // Отправка email администратору
        $htmlFormat = true; // Переключатель формата письма (true - HTML, false - текст)
        $to = get_option('admin_email');
        $subject = 'Nová objednávka dortu';

        if ($htmlFormat) {
            $message = '<h2>Nová objednávka dortu</h2>';
            $message .= '<table border="1" cellpadding="5" style="border-collapse: collapse;">';
            foreach ($order_data as $key => $value) {
                $label = ucfirst(str_replace('_', ' ', $key));
                $message .= "<tr><td><strong>$label</strong></td><td>$value</td></tr>";
            }
            $message .= '</table>';
            $headers = array('Content-Type: text/html; charset=UTF-8');
        } else {
            $message = "Nová objednávka:\n\n";
            foreach ($order_data as $key => $value) {
                $message .= ucfirst(str_replace('_', ' ', $key)) . ": $value\n";
            }
            $headers = array('Content-Type: text/plain; charset=UTF-8');
        }

        // Попытка отправки письма
        $email_sent = wp_mail($to, $subject, $message, $headers);

        // Обновление метаполей в зависимости от результата отправки
        if ($email_sent) {
            update_post_meta($post_id, 'kbps_email_sent', true);
            update_post_meta($post_id, 'kbps_error', '');
        } else {
            $error_message = 'Chyba při odesílání emailu.';
            update_post_meta($post_id, 'kbps_email_sent', false);
            update_post_meta($post_id, 'kbps_error', $error_message);
            wp_send_json_error(array(
                'message' => $error_message,
                'debug' => 'Email sending failed'
            ), 500);
        }

        wp_send_json_success(array(
            'message' => 'Objednávka byla úspěšně odeslána!'
        ));

        wp_die();
    }
}