! function() {
    // submit comment via ajax ========================================
    let formEl = document.querySelector(".js-form-comment");

    if (formEl !== null) {
        let buttonEl = formEl.querySelector(".js-button");
        let errorEl = formEl.querySelector(".js-error");
        let contentTextarea = formEl.querySelector("textarea");

        // resets error messages and hides elements
        const resetFormError = function() {
            // error message element
            errorEl.textContent = "";
            errorEl.hidden = true;
        };

        // sends ajax request when the form is submit
        formEl.addEventListener("submit", function(e) {
            e.preventDefault();
            submitForm();
        });

        async function submitForm() {
            resetFormError();
            buttonEl.disabled = true;
            contentTextarea.readOnly = true;

            // tries the fetch to catch errors and to allow trowing errors inside the try block
            try {
                let response = await fetch(formEl.getAttribute("action"), {
                    method: "POST",
                    body: new FormData(formEl)
                });

                let data = await response.json();
                console.log(data, data.error);
                // if 422 then sets error messages
                if (response.status === 422) {
                    let error = data.error;

                    if (error !== null) {
                        // sets the message in the form
                        errorEl.textContent = error;
                        errorEl.hidden = false;
                    }

                    buttonEl.disabled = false;
                    contentTextarea.readOnly = false;

                    // if 303 then redirects to new url
                } else if (response.status === 303) {
                    document.location.href = data.url;
                } else {
                    // if not 303 or 422, then executes the catch block
                    throw 500;
                }
            } catch (error) {
                errorEl.textContent = "Sorry, the website can't currently submit your comment. Please, try again later.";
                errorEl.hidden = false;
            }
        }
    }

    // add id to pagination links
    document.querySelector('.pagination').querySelectorAll('a').forEach(function(element) {
        element.href += '#comments';
    });
}()