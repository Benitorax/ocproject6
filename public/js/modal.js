function openDeleteModal(event, deleteModal) {
    let deleteForm = document.getElementById("modal-delete-form");
    let trickName = document.getElementById("js-delete-trick-name");
    deleteForm.action = event.currentTarget.dataset.url;
    trickName.innerText = event.currentTarget.dataset.name;

    deleteModal.show();
}

export default function openDeleteModalCallback(event) {
    let deleteModal = new bootstrap.Modal(document.getElementById("modal-delete"));
    openDeleteModal(event, deleteModal);
}

let deleteButtons = document.querySelectorAll(".js-modal-delete");

deleteButtons.forEach(function(deleteButton) {
    deleteButton.addEventListener("click", openDeleteModalCallback);
});