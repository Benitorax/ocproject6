! function() {
    let mediaButton = document.querySelector('.js-media-button');
    if (mediaButton !== null) {
        mediaButton.addEventListener('click', function(event) {
            mediaButton.parentElement.parentElement.remove();
            let mediaContent = document.querySelector('.js-media-content');
            mediaContent.classList.remove('d-none');
        });
    }
}()