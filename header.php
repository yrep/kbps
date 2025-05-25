<?php
/**
 * The header template.
 */

if (!defined('ABSPATH')) {
    exit;
}

global $kbpsCore;

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div <?php $kbpsCore->getClassAttr('main_wrapper'); ?>>
    <header class="kbps-header-desktop">
        <div class="kbps-header-container">
            <!-- LOGO -->
            <div class="kbps-logo">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <h1><?php bloginfo('name'); ?></h1>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Блок справа -->
            <div class="kbps-header-right">
                <!-- Соцсети -->
                <div class="kbps-header-icon-container">
                    <div class="kbps-social-icons">
                        <a href="https://www.youtube.com/@KrakhmalnikovBrothers/" target="_blank"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.instagram.com/krakhmalnikov_brothers/" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="tel:+420733392158" target="_blank"><i class="fas fa-phone fa-sm"></i></a>
                    </div>
                </div>

                <!-- Меню с заголовком "MENU" (Desktop)-->
                <nav class="kbps-menu">
                    <h3 class="kbps-menu-title">MENU</h3>
                    <div class="kbps-menu-content">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'menu_class'     => 'kbps-menu-vertical',
                            'container'      => false,
                            'fallback_cb'    => false,
                        ]);
                        ?>
                    </div>
                </nav>

                <!-- Корзина WooCommerce -->
                <div class="kbps-header-icon-container">
                    <div class="kbps-cart">
                        <a href="<?php echo wc_get_cart_url(); ?>">
                            <i class="fas fa-shopping-cart fa-nm"></i>
                            <span class="kbps-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    

<!-- Мобильная версия хедера -->
<header class="kbps-header-mobile">
        <div class="kbps-header-container">
            <!-- Соцсети -->
            <div class="kbps-social-icons">
                <a href="https://www.youtube.com/@KrakhmalnikovBrothers/" target="_blank"><i class="fab fa-youtube"></i></a>
                <a href="https://www.instagram.com/krakhmalnikov_brothers/" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="tel:+420733392158" target="_blank"><i class="fas fa-phone"></i></a>
            </div>

            <!-- Лого -->
            <div class="kbps-logo">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <h1><?php bloginfo('name'); ?></h1>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Меню -->
            <nav class="kbps-menu">
                <h3 class="kbps-menu-title">MENU</h3>
                <div class="kbps-menu-content">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_class'     => 'kbps-menu-vertical',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ]);
                    ?>
                </div>
            </nav>

            <!-- Корзина -->
            <div class="kbps-header-icon-container">
                <div class="kbps-cart">
                    <a href="<?php echo wc_get_cart_url(); ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if (WC()->cart->get_cart_contents_count() > 0) : ?>
                            <span class="kbps-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </header>


    <div id="content" class="site-content"> <!-- content div -->
        <div class="kbps-container"> <!-- kbps-container div -->