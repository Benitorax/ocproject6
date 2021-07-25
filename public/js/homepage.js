import openDeleteModalCallback from "/js/modal.js";

(function() {
    function scrollToElement(element) {
        element.scrollIntoView();
    }

    // check if the top border of an element is inside viewport
    function isElementTopInViewPort(element) {
        let bounds = element.getBoundingClientRect();
        if (bounds["top"] < 0) {
            return false;
        }

        return true;
    }

    // check if the bottom border of an element is inside viewport
    function isElementBottomInViewPort(element) {
        let bounds = element.getBoundingClientRect();
        if (bounds["bottom"] > window.innerHeight) {
            return false;
        }

        return true;
    }

    function showElement(element) {
        element.style.display = "block";
    }

    function hideElement(element) {
        element.style.display = "none";
    }

    let timer = null;
    let banner = document.getElementById("image-banner");
    let upIcon = document.getElementById("scroll-up-to-tricks");
    let tricks = document.getElementById("tricks");
    let spinner = document.querySelector(".js-spinner");
    tricks.dataset.index = 8;

    // load more tricks
    async function loadTricks() {
        try {
            let index = tricks.dataset.index;
            let response = await fetch("/api/trick?index=" + index, {
                method: "GET",
            });

            tricks.dataset.index = parseInt(8, 10) + parseInt(index, 10);
            let data = await response.json();

            if (data.body.length > 0) {
                const fragment = document.createRange().createContextualFragment(data.body);
                document.getElementById("tricks-content").appendChild(fragment);
                let deleteButtons = document.querySelectorAll(".js-modal-delete");

                deleteButtons.forEach(function(deleteButton) {
                    deleteButton.removeEventListener("click", openDeleteModalCallback);
                    deleteButton.addEventListener("click", openDeleteModalCallback);
                });

            } else {
                hideElement(spinner);
            }

        } catch (error) {
            spinner.innerHTML = "Failed to load more tricks.";
        }
    }

    scrollToElement(banner);

    document.addEventListener("scroll", (event) => {
        if (timer !== null) {
            clearTimeout(timer);
        }
        timer = setTimeout(function() {
            // hide or show arrow icon depending of tricks element in view port
            if (isElementTopInViewPort(tricks)) {
                hideElement(upIcon);
            } else {
                showElement(upIcon);
            }

            // load more tricks if spinner is inside viewport
            if (isElementBottomInViewPort(spinner)) {
                loadTricks();
            }
        }, 200);
    });
})();