! function() {
    let illustrationFileInput = document.getElementById('snowboard_trick_illustration_file');
    let trickNameInput = document.getElementById('snowboard_trick_name');
    let bannerImage = document.getElementById('image-banner');
    let bannerTitle = document.getElementById('title-banner');
    let reader = new FileReader();

    // change dynamically the banner image
    illustrationFileInput.onchange = function(e) {
        let image = illustrationFileInput.files[0];

        if (!image.type.includes('image/')) {
            return;
        }

        reader.onload = function(e) {
            bannerImage.src = e.target.result;
        };
        reader.readAsDataURL(image);
    };

    // change dynamically the title of the banner image
    trickNameInput.addEventListener('input', function(e) {
        bannerTitle.innerText = e.target.value;
    });

    // ==========================================================================================================

    // Get the ul that holds the collection of images
    let collectionHolders = document.querySelectorAll('.js-collection-holder');

    collectionHolders.forEach(function(collectionHolder) {
        // count the current form inputs we have use that as the new
        // index when inserting a new item
        collectionHolder.dataset.index = collectionHolder.childElementCount;

        // add a delete link to all of the existing form li elements
        collectionHolder.querySelectorAll('li').forEach(function(element) {
            addDeleteButtonToForm(element);
        });
    });

    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains("add_item_link")) {
            let collectionHolderClass = e.target.dataset.collectionHolderClass;
            // add a new form to collection form
            addFormToCollection(collectionHolderClass);
        }
    });

    function addFormToCollection(collectionHolderClass) {
        // Get the ul that holds the collection of tags
        let collectionHolder = document.querySelector('.' + collectionHolderClass);

        // Get the data-prototype
        let prototype = collectionHolder.dataset.prototype;

        // get the new index
        let index = collectionHolder.dataset.index;

        let newForm = prototype;

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__/g, index);

        // increase the index with one for the next item
        collectionHolder.dataset.index = parseInt(index) + parseInt(1);

        // Display the form in the page in an li, before the "Add" link li
        let liElement = document.createElement("li");
        liElement.classList.add('list-group-item');
        liElement.innerHTML = newForm;

        // Add the new form at the end of the list
        collectionHolder.append(liElement);

        // add listener to file input of the new form for images
        if (collectionHolderClass === 'snowboard_trick_images') {
            addListenerToFileInput(liElement);
        }

        // add a delete link to the new form
        addDeleteButtonToForm(liElement);
    }

    // add a delete button to form
    function addDeleteButtonToForm(liElement) {
        let buttonElement = document.createElement("button");
        buttonElement.type = 'button';
        buttonElement.classList.add("btn", "btn-danger");
        buttonElement.innerText = 'Delete';
        liElement.querySelector('.js-button').append(buttonElement);

        buttonElement.addEventListener('click', function(e) {
            // remove the li for the form
            liElement.remove();
        });
    }

    // display image of file input
    function addListenerToFileInput(liElement) {
        let inputElement = liElement.querySelector('input')

        inputElement.onchange = function(e) {
            let image = inputElement.files[0];

            if (!image.type.includes('image/')) {
                return;
            }

            reader.onload = function(e) {
                let jsElement = liElement.querySelector('.js-image');
                jsElement.firstElementChild.src = e.target.result;
                jsElement.classList.remove('d-none');
            };
            reader.readAsDataURL(image);
        };
    }
}();