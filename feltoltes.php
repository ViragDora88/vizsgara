<?php

// Képfeltöltés 
if(isset($_POST['feltoltes'])) {

	$error=false;
	$notify=false;
	$error_msg = array();
	$notify_msg = array();
	$runtime_error = array();

    // Megnézzük, hogy van-e fájl
	if(!$error AND !isset($_FILES['image'])) {
		$error = true;
		$error_msg[] = 'Nem választottál ki fájlt!';
	}

	$maxFileSize = $max_image_size * 1024 * 1024; // 30 MB

	if (!$error AND $_FILES['image']['size'] > $maxFileSize) {
		$error = true;
		$error_msg[] = 'A fájl mérete túl nagy! (max. '.$max_image_size.'MB)';
	}

	print_r($_FILES['image']['error']);

	// Megnézzük, hogy volt-e hiba a fájl feltöltésekor
	if(!$error AND $_FILES['image']['error'] != 0) {
		$error = true;
		switch ($_FILES['image']['error']) {
			case 1:
			//case 2:
				$error_msg[] = 'A fájl mérete túl nagy!<br>Kérlek méretezd át a képed, hogy ne haladja meg a megengedett maximális méretet, vagy vedd fel velünk a kapcsolatot a lenti "Elérhetőségek" linkre kattintva.';
				break;
			default:
				$error_msg[] = 'Hiba történt a fájl feltöltése közben! Szükség esetén a lenti "Elérhetőségek" linkre kattintva felveheted velünk a kapcsolatot, a hibakód: '.$_FILES['image']['error'];
				break;
		}
	}

	// Ellenőrzések a fájlon
	if(!$error AND is_uploaded_file($_FILES['image']['tmp_name'])) {

		$filename = $_FILES['image']['tmp_name'];
    }
	
}