document.addEventListener("DOMContentLoaded", () => {
    fetch('../get_images.php')
        .then(response => response.json())
        .then(data => {
            const section = document.querySelector("section");
            data.forEach(image => {
                const div = document.createElement("div");
                div.classList.add("image-item");

                const img = document.createElement("img");
                img.src = `../contest/${image.user_id}/${image.filename}`;
                img.alt = "Feltöltött kép";
                img.width = 200;

                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.name = "selected_images[]";
                checkbox.value = image.id;

                div.appendChild(img);
                div.appendChild(checkbox);
                section.appendChild(div);
            });
        })
        .catch(error => {
            console.error("Hiba a képek betöltése közben:", error);
        });
        
});