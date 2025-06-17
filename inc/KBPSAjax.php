<?php
class KBPSAjax {
    public function __construct() {
        // AJAX для счётчика и удаления
        add_action('wp_ajax_kbps_remove_cart_item', array($this, 'kbps_remove_cart_item_callback'));
        add_action('wp_ajax_nopriv_kbps_remove_cart_item', array($this, 'kbps_remove_cart_item_callback'));

        // Фрагменты для счётчика
        add_filter('woocommerce_add_to_cart_fragments', array($this, 'kbps_cart_count_fragment'));
        add_filter('woocommerce_update_cart_fragments', array($this, 'kbps_cart_count_fragment'));

        // AJAX для блочной корзины
        add_action('wp_footer', array($this, 'kbps_force_cart_ajax'), 100);

        // Подавление REST API /users/me
        //add_filter('rest_pre_dispatch', array($this, 'kbps_disable_user_me_for_guests'), 10, 3);

        add_action('wp_ajax_get_filling_data', array($this, 'kbps_ajax_get_filling_data'));
        add_action('wp_ajax_nopriv_get_filling_data', array($this, 'kbps_ajax_get_filling_data'));

        add_action('wp_ajax_kbps_process_order_form', array($this, 'kbps_process_order_form'));
        add_action('wp_ajax_nopriv_kbps_process_order_form', array($this, 'kbps_process_order_form'));
    }

    // Удаление товара
    public function kbps_remove_cart_item_callback() {
        if (!check_ajax_referer('kbps_cart_nonce', '_wpnonce', false)) {
            wp_send_json_error(array('message' => 'Invalid nonce'), 403);
            wp_die();
        }

        if (!isset($_GET['cart_item_key']) || empty($_GET['cart_item_key'])) {
            wp_send_json_error(array('message' => 'Cart item key missing'), 400);
            wp_die();
        }

        if (!function_exists('WC') || !WC()->cart) {
            wp_send_json_error(array('message' => 'Cart not available'), 500);
            wp_die();
        }

        $cart_item_key = sanitize_text_field($_GET['cart_item_key']);
        $cart = WC()->cart;
        $removed = $cart->remove_cart_item($cart_item_key);

        if ($removed || !$cart->get_cart_item($cart_item_key)) {
            $cart->calculate_totals();
            do_action('woocommerce_cart_updated');
            $fragments = $this->kbps_cart_count_fragment(array());
            wp_send_json_success(array(
                'fragments' => $fragments,
                'cart_item_key' => $cart_item_key,
                'cart_count' => $cart->get_cart_contents_count()
            ));
        } else {
            wp_send_json_error(array('message' => 'Failed to remove item: ' . $cart_item_key));
        }
        wp_die();
    }

    // Фрагмент счётчика
    public function kbps_cart_count_fragment($fragments) {
        ob_start();
        ?>
        <span class="kbps-cart-count"><?php echo function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?></span>
        <?php
        $fragments['.kbps-cart-count'] = ob_get_clean();
        return $fragments;
    }

    // AJAX для блочной корзины
    public function kbps_force_cart_ajax() {
        if (is_cart() || is_checkout()) {
            wc_enqueue_js('
                jQuery(document).on("click", ".wc-block-cart__remove-item, .wc-block-components-product-remove", function(e) {
                    e.preventDefault();
                    var $this = jQuery(this);
                    var $cartItem = $this.closest("[data-cart-item-key]");
                    var cartItemKey = $cartItem.data("cart-item-key") || $this.attr("data-cart-item-key");
                    if (!cartItemKey) {
                        console.error("Cart item key not found", $this);
                        return;
                    }
                    console.log("Remove triggered for key:", cartItemKey);
                    // Скрываем элемент сразу
                    $cartItem.fadeOut(200);
                    jQuery.ajax({
                        url: "' . admin_url('admin-ajax.php') . '",
                        type: "GET",
                        data: {
                            action: "kbps_remove_cart_item",
                            cart_item_key: cartItemKey,
                            _wpnonce: "' . wp_create_nonce('kbps_cart_nonce') . '"
                        },
                        dataType: "json",
                        cache: false,
                        success: function(response) {
                            if (response.success) {
                                console.log("Item removed:", response);
                                // Обновляем счётчик
                                jQuery(".kbps-cart-count").text(response.data.cart_count);
                                // Синхронизируем корзину
                                jQuery(document.body).trigger("wc_fragment_refresh");
                                jQuery(document.body).trigger("wc_cart_updated");
                                jQuery(document.body).trigger("wc-blocks_cart_updated");
                                if (window.wc && window.wc.blocksCart) {
                                    window.wc.blocksCart.dispatch("cart-updated");
                                }
                            } else {
                                console.error("Remove failed:", response.data.message);
                                $cartItem.fadeIn(200);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Remove error:", status, error);
                            $cartItem.fadeIn(200);
                        }
                    });
                });
            ');
        }
    }




    // Подавление REST API /users/me
    /*
    public function kbps_disable_user_me_for_guests($result, $server, $request) {
        if (strpos($request->get_route(), '/wp/v2/users/me') !== false && !is_user_logged_in()) {
            return new WP_Error('rest_not_logged_in', __('Momentálně nejste přihlášeni.', 'woocommerce'), array('status' => 401));
        }
        return $result;
    }
*/



     //Корзина
/*
    function kbps_update_cart_count($fragments) {
        $count = WC()->cart->get_cart_contents_count();
        $fragments['.kbps-cart-count'] = '<span class="kbps-cart-count">' . ($count > 0 ? $count : '') . '</span>';
        return $fragments;
    }
*/
    
    /**
     * AJAX-filling data.
     */
    function kbps_ajax_get_filling_data() {
        if ( empty( $_POST['filling_id'] ) ) {
            wp_send_json_error( 'Filling ID is required.' );
        }

        $filling_id = absint( $_POST['filling_id'] );

        $filling_data = KBPSFillingPostManager::getFillingDataById( $filling_id );

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

        if (!preg_match('/^[0-9]{10,15}$/', $order_data['mobile'])) {
            wp_send_json_error(array(
                'message' => 'Neplatné telefonní číslo. Používejte pouze číslice bez mezer a jakýchkoli znaků.',
                'debug' => 'Invalid phone number'
            ), 400);
        }

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
                'kbps_status'        => 'new',
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