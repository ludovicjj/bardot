import { initCkeditor } from '../components/CkeditorInit';
import { CopyText } from '../components/CopyText';
import { ResetToken } from '../components/ResetToken';

document.addEventListener('DOMContentLoaded', () => {
    initCkeditor('textarea[data-ckeditor]');
    new CopyText('#copy-url-btn', '#video-url', '#copy-url-text');
    new ResetToken('#reset-token-btn', '#video-url', '#reset-token-text');
});
