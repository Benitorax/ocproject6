let illustrationFileInput = document.getElementById("snowboard_trick_illustration_file");
let trickNameInput = document.getElementById("snowboard_trick_name");
let bannerImage = document.getElementById("image-banner");
let bannerTitle = document.getElementById("title-banner");
let reader = new FileReader();

// change dynamically the banner image
if (illustrationFileInput != null) {
    illustrationFileInput.onchange = function(e) {
        let image = illustrationFileInput.files[0];

        if (!image.type.includes("image/")) {
            return;
        }

        document.getElementById("snowboard_trick_hasIllustration").checked = true;
        reader.onload = function(e) {
            bannerImage.src = e.target.result;
        };
        reader.readAsDataURL(image);
    };
}


// change dynamically the title of the banner image
if (trickNameInput != null) {
    trickNameInput.addEventListener("input", function(e) {
        bannerTitle.innerText = e.target.value;
    });
}

// ==========================================================================================================

// add click listener to delete button
function addListenerToDeleteButton(divElement) {
    let buttonElement = divElement.querySelector(".js-icon-delete");
    buttonElement.addEventListener("click", function(e) {
        // remove the form div
        divElement.remove();
    });
}

// display image of file input and remove error message
function addListenerToFileInput(divElement) {
    let inputElement = divElement.querySelector("input");
    if (inputElement === null) {
        return;
    }

    inputElement.onchange = function(e) {
        // remove error message
        let errorElement = divElement.querySelector(".js-error");
        if (errorElement !== null) {
            errorElement.remove();
        }

        // display image from file input
        let image = inputElement.files[0];

        if (image.type.includes("image/")) {
            reader.onload = function(e) {
                let jsElement = divElement.querySelector(".js-image");
                if (jsElement !== null) {
                    jsElement.firstElementChild.src = e.target.result;
                    jsElement.classList.remove("d-none");
                }
            };
            reader.readAsDataURL(image);
        }

        // hide the input file and show the "edit" icon
        inputElement.classList.add("d-none");
        divElement.querySelector(".js-icon-edit").classList.remove("d-none");
    };
}

function addFormToCollection(collectionHolderClass) {
    // Get the ul that holds the collection of tags
    let collectionHolder = document.querySelector("." + collectionHolderClass);

    // Get the data-prototype
    let prototype = collectionHolder.dataset.prototype;

    // get the new index
    let index = collectionHolder.dataset.index;

    let newForm = prototype;

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    collectionHolder.dataset.index = parseInt(index, 10) + parseInt(1, 10);

    // Display the form in the page in an div, before the "Add" link
    let divElement = document.createElement("div");
    divElement.classList.add("card", "mx-auto", "mb-3");
    divElement.style.width = "19.6rem";
    divElement.innerHTML = newForm;

    // Add the new form at the end of the list
    collectionHolder.append(divElement);

    // add listener to file input of the new form for images
    if (collectionHolderClass === "snowboard_trick_images") {
        addListenerToFileInput(divElement);
    }

    // add click listener to delete button
    addListenerToDeleteButton(divElement);
}

// Get the ul that holds the collection of images
let collectionHolders = document.querySelectorAll(".js-collection-holder");

collectionHolders.forEach(function(collectionHolder) {
    // count the current form inputs we have use that as the new
    // index when inserting a new item
    collectionHolder.dataset.index = collectionHolder.childElementCount;

    // add a delete link to all of the existing form div elements
    collectionHolder.querySelectorAll("div.card").forEach(function(element) {
        // add delete button and listener to form
        addListenerToDeleteButton(element);
        addListenerToFileInput(element);
    });
});

document.body.addEventListener("click", function(e) {
    if (e.target.classList.contains("add_item_link")) {
        let collectionHolderClass = e.target.dataset.collectionHolderClass;
        // add a new form to collection form
        addFormToCollection(collectionHolderClass);
    }
});

// add listener to delete illustration button
let illDeleteButton = document.querySelector(".js-icon-delete-illustration");
if (illDeleteButton !== null) {
    illDeleteButton.addEventListener("click", function() {
        document.getElementById("snowboard_trick_illustration_file").value = "";
        document.getElementById("snowboard_trick_hasIllustration").checked = false;
        bannerImage.src = "/images/default.png";
    });
}