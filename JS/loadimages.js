document.addEventListener("DOMContentLoaded", () => {
    fetch("../get_images.php")
        .then(res => res.json())
        .then(images => {
            const section = document.querySelector(".gallery section");

            if (!Array.isArray(images)) {
                console.error("Nem tömb jött vissza:", images);
                return;
            }

            images.forEach(image => {
                const imgWrapper = document.createElement("div");
                imgWrapper.classList.add("image-box");

                const img = document.createElement("img");
                img.src = `../uploads/${image.filename}`;
                img.alt = "Feltöltött kép";

                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.name = "selected_images[]";
                checkbox.value = image.id;

                imgWrapper.appendChild(img);
                imgWrapper.appendChild(checkbox);

                section.appendChild(imgWrapper);
            });
            
        })
        .catch(err => console.error("Hiba a képek betöltésekor:", err));
});