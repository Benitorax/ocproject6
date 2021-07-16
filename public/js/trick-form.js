! function() {
    let illustrationFileInput = document.getElementById('snowboard_trick_illustration_file');
    let trickNameInput = document.getElementById('snowboard_trick_name');
    let bannerImage = document.getElementById('image-banner');
    let bannerTitle = document.getElementById('title-banner');

    illustrationFileInput.onchange = function(e) {
        let image = illustrationFileInput.files[0];

        if (!image.type.includes('image/')) {
            return;
        }

        let reader = new FileReader();
        reader.onload = function(e) {
            bannerImage.src = e.target.result;
        };
        reader.readAsDataURL(image);
    };

    trickNameInput.addEventListener('input', function(e) {
        bannerTitle.innerText = e.target.value;
    });
}();