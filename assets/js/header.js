document.addEventListener('DOMContentLoaded', function() {
    // Desktop elements
    const desktopHeader = document.querySelector('.kbps-header-desktop');
    const desktopLogo = document.querySelector('.kbps-logo');
    const desktopMenuTitle = document.querySelector('.kbps-menu-title');
    const desktopMenuElement = document.querySelector('.kbps-menu');

    // Mobile elements
    const mobileHeader = document.querySelector('.kbps-header-mobile');
    const mobileMenuTitle = document.querySelector('.kbps-header-mobile .kbps-menu-title');
    const mobileMenuElement = document.querySelector('.kbps-header-mobile .kbps-menu');

    // Update header height for proper body padding
    function updateHeaderHeight() {
        const activeHeader = window.innerWidth > 768 ? desktopHeader : mobileHeader;
        if (activeHeader) {
            const headerHeight = activeHeader.offsetHeight;
            document.documentElement.style.setProperty('--header-height', `${headerHeight}px`);
        }
    }

    const scrollThreshold = 50;

    // Handle scroll events
    window.addEventListener('scroll', function() {
        if (!desktopHeader) return;
        
        if (window.scrollY > scrollThreshold) {
            if (!desktopHeader.classList.contains('scrolled')) {
                desktopHeader.classList.add('scrolled');
                if (desktopMenuTitle) desktopMenuTitle.classList.add('scrolled');
                if (desktopLogo) desktopLogo.classList.add('small');
                // Close menu if open when starting to scroll
                if (desktopMenuElement && desktopMenuElement.classList.contains('open')) {
                    desktopMenuElement.classList.remove('open');
                }
            }
        } else {
            if (desktopHeader.classList.contains('scrolled')) {
                desktopHeader.classList.remove('scrolled');
                if (desktopMenuTitle) desktopMenuTitle.classList.remove('scrolled');
                if (desktopLogo) desktopLogo.classList.remove('small');
                // Close menu if open when returning to top
                if (desktopMenuElement && desktopMenuElement.classList.contains('open')) {
                    desktopMenuElement.classList.remove('open');
                }
            }
        }
    });

    // Desktop menu toggle
    if (desktopMenuTitle && desktopMenuElement) {
        desktopMenuTitle.addEventListener('click', function() {
            if (desktopHeader.classList.contains('scrolled')) {
                desktopMenuElement.classList.toggle('open');
            }
        });
    }

    // Mobile menu toggle
    if (mobileMenuTitle && mobileMenuElement) {
        mobileMenuTitle.addEventListener('click', function() {
            mobileMenuElement.classList.toggle('open');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileMenuElement.contains(event.target) && 
                !mobileMenuTitle.contains(event.target) && 
                mobileMenuElement.classList.contains('open')) {
                mobileMenuElement.classList.remove('open');
            }
        });
    }

    // Handle resize events
    function handleResize() {
        updateHeaderHeight();
        
        // Close menus when switching between mobile/desktop
        if (window.innerWidth > 768 && mobileMenuElement && mobileMenuElement.classList.contains('open')) {
            mobileMenuElement.classList.remove('open');
        } else if (window.innerWidth <= 768 && desktopMenuElement && desktopMenuElement.classList.contains('open')) {
            desktopMenuElement.classList.remove('open');
        }
    }

    // Initial setup
    updateHeaderHeight();
    window.addEventListener('resize', handleResize);
});













/*
jQuery(document).ready(function($) {
    if (typeof kbps_cart_params === 'undefined') {
        console.error('kbps_cart_params not defined');
        return;
    }

    function updateCartCount() {
        $.ajax({
            url: kbps_cart_params.ajax_url,
            type: 'GET',
            data: {
                action: 'kbps_get_cart_count', // должен быть зарегистрирован на стороне PHP
                _wpnonce: kbps_cart_params.nonce
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data && typeof response.data.cart_count !== 'undefined') {
                    const count = parseInt(response.data.cart_count, 10);
                    const $cartCount = $('.kbps-cart-count');

                    if (count > 0) {
                        $cartCount.text(count).show();
                    } else {
                        $cartCount.hide();
                    }
                } else {
                    console.error('Ошибка получения cart_count:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX ошибка:', status, error);
            }
        });
    }

    // Инициализация счётчика при загрузке страницы
    updateCartCount();

    // Обновление счётчика при добавлении товара в корзину
    $(document.body).on('added_to_cart', function () {
        console.log('Товар добавлен в корзину');
        updateCartCount();
    });

    // Также можно вызвать при удалении товара или других событиях
    $(document.body).on('removed_from_cart', function () {
        console.log('Товар удален из корзины');
        updateCartCount();
    });
});
*/





jQuery(document).ready(function($) {
    if (typeof kbps_cart_params === 'undefined') {
        console.error('kbps_cart_params not defined');
        return;
    }

    // Инициализация счётчика
    $.ajax({
        url: kbps_cart_params.ajax_url,
        type: 'GET',
        data: {
            action: 'kbps_remove_cart_item',
            cart_item_key: 'init', // Фиктивный запрос для получения счётчика
            _wpnonce: kbps_cart_params.nonce
        },
        dataType: 'json',
        cache: false,
        success: function(response) {
            if (response.data && response.data.cart_count !== undefined) {
                const count = parseInt(response.data.cart_count, 10);

                if (count > 0) {
                    $('.kbps-cart-count')
                        .text(count)
                        .show();
                } else {
                    $('.kbps-cart-count')
                        .hide();
                }
            }
        }
    });

    // Обновление счётчика при добавлении
    $(document.body).on('added_to_cart', function() {
        console.log('Cart event triggered');
        $.ajax({
            url: kbps_cart_params.ajax_url,
            type: 'GET',
            data: {
                action: 'kbps_remove_cart_item',
                cart_item_key: 'init',
                _wpnonce: kbps_cart_params.nonce
            },
            dataType: 'json',
            cache: false,
            success: function(response) {
                if (response.data && response.data.cart_count !== undefined) {
                    $('.kbps-cart-count').text(response.data.cart_count).show();
                }
            }
        });
    });
});
