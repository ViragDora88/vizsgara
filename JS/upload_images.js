//képek feltölése, olvasás

function previewImage(event) {
    const reader = new FileReader();
    const imagePreview = document.getElementById('image-preview');

    reader.onload = function() {
        imagePreview.src = reader.result;
        imagePreview.style.display = 'block';
    }

    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}