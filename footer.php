<?php
/**
 * The footer template.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
</div>  <!-- kbps-container div -->
</div> <!-- content div -->
</div> <!-- main div -->
<?php wp_footer(); ?>

<footer class="main-footer">
    <div class="footer-container">
        <!-- Левая секция - форма -->
        <div class="footer-section form-section">
            <div class="footer-order-form-container">
                <h3>Zadejte objednávku</h3>
                <form id="footer-order-form" method="POST">
                    <div class="form-row footer">
                        <label for="cake-model">Model dortu №</label>
                        <input type="text" id="cake-model" name="cake-model" required>
                    </div>
                    <div class="form-row footer">
                        <label for="name">Vaše jméno</label>
                        <input type="text" id="name" name="name" required minlength="2">
                    </div>
                    <div class="form-row footer">
                        <label for="mobile">Váš mobil</label>
                        <input type="tel" id="mobile" name="mobile" required pattern="[0-9]{10,15}">
                    </div>
                    <div class="form-row footer">
                        <label for="email">Váš email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-row footer">
                        <label for="wedding-date">Datum svatby</label>
                        <input type="date" id="wedding-date" name="wedding-date" required>
                    </div>
                    <div class="form-row footer">
                        <label for="venue">Místo svatby</label>
                        <input type="text" id="venue" name="venue" required>
                    </div>
                    <div class="form-row footer">
                        <label for="guests">Počet hostů</label>
                        <input type="number" id="guests" name="guests" required min="1">
                    </div>
                    <button type="submit" class="submit-btn">Poslat</button>
                    <input type="hidden" name="footer_form_submitted" value="1">
                </form>
                <div id="form-message"></div>
            </div>
        </div>

        <!-- Правая секция - кнопка и лого -->
        <div class="footer-section actions-section">
            <div class="footer-actions">
                <button class="button-arrow back-to-top" aria-label="Back to top">
                    <i class="fas fa-arrow-up"></i>
                </button>
                <div class="footer-logo">
                    <?php if (has_custom_logo()): ?>
                        <?php the_custom_logo(); ?>
                    <?php else: ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="Logo">
                    <?php endif; ?>
                </div>
                
                <!-- Меню с политиками -->
                <?php
                $is_front_page = is_front_page();
                $is_svatebni_dort_archive = is_tax('cake-type', 'svatebni-dort');
                $is_svatebni_model = false;

                if (is_singular('cake')) {
                    $terms = wp_get_post_terms(get_the_ID(), 'cake-type');
                    foreach ($terms as $term) {
                        if ($term->slug === 'svatebni-dort') {
                            $is_svatebni_model = true;
                            break;
                        }
                    }
                }

                if (!$is_front_page && !$is_svatebni_dort_archive && !$is_svatebni_model): ?>
                    <nav class="footer-info-menu under-logo">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer-info',
                            'container' => false,
                            'menu_class' => 'footer-links',
                        ]);
                        ?>
                    </nav>

                    
                <?php endif; ?>
                <!-- Меню с политиками - конец -->


            </div>
        </div>

    </div>
    <div class="payment-gateway-logos">
        <div class="comgate">
            <span>
                Platební brána:
            </span>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/comgate/comgate.png" alt="Logo 1">
        </div>
        <div class="footer-comgate-logo-line">
            
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/comgate/mastercard_e_logo.png" alt="Logo 2">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/comgate/mastercard_logo.png" alt="Logo 3">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/comgate/mastercard_maestro_logo-01.png" alt="Logo 4">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/comgate/mastercard_sc_logo.png" alt="Logo 5">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/comgate/visa_electron_logo-01.png" alt="Logo 6">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/comgate/visa_logo.png" alt="Logo 7">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/comgate/visa_ver_logo.png" alt="Logo 8">
        </div>
    </div>
    <div class="footer-copyright-container">
        <div class="footer-copyright">
            <?php echo '© ' . date('Y') . ' Krakhmalnikov Brothers.'; ?>
        </div>
    </div>
</footer>

</body>
</html>