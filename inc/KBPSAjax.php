<?php

if (!class_exists('KBPSAjax')) {
    class KBPSAjax {
        public function __construct() {
            add_action('wp_ajax_kbps_remove_cart_item', array($this, 'kbps_remove_cart_item_callback'));
            add_action('wp_ajax_nopriv_kbps_remove_cart_item', array($this, 'kbps_remove_cart_item_callback'));

            add_action('wp_ajax_kbps_get_current_cart_count', array($this, 'kbps_get_current_cart_count_callback'));
            add_action('wp_ajax_nopriv_kbps_get_current_cart_count', array($this, 'kbps_get_current_cart_count_callback'));

            add_action('wp_ajax_get_filling_data', array($this, 'kbps_ajax_get_filling_data'));
            add_action('wp_ajax_nopriv_get_filling_data', array($this, 'kbps_ajax_get_filling_data'));

            add_action('wp_ajax_kbps_process_order_form', array($this, 'kbps_process_order_form'));
            add_action('wp_ajax_nopriv_kbps_process_order_form', array($this, 'kbps_process_order_form'));

            add_action('wp_ajax_kbps_update_cart_item_quantity', [$this, 'kbps_update_cart_item_quantity_callback']);
            add_action('wp_ajax_nopriv_kbps_update_cart_item_quantity', [$this, 'kbps_update_cart_item_quantity_callback']);

        }


        public function kbps_update_cart_item_quantity_callback() {
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'kbps_cart_nonce')) {
            wp_send_json_error(['message' => 'Invalid nonce.'], 403);
            wp_die();
        }

        if (empty($_POST['cart_item_key']) || !isset($_POST['qty'])) {
            wp_send_json_error(['message' => 'Missing cart item key or quantity.'], 400);
            wp_die();
        }

        $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
        $new_quantity = intval($_POST['qty']);

        $cart = WC()->cart;

        if (!$cart) {
            wp_send_json_error(['message' => 'Cart not available.'], 500);
            wp_die();
        }

        $updated = $cart->set_quantity($cart_item_key, $new_quantity, true);

        $cart->calculate_totals();
        $cart->maybe_set_cart_cookies();
        do_action('woocommerce_cart_updated');

        if ($updated !== false) {
            $fragments = WC_AJAX::get_refreshed_fragments();
            wp_send_json($fragments);
        } else {
            wp_send_json_error(['message' => 'Failed to update item quantity.'], 500);
        }

        wp_die();
    }

        public function kbps_get_current_cart_count_callback() {

            if (null === WC()->cart) {
                WC()->session->set_customer_session_cookie(true);
                WC()->cart->calculate_totals();
            } else {
                WC()->cart->calculate_totals();
            }

            $current_cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;

            wp_send_json_success([
                'cart_count' => $current_cart_count
            ]);
            wp_die();
        }


        public function kbps_remove_cart_item_callback() {
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'kbps_cart_nonce')) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
            wp_die();
        }

        if (empty($_GET['cart_item_key'])) {
            wp_send_json_error(['message' => 'Cart item key missing'], 400);
            wp_die();
        }

        $cart_item_key = sanitize_text_field($_GET['cart_item_key']);
        $cart = WC()->cart;

        if (!$cart) {
            wp_send_json_error(['message' => 'Cart not available'], 500);
            wp_die();
        }

        $removed = $cart->remove_cart_item($cart_item_key);

        $cart->calculate_totals();
        $cart->maybe_set_cart_cookies();
        $cart->calculate_totals();

        if ($removed || !$cart->get_cart_item($cart_item_key)) {
            do_action('woocommerce_cart_updated');

            $fragments = WC_AJAX::get_refreshed_fragments();
            wp_send_json($fragments);
        } else {
            wp_send_json_error(['message' => 'Failed to remove item: ' . $cart_item_key]);
        }
        wp_die();
    }



        function kbps_ajax_get_filling_data() {
            if ( empty( $_POST['filling_id'] ) ) {
                wp_send_json_error( 'Filling ID is required.' );
            }

            $filling_id = absint( $_POST['filling_id'] );

            $filling_data = class_exists('KBPSFillingPostManager') ? KBPSFillingPostManager::getFillingDataById( $filling_id ) : null;

            if ( $filling_data ) {
                wp_send_json_success( $filling_data );
            } else {
                wp_send_json_error( 'Filling is not found.' );
            }
            wp_die();
        }


        public function kbps_process_order_form() {
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'kbps_order_nonce')) {
                wp_send_json_error(array(
                    'message' => 'Chyba ověření bezpečnosti.',
                    'debug' => 'Invalid nonce'
                ), 400);
            }

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

            $order_data = array(
                'cake_model' => sanitize_text_field($_POST['cake-model']),
                'name' => sanitize_text_field($_POST['name']),
                'mobile' => sanitize_text_field($_POST['mobile']),
                'email' => sanitize_email($_POST['email']),
                'wedding_date' => sanitize_text_field($_POST['wedding-date']),
                'venue' => sanitize_text_field($_POST['venue']),
                'guests' => absint($_POST['guests'])
            );

            if (!is_email($order_data['email'])) {
                wp_send_json_error(array(
                    'message' => 'Neplatná emailová adresa.',
                    'debug' => 'Invalid email'
                ), 400);
            }

            $clean_mobile = preg_replace('/[^0-9+]/', '', $order_data['mobile']); // Удаляем все, кроме цифр и +
            if (!preg_match('/^[0-9+]{7,15}$/', $clean_mobile)) { // От 7 до 15 цифр/плюсов
                wp_send_json_error(array(
                    'message' => 'Neplatné telefonní číslo. Použijte platný formát.',
                    'debug' => 'Invalid phone number format'
                ), 400);
            }
            $order_data['mobile'] = $clean_mobile;


            $post_data = array(
                'post_title'   => 'Objednávka od ' . $order_data['name'] . ' (' . date('Y-m-d H:i:s') . ')',
                'post_type'    => 'cake_request',
                'post_status'  => 'publish',
                'meta_input'   => array(
                    'kbps_cake_model'      => $order_data['cake_model'],
                    'kbps_name'            => $order_data['name'],
                    'kbps_mobile'          => $order_data['mobile'],
                    'kbps_email'           => $order_data['email'],
                    'kbps_wedding_date'    => $order_data['wedding_date'],
                    'kbps_venue'           => $order_data['venue'],
                    'kbps_guests'          => $order_data['guests'],
                    'kbps_status'          => 'new',
                    'kbps_email_sent'      => false,
                    'kbps_error'           => ''
                )
            );

            $post_id = wp_insert_post($post_data, true);

            if (is_wp_error($post_id)) {
                wp_send_json_error(array(
                    'message' => 'Chyba při ukládání objednávky.',
                    'debug' => $post_id->get_error_message()
                ), 500);
            }

            $htmlFormat = true;
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

            $email_sent = wp_mail($to, $subject, $message, $headers);

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

}
