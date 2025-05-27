document.addEventListener("DOMContentLoaded", () => {
    fetch('../get_images.php',{
        credentials: 'include',
    })
        .then(response => response.json())
        .then(data => {
    console.log("Kapott képadatok:", data);

    if (!Array.isArray(data)) {
        console.error("Visszakapott adatok nem tömb:", data);
        return;
    }

    data.forEach((image, index) => {
        console.log(`Kép #${index}:`, image);
        if (!image.filename) {
            console.warn(`Figyelem, a kép #${index} nem tartalmaz filename mezőt.`);
        }
        if (!image.user_id) {
            console.warn(`Figyelem, a kép #${index} nem tartalmaz user_id mezőt.`);
        }
    });

    const section = document.querySelector("section");
    section.innerHTML = '';

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
