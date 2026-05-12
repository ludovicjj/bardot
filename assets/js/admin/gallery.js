import { initThumbnailPreview } from '../components/thumbnailManager';
import { PicturesManager } from '../components/PicturesManager';
import { CopyText } from '../components/CopyText';
import { ResetToken } from '../components/ResetToken';
import { initCategorySearch } from '../components/CategorySearch';

// Init Copy URL button
new CopyText('#copy-url-btn', '#gallery-url', '#copy-url-text');

// Init Reset Token button
new ResetToken('#reset-token-btn', '#gallery-url', '#reset-token-text', '#gallery-qrcode');

// Init Thumbnail preview
initThumbnailPreview('#gallery_thumbnailFile', '#thumbnail-dropzone');

// Init Pictures upload (only on update page)
if (document.querySelector('#pictures-dropzone')) {
    new PicturesManager('#pictures-dropzone', '#pictures-input');
}

// Init Categories TomSelect
initCategorySearch('.js-category-search');

// Toggle the download URL field based on the downloadable checkbox
const downloadToggle = document.querySelector('#gallery_downloadable');
const downloadWrapper = document.querySelector('#download-url-wrapper');
if (downloadToggle && downloadWrapper) {
    downloadToggle.addEventListener('change', () => {
        downloadWrapper.classList.toggle('hidden', !downloadToggle.checked);
    });

    if (downloadToggle.checked) {
        downloadWrapper.classList.toggle('hidden', !downloadToggle.checked);
    }
}
