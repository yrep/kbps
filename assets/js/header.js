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
    if (typeof kbps_cart_params === 'undefined' || !kbps_cart_params.ajax_url || !kbps_cart_params.nonce) {
        return;
    }

    const $cartCountElement = $('.kbps-cart-count');

    // Function to update cart count display
    function updateCartCountDisplay(count) {
        if ($cartCountElement.length === 0) {
            return;
        }
        if (count > 0) {
            $cartCountElement.text(count).addClass('active');
        } else {
            $cartCountElement.text('').removeClass('active');
        }
    }

    // Function to fetch and update cart count via AJAX
    function fetchAndUpdateCartCount() {
        $.ajax({
            url: kbps_cart_params.ajax_url,
            type: 'GET',
            data: {
                action: 'kbps_get_current_cart_count',
                _wpnonce: kbps_cart_params.nonce
            },
            dataType: 'json',
            cache: false,
            success: function(response) {
                if (response.success && typeof response.data.cart_count !== 'undefined') {
                    const actualCount = parseInt(response.data.cart_count, 10);
                    updateCartCountDisplay(actualCount);
                } else {
                    updateCartCountDisplay(0);
                }
            },
            error: function(xhr, status, error) {
                updateCartCountDisplay(0);
            }
        });
    }

    // Initial cart count load on DOM ready
    fetchAndUpdateCartCount();

    // Event listener for adding to cart
    $(document.body).on('added_to_cart', function() {
        fetchAndUpdateCartCount();
    });

    // Event listener for removing item from cart
    if ($('body').hasClass('woocommerce-cart') || $('body').hasClass('woocommerce-checkout') || $('body').hasClass('woocommerce-page')) {
        $(document).on("click", ".wc-block-cart__remove-item, .wc-block-components-product-remove, .woocommerce-cart-form__cart-item .remove", function(e) {
            e.preventDefault();

            const $this = $(this);
            const $cartItem = $this.closest("[data-cart_item_key], .cart_item, .wc-block-components-product-item");

            let cartItemKey = $this.data("cart_item_key") || $this.attr("data-cart_item_key");
            if (!cartItemKey && $this.attr("href")) {
                const hrefMatch = $this.attr("href").match(/remove_item=([^&]+)/);
                if (hrefMatch && hrefMatch[1]) {
                    cartItemKey = hrefMatch[1];
                }
            }

            if (!cartItemKey) {
                alert('Failed to find cart item key for removal. Please try reloading the page.');
                return;
            }

            $this.prop("disabled", true);
            $cartItem.css('opacity', '0.5');

            $.ajax({
                url: kbps_cart_params.ajax_url,
                type: "GET",
                data: {
                    action: "kbps_remove_cart_item",
                    cart_item_key: cartItemKey,
                    _wpnonce: kbps_cart_params.nonce
                },
                dataType: "json",
                cache: false,
                success: function(response) {
                    if (response && response.fragments) {
                        fetchAndUpdateCartCount();

                        $cartItem.fadeOut(200, function() {
                            $(this).remove();
                            if ($('body').hasClass('woocommerce-cart') && $('.woocommerce-cart-form__contents tr').length === 0 && $('.wc-block-cart__items .wc-block-components-product-item').length === 0) {
                                window.location.reload();
                            }
                        });

                        $.each(response.fragments, function(key, value) {
                            $(key).replaceWith(value);
                        });

                        $(document.body).trigger("wc_cart_updated");
                        $(document.body).trigger("wc-blocks_cart_updated");
                        if (window.wc && window.wc.blocksCart) {
                            window.wc.blocksCart.dispatch("cart-updated");
                        }
                    } else if (response && response.success === false) {
                        $cartItem.fadeIn(200).css('opacity', '1');
                        alert('An error occurred during item removal: ' + (response.data && response.data.message ? response.data.message : 'Unknown error.'));
                    } else {
                        $cartItem.fadeIn(200).css('opacity', '1');
                        alert('An error occurred during item removal: Unexpected server response.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('A network error occurred during item removal. Please try again.');
                },
                complete: function() {
                    $this.prop("disabled", false);
                }
            });
        });
    }

    // Event listener for changing item quantity in cart
    if ($('body').hasClass('woocommerce-cart') || $('body').hasClass('woocommerce-checkout')) {
        $(document).on('change', 'input.qty', function() {
            const $this = $(this);
            const $cartItem = $this.closest('.cart_item, .woocommerce-cart-form__cart-item, .wc-block-components-product-item');
            const cartItemKey = $this.attr('name').match(/cart\[([^\]]+)\]\[qty\]/)[1];
            const newQuantity = parseInt($this.val(), 10);

            if (!cartItemKey || isNaN(newQuantity)) {
                return;
            }

            $this.prop('disabled', true);
            $cartItem.css('opacity', '0.5');

            $.ajax({
                url: kbps_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'kbps_update_cart_item_quantity',
                    cart_item_key: cartItemKey,
                    qty: newQuantity,
                    _wpnonce: kbps_cart_params.nonce
                },
                dataType: 'json',
                success: function(response) {
                    if (response && response.fragments) {
                        fetchAndUpdateCartCount();

                        $.each(response.fragments, function(key, value) {
                            $(key).replaceWith(value);
                        });

                        $(document.body).trigger('wc_cart_updated');
                        $(document.body).trigger('wc-blocks_cart_updated');
                        if (window.wc && window.wc.blocksCart) {
                            window.wc.blocksCart.dispatch('cart-updated');
                        }

                        if (newQuantity === 0 && ($('body').hasClass('woocommerce-cart') || $('body').hasClass('woocommerce-checkout'))) {
                            if ($('.woocommerce-cart-form__contents tr').length === 0 && $('.wc-block-cart__items .wc-block-components-product-item').length === 0) {
                                window.location.reload();
                            }
                        }

                    } else if (response && response.success === false) {
                        alert('An error occurred while changing quantity: ' + (response.data && response.data.message ? response.data.message : 'Unknown error.'));
                    } else {
                        alert('An error occurred while changing quantity: Unexpected server response.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('A network error occurred while changing quantity. Please try again.');
                },
                complete: function() {
                    $this.prop('disabled', false);
                    $cartItem.css('opacity', '1');
                }
            });
        });
    }

    // Fallback event for WooCommerce's 'removed_from_cart'
    $(document.body).on('removed_from_cart', function(event, fragments, cart_hash, removed_item_key) {
        fetchAndUpdateCartCount();
    });
});