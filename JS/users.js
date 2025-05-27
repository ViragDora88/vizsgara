window.addEventListener('DOMContentLoaded', () => {
    fetch('../gert_images.php')
      .then(response => response.json())
      .then(images => {
        const container = document.getElementById('imageContainer');
        container.innerHTML = '';  // Ha van benne valami, töröljük

        images.forEach(img => {
          const imgElem = document.createElement('img');
          imgElem.src = 'uploads/' + img.filename; // vagy a képek helye a szerveren
          imgElem.alt = 'Felhasználói kép';
          imgElem.style.maxWidth = '150px';  // pl. stílus
          imgElem.style.margin = '5px';

          container.appendChild(imgElem);
        });
        
      })
      .catch(error => {
        console.error('Hiba a képek betöltésekor:', error);
      });
});
