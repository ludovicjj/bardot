function initVideoCards() {
    document.querySelectorAll('[data-video-embed]').forEach((button) => {
        button.addEventListener('click', () => {
            const url = button.dataset.embedUrl;
            const title = button.dataset.videoTitle || 'Vidéo';
            const playUrl = url + (url.includes('?') ? '&' : '?') + 'autoplay=1';

            const wrapper = document.createElement('div');
            wrapper.className = 'aspect-video bg-gray-900 overflow-hidden rounded-lg shadow-sm';

            const iframe = document.createElement('iframe');
            iframe.src = playUrl;
            iframe.title = title;
            iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
            iframe.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
            iframe.setAttribute('allowfullscreen', '');
            iframe.className = 'w-full h-full border-0';

            wrapper.appendChild(iframe);
            button.replaceWith(wrapper);
        });
    });
}

document.addEventListener('DOMContentLoaded', initVideoCards);
