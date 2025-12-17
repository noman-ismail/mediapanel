/**
 * MediaPanel - Unified Media Manager JavaScript
 * Works with any framework - Vanilla JS, React, Vue, Blade
 */

class MediaPanel {
    constructor(options = {}) {
        this.baseUrl = options.baseUrl || window.location.origin;
        this.csrfToken = options.csrfToken || this.getCsrfToken();
        this.onSelect = options.onSelect || null;
        this.multiple = options.multiple || false;
        this.targetInput = options.targetInput || null;
        this.targetEditor = options.targetEditor || null;
        this.size = options.size || 'original';
        this.modal = null;
        this.selectedMedia = [];
    }

    /**
     * Get CSRF token from meta tag or cookie
     */
    getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) return meta.getAttribute('content');
        
        const cookie = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
        return cookie ? decodeURIComponent(cookie[1]) : '';
    }

    /**
     * Open MediaPanel modal
     */
    open(options = {}) {
        if (options.targetInput) this.targetInput = options.targetInput;
        if (options.targetEditor) this.targetEditor = options.targetEditor;
        if (options.size) this.size = options.size;
        if (options.multiple !== undefined) this.multiple = options.multiple;
        if (options.onSelect) this.onSelect = options.onSelect;

        this.loadModal();
    }

    /**
     * Load MediaPanel modal HTML
     */
    async loadModal() {
        try {
            const response = await fetch(`${this.baseUrl}/mediapanel`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            const result = await response.json();
            if (result.success && result.html) {
                this.createModal(result.html);
                this.attachEventListeners();
            } else {
                // Fallback: try HTML response
                const htmlResponse = await fetch(`${this.baseUrl}/mediapanel`);
                const html = await htmlResponse.text();
                this.createModal(html);
                this.attachEventListeners();
            }
        } catch (error) {
            console.error('Failed to load MediaPanel:', error);
            alert('Failed to load Media Panel');
        }
    }

    /**
     * Create modal overlay
     */
    createModal(content) {
        // Remove existing modal
        const existing = document.getElementById('mediapanel-modal-overlay');
        if (existing) existing.remove();

        // Create modal wrapper
        const modal = document.createElement('div');
        modal.id = 'mediapanel-modal-overlay';
        modal.className = 'mediapanel-modal-overlay';
        
        // If content is HTML string, wrap it; otherwise use as-is
        if (typeof content === 'string') {
            modal.innerHTML = `
                <div class="mediapanel-modal">
                    <div class="mediapanel-modal-header">
                        <h2>Media Library</h2>
                        <button class="mediapanel-close" onclick="window.mediaPanelInstance.close()">&times;</button>
                    </div>
                    <div class="mediapanel-modal-body">
                        ${content}
                    </div>
                    <div class="mediapanel-modal-footer">
                        <button class="mediapanel-btn-cancel" onclick="window.mediaPanelInstance.close()">Cancel</button>
                        <button class="mediapanel-btn-select" onclick="window.mediaPanelInstance.insertSelected()" disabled>
                            Select ${this.multiple ? 'Images' : 'Image'}
                        </button>
                    </div>
                </div>
            `;
        } else {
            modal.appendChild(content);
        }

        document.body.appendChild(modal);
        this.modal = modal;
        modal.style.display = 'flex';
    }

    /**
     * Load media list
     */
    async loadMedia(folderId = null, search = '') {
        const params = new URLSearchParams();
        if (folderId) params.append('folder_id', folderId);
        if (search) params.append('search', search);

        try {
            const url = folderId 
                ? `${this.baseUrl}/mediapanel/folder?${params}`
                : `${this.baseUrl}/mediapanel?${params}`;

            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            const result = await response.json();
            if (result.success) {
                this.renderMedia(result.data);
            }
        } catch (error) {
            console.error('Failed to load media:', error);
        }
    }

    /**
     * Render media grid
     */
    renderMedia(media) {
        const container = this.modal.querySelector('.mediapanel-grid') || this.createGridContainer();
        
        if (!media || media.length === 0) {
            container.innerHTML = `
                <div class="mediapanel-empty">
                    <div class="mediapanel-empty-icon">📷</div>
                    <p>No media files found.</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = media.map(item => {
            const url = item.getUrl ? item.getUrl(this.size) : (item.original_url || item.url || '');
            const thumbUrl = item.getUrl ? item.getUrl('thumb') : (item.thumb_url || item.original_url || item.url || '');
            
            return `
                <div class="mediapanel-item" data-id="${item.id}" data-url="${url}">
                    <div class="mediapanel-item-checkbox">
                        <input type="${this.multiple ? 'checkbox' : 'radio'}" 
                               name="media-select" 
                               value="${item.id}"
                               data-url="${url}"
                               data-title="${item.title || item.name || ''}">
                    </div>
                    <img src="${thumbUrl}" 
                         alt="${item.alt || item.title || item.name || ''}"
                         loading="lazy">
                    <div class="mediapanel-item-info">
                        <p class="mediapanel-item-title">${item.title || item.name || 'Untitled'}</p>
                    </div>
                </div>
            `;
        }).join('');

        this.attachMediaListeners();
    }

    /**
     * Create grid container
     */
    createGridContainer() {
        const container = document.createElement('div');
        container.className = 'mediapanel-grid';
        const body = this.modal.querySelector('.mediapanel-modal-body');
        body.appendChild(container);
        return container;
    }

    /**
     * Attach event listeners
     */
    attachEventListeners() {
        if (!this.modal) return;

        // Close on overlay click
        const overlay = this.modal.querySelector('.mediapanel-modal-overlay') || this.modal;
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay || e.target.classList.contains('mediapanel-modal-overlay')) {
                this.close();
            }
        });

        // Search functionality
        const searchInput = this.modal.querySelector('#mediapanel-search-input');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.loadMedia(null, e.target.value);
                }, 300);
            });
        }

        // Upload functionality
        const uploadInput = this.modal.querySelector('#mediapanel-upload-input');
        const uploadArea = this.modal.querySelector('#mediapanel-upload-area');
        
        if (uploadInput) {
            uploadInput.addEventListener('change', (e) => {
                if (e.target.files[0]) {
                    this.handleUpload(e.target.files[0]);
                }
            });
        }

        // Drag and drop
        if (uploadArea) {
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    this.handleUpload(file);
                }
            });
        }
    }

    /**
     * Attach media item listeners
     */
    attachMediaListeners() {
        const items = this.modal.querySelectorAll('.mediapanel-item');
        items.forEach(item => {
            item.addEventListener('click', (e) => {
                if (e.target.type === 'checkbox' || e.target.type === 'radio') return;
                
                const checkbox = item.querySelector('input[type="checkbox"], input[type="radio"]');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    this.updateSelection();
                }
            });
        });

        // Update selection on checkbox change
        const checkboxes = this.modal.querySelectorAll('input[name="media-select"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.updateSelection();
            });
        });
    }

    /**
     * Update selection state
     */
    updateSelection() {
        const selected = Array.from(this.modal.querySelectorAll('input[name="media-select"]:checked'));
        this.selectedMedia = selected.map(cb => ({
            id: cb.value,
            url: cb.dataset.url,
            title: cb.dataset.title
        }));

        const selectBtn = this.modal.querySelector('.mediapanel-btn-select');
        if (selectBtn) {
            selectBtn.disabled = this.selectedMedia.length === 0;
            selectBtn.textContent = this.multiple 
                ? `Select ${this.selectedMedia.length} Image${this.selectedMedia.length !== 1 ? 's' : ''}`
                : 'Select Image';
        }
    }

    /**
     * Insert selected media
     */
    insertSelected() {
        if (this.selectedMedia.length === 0) return;

        if (this.onSelect) {
            // Custom callback
            this.onSelect(this.multiple ? this.selectedMedia : this.selectedMedia[0]);
        } else if (this.targetInput) {
            // Insert into input field
            const input = document.querySelector(this.targetInput);
            if (input) {
                input.value = this.selectedMedia[0].url;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        } else if (this.targetEditor) {
            // Insert into editor (TinyMCE, CKEditor, etc.)
            this.insertIntoEditor(this.selectedMedia[0]);
        }

        this.close();
    }

    /**
     * Insert into editor
     */
    insertIntoEditor(media) {
        const editorId = this.targetEditor.replace('#', '');
        
        // TinyMCE
        if (window.tinymce && window.tinymce.get(editorId)) {
            window.tinymce.get(editorId).insertContent(`<img src="${media.url}" alt="${media.title}">`);
            return;
        }

        // CKEditor
        if (window.CKEDITOR && window.CKEDITOR.instances[editorId]) {
            window.CKEDITOR.instances[editorId].insertHtml(`<img src="${media.url}" alt="${media.title}">`);
            return;
        }

        // Quill
        if (window.Quill) {
            const editor = document.querySelector(this.targetEditor);
            if (editor && editor.__quill) {
                const range = editor.__quill.getSelection(true);
                editor.__quill.insertEmbed(range.index, 'image', media.url);
                return;
        }

        // Fallback: insert HTML
        const editor = document.querySelector(this.targetEditor);
        if (editor) {
            editor.innerHTML += `<img src="${media.url}" alt="${media.title}">`;
        }
    }

    /**
     * Handle file upload
     */
    async handleUpload(file) {
        if (!file) return;

        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file');
            return;
        }

        // Validate file size
        const maxSize = 5120 * 1024; // 5MB default
        if (file.size > maxSize) {
            alert(`File size exceeds maximum allowed size of ${maxSize / 1024}KB`);
            return;
        }

        const formData = new FormData();
        formData.append('image', file);

        // Show loading state
        const uploadArea = this.modal.querySelector('#mediapanel-upload-area');
        const originalHTML = uploadArea.innerHTML;
        uploadArea.innerHTML = '<p class="text-indigo-600">Uploading...</p>';

        try {
            const response = await fetch(`${this.baseUrl}/mediapanel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            });

            const result = await response.json();
            
            // Restore upload area
            uploadArea.innerHTML = originalHTML;
            this.attachEventListeners();

            if (result.success) {
                // Reload media list
                await this.loadMedia();
                // Show success message
                const successMsg = document.createElement('div');
                successMsg.className = 'mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded';
                successMsg.textContent = 'Image uploaded successfully!';
                this.modal.querySelector('.mediapanel-modal-body').insertBefore(
                    successMsg,
                    this.modal.querySelector('.mediapanel-search-container')
                );
                setTimeout(() => successMsg.remove(), 3000);
            } else {
                alert('Upload failed: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Upload failed:', error);
            uploadArea.innerHTML = originalHTML;
            this.attachEventListeners();
            alert('Upload failed: ' + error.message);
        }
    }

    /**
     * Close modal
     */
    close() {
        const modal = document.getElementById('mediapanel-modal-overlay');
        if (modal) {
            modal.remove();
        }
        this.modal = null;
        this.selectedMedia = [];
    }
}

// Initialize global instance
window.mediaPanelInstance = new MediaPanel({
    baseUrl: window.location.origin,
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
});

// Auto-initialize buttons with data-mediapanel attribute
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-mediapanel]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const options = {
                targetInput: this.dataset.targetInput || null,
                targetEditor: this.dataset.targetEditor || null,
                size: this.dataset.size || 'original',
                multiple: this.dataset.multiple === 'true',
            };

            window.mediaPanelInstance.open(options);
        });
    });
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MediaPanel;
}

