document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('footer-order-form');
    const formMessage = document.getElementById('form-message');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Собрать данные формы
            const formData = new FormData(form);
            formData.append('action', 'kbps_process_order_form');
            formData.append('nonce', kbpsAjax.nonce);

            // Очистить предыдущие сообщения
            formMessage.innerHTML = '';

            // Отправить AJAX-запрос
            fetch(kbpsAjax.ajax_url, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    formMessage.innerHTML = '<p class="success-message">' + data.data.message + '</p>';
                    form.reset();
                } else {
                    formMessage.innerHTML = '<p class="error-message">' + data.data.message + '</p>';
                }
            })
            .catch(error => {
                formMessage.innerHTML = '<p class="error-message">Chyba při odesílání formuláře. Zkuste to prosím znovu.</p>';
                console.error('Error:', error);
            });
        });
    }
});