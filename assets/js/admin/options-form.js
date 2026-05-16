import './video-form.js';
import { AsyncFormSubmit } from '../components/AsyncFormSubmit';
import { VideoPictureUploader } from '../components/VideoPictureUploader';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-async-form]').forEach((form) => {
        new AsyncFormSubmit(form);
    });

    document.querySelectorAll('[data-video-picture-uploader]').forEach((container) => {
        new VideoPictureUploader(container);
    });
});
