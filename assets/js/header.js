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






jQuery(document).ready(function($) {
    if (typeof kbps_cart_params === 'undefined') {
        console.error('kbps_cart_params not defined');
        return;
    }

    function updateCartCount(count) {
        const $cartCount = $('.kbps-cart-count');
        if (count > 0) {
            $cartCount.text(count).addClass('active');
        } else {
            $cartCount.text('').removeClass('active');
        }
    }

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
            if (response.data && typeof response.data.cart_count !== 'undefined') {
                updateCartCount(parseInt(response.data.cart_count, 10));
            } else {
                updateCartCount(0);
            }
        },
        error: function() {
            updateCartCount(0);
        }
    });

    $(document.body).on('added_to_cart', function() {
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
                if (response.data && typeof response.data.cart_count !== 'undefined') {
                    updateCartCount(parseInt(response.data.cart_count, 10));
                } else {
                    updateCartCount(0);
                }
            },
            error: function() {
                updateCartCount(0);
            }
        });
    });
});
