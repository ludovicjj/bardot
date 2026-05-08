import '../../styles/components/ckeditor-dark.scss';

const DEFAULT_TOOLBAR = [
    'bold', 'italic', 'underline',
    '|',
    'bulletedList', 'numberedList',
    '|',
    'blockQuote',
    '|',
    'undo', 'redo',
];

export function initCkeditor(selector, config = {}) {
    const targets = document.querySelectorAll(selector);

    if (targets.length === 0) {
        return;
    }

    if (typeof window.ClassicEditor === 'undefined') {
        console.error('CKEditor build is not loaded — ensure <script src="build/ckeditor/ckeditor.js"> is included before this script.');
        return;
    }

    targets.forEach((textarea) => {
        if (textarea.dataset.ckeditorInitialized === 'true') {
            return;
        }
        textarea.dataset.ckeditorInitialized = 'true';

        window.ClassicEditor
            .create(textarea, {
                toolbar: DEFAULT_TOOLBAR,
                ...config,
            })
            .catch((error) => {
                textarea.dataset.ckeditorInitialized = 'false';
                console.error('CKEditor init error:', error);
            });
    });
}
