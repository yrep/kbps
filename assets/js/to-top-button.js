document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.querySelector('.back-to-top');
    
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    /*
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.style.display = 'flex';
        } else {
            backToTopButton.style.display = 'none';
        }
    });
    */
});


//Country disable (checkout)
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, что мы на странице оформления заказа
    if (document.querySelector('body.woocommerce-checkout')) {
        // Находим все элементы select2
        const select2Elements = document.querySelectorAll('.select2-selection.select2-selection--single');
        
        // Добавляем disabled к каждому найденному элементу
        select2Elements.forEach(element => {
            element.setAttribute('disabled', 'disabled');
            
            // Дополнительные стили для визуального отключения
            element.style.opacity = '0.7';
            element.style.cursor = 'not-allowed';
            
            // Находим соответствующий скрытый select и тоже отключаем его
            const selectId = element.closest('.select2-container').previousElementSibling.id;
            if (selectId) {
                const originalSelect = document.getElementById(selectId);
                if (originalSelect) {
                    originalSelect.disabled = true;
                }
            }
        });
        
        // Отключаем клики по стрелке выпадающего списка
        const select2Arrows = document.querySelectorAll('.select2-selection__arrow');
        select2Arrows.forEach(arrow => {
            arrow.style.pointerEvents = 'none';
            arrow.style.opacity = '0.5';
        });
    }
});