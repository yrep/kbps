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
                    <div class="form-row">
                        <label for="cake-model">Model dortu №</label>
                        <input type="text" id="cake-model" name="cake-model" required>
                    </div>
                    <div class="form-row">
                        <label for="name">Vaše jméno</label>
                        <input type="text" id="name" name="name" required minlength="2">
                    </div>
                    <div class="form-row">
                        <label for="mobile">Váš mobil</label>
                        <input type="tel" id="mobile" name="mobile" required pattern="[0-9]{10,15}">
                    </div>
                    <div class="form-row">
                        <label for="email">Váš email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-row">
                        <label for="wedding-date">Datum svatby</label>
                        <input type="date" id="wedding-date" name="wedding-date" required>
                    </div>
                    <div class="form-row">
                        <label for="venue">Místo svatby</label>
                        <input type="text" id="venue" name="venue" required>
                    </div>
                    <div class="form-row">
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
                <button class="back-to-top" aria-label="Back to top">
                    <i class="fas fa-arrow-up"></i>
                </button>
                <div class="footer-logo">
                    <?php if (has_custom_logo()): ?>
                        <?php the_custom_logo(); ?>
                    <?php else: ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="Logo">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
</footer>

</body>
</html>