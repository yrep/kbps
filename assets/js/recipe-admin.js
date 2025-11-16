/**
 * Recipe File Manager - Modern ES2018+ JavaScript for recipe file uploads
 */

class RecipeFileManager {
    constructor() {
        this.selectors = {
            uploadButton: '.kbps-upload-file-button',
            fileIdInput: '#kbps_recipe_file_id',
            fileUrlInput: '#kbps_recipe_file_url'
        };
        
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        const { uploadButton } = this.selectors;
        
        document.addEventListener('click', (event) => {
            if (event.target.matches(uploadButton) || event.target.closest(uploadButton)) {
                this.handleFileUpload(event);
            }
        });
    }

    async handleFileUpload(event) {
        event.preventDefault();
        
        try {
            const fileFrame = wp.media({
                title: 'Select PDF File',
                library: { 
                    type: 'application/pdf' 
                },
                button: { 
                    text: 'Use this file' 
                },
                multiple: false
            });

            const attachment = await new Promise((resolve) => {
                fileFrame.on('select', () => {
                    const selected = fileFrame.state().get('selection').first().toJSON();
                    resolve(selected);
                });
                fileFrame.open();
            });

            this.updateFileFields(attachment);
            
        } catch (error) {
            console.error('File selection error:', error);
        }
    }

    updateFileFields(attachment) {
        const { fileIdInput, fileUrlInput } = this.selectors;
        
        const idField = document.querySelector(fileIdInput);
        const urlField = document.querySelector(fileUrlInput);
        
        if (idField && urlField) {
            idField.value = attachment.id;
            urlField.value = attachment.url;
            
            this.showSuccessMessage(`File "${attachment.filename}" selected`);
        }
    }

    showSuccessMessage(message) {
        const existingNotice = document.querySelector('.kbps-upload-notice');
        if (existingNotice) {
            existingNotice.remove();
        }

        const notice = document.createElement('div');
        notice.className = 'kbps-upload-notice updated notice is-dismissible';
        notice.style.margin = '10px 0';
        notice.innerHTML = `
            <p>${message}</p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss</span>
            </button>
        `;

        const form = document.querySelector('#kbps_recipe_file');
        if (form) {
            form.appendChild(notice);
        }

        setTimeout(() => {
            notice.remove();
        }, 3000);
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => new RecipeFileManager());
} else {
    new RecipeFileManager();
}