document.addEventListener('DOMContentLoaded', function() {
    let galleryFrame = null;
    const uploadButton = document.querySelector('.kbps-gallery-upload');
    const galleryInput = document.getElementById('kbps_gallery');
    const previewContainer = document.getElementById('kbps-gallery-preview');

    if (!uploadButton || !galleryInput || !previewContainer) {
        return;
    }

    // Открытие медиабиблиотеки
    uploadButton.addEventListener('click', function(event) {
        event.preventDefault();

        // Создаем или открываем медиабиблиотеку
        if (galleryFrame) {
            galleryFrame.open();
            return;
        }

        galleryFrame = wp.media({
            title: 'Select Gallery Images',
            button: {
                text: 'Add to Gallery'
            },
            multiple: true,
            library: {
                type: 'image'
            }
        });

        // Обработка выбора изображений
        galleryFrame.on('select', function() {
            const selection = galleryFrame.state().get('selection');
            const imageIds = [];
            let previewHtml = '';

            selection.each(function(attachment) {
                const id = attachment.id;
                const thumbnail = attachment.attributes.sizes.thumbnail.url;
                imageIds.push(id);
                previewHtml += `
                    <div class="kbps-gallery-image" style="display: inline-block; margin: 5px; position: relative;">
                        <img src="${thumbnail}" style="max-width: 100px; height: auto;">
                        <span class="kbps-gallery-remove" data-id="${id}" style="position: absolute; top: 0; right: 0; cursor: pointer; background: red; color: white; padding: 2px 5px;">×</span>
                    </div>
                `;
            });

            galleryInput.value = imageIds.join(',');
            previewContainer.innerHTML = previewHtml;
        });

        galleryFrame.open();
    });

    // Удаление изображения из галереи
    previewContainer.addEventListener('click', function(event) {
        if (event.target.classList.contains('kbps-gallery-remove')) {
            const imageContainer = event.target.closest('.kbps-gallery-image');
            const id = event.target.getAttribute('data-id');
            const imageIds = galleryInput.value.split(',').filter(item => item !== id && item !== '');

            galleryInput.value = imageIds.join(',');
            if (imageContainer) {
                imageContainer.remove();
            }
        }
    });
});