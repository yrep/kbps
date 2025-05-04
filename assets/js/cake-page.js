document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('filling-modal');
    const closeButton = modal.querySelector('.close-button');
    const fillingLinks = document.querySelectorAll('.filling-item .select-filling-link');
    const modalImageFull = document.getElementById('modal-filling-image-full');
    const imagePlaceholderSquare = modal.querySelector('.image-placeholder-square');
    const modalTitle = document.getElementById('modal-filling-title');
    const modalDescription = document.getElementById('modal-filling-description');


    const ajaxUrl = typeof kbps_ajax_object !== 'undefined' ? kbps_ajax_object.ajax_url : '';

    function showPlaceholder() {
        imagePlaceholderSquare.style.display = 'block';
        modalImageFull.style.display = 'none';
    }

    function showImage() {
        imagePlaceholderSquare.style.display = 'none';
        modalImageFull.style.display = 'block';
    }

    function openModal(fillingId) {
        if (!ajaxUrl) {
            console.error('AJAX URL is missing.');
            return;
        }

        modalTitle.textContent = 'Načítání, vydržte...';
        modalDescription.textContent = '';
        modalImageFull.src = '';

        showPlaceholder();
        modal.style.display = 'flex';


        fetch(ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'action': 'get_filling_data',
                'filling_id': fillingId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.data) {
                const fillingData = data.data;

                modalTitle.textContent = fillingData.title || 'Žádné jméno';
                modalDescription.innerHTML = fillingData.description || 'Žádný popis.';

                if (fillingData.full_image_url) {
                    modalImageFull.onload = function() {
                        showImage();
                        modalImageFull.onload = null;
                        modalImageFull.onerror = null;
                    };
                    modalImageFull.onerror = function() {
                        console.error('Image loading error:', fillingData.full_image_url);
                        showPlaceholder();
                        modalImageFull.onload = null;
                        modalImageFull.onerror = null;
                    };
                    modalImageFull.src = fillingData.full_image_url;
                } else {
                    console.warn('No image URL for filling:', fillingId);
                    showPlaceholder();
                }

            } else {
                console.error('AJAX error or non-valid data:', data.data);
                modalTitle.textContent = 'Data load error.';
                modalDescription.textContent = '';
                showPlaceholder();
            }
        })
        .catch(error => {
            console.error('Query error:', error);
            modalTitle.textContent = 'Network error.';
            modalDescription.textContent = '';
            showPlaceholder();
        });
    }

    function closeModal() {
        modal.style.display = 'none';
        modalTitle.textContent = '';
        modalDescription.textContent = '';
        modalImageFull.src = '';
        showPlaceholder();
    }

    fillingLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();

            const urlParams = new URLSearchParams(link.href.split('?')[1]);
            const fillingId = urlParams.get('filling_id');

            if (fillingId) {
                openModal(fillingId);
            } else {
                console.error('Filling ID is not set');
            }
        });
    });

    closeButton.addEventListener('click', closeModal);

    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });


    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.style.display === 'flex') {
            closeModal();
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const scrollButton = document.querySelector('.scroll-to-footer');
    if (scrollButton) {
        scrollButton.addEventListener('click', function(event) {
            event.preventDefault();
            const footer = document.querySelector('footer');
            if (footer) {
                footer.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
});