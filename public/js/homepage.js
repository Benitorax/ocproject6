! function() {
    function scrollToElement(element) {
        element.scrollIntoView();
    }

    /* Checks if the top border of an element is inside viewport */
    function isElementTopInViewPort(element) {
        let bounds = element.getBoundingClientRect();
        if (bounds['top'] < 0) return false;

        return true;
    }

    function showElement(element) {
        element.style.display = 'block';
    }

    function hideElement(element) {
        element.style.display = 'none';
    }

    let timer = null;

    let banner = document.getElementById('image-banner');
    let upIcon = document.getElementById('scroll-up-to-tricks');
    let tricks = document.getElementById('tricks');

    scrollToElement(banner);

    document.addEventListener('scroll', event => {
        if (timer !== null) {
            clearTimeout(timer);
        }
        timer = setTimeout(function() {
            if (isElementTopInViewPort(tricks)) {
                hideElement(upIcon);
            } else {
                showElement(upIcon);
            }
        }, 300);
    });
}()