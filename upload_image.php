<?php // képek feltöltése
session_start();
require_once '/../src/db.php';
$db = new Db();
$conn = $db->getConnection();

// Képfeltöltés 
if(isset($_POST['feltoltes'])) {

	$error=false;
	$notify=false;
	$error_msg = array();
	$notify_msg = array();
	$runtime_error = array();

	// Megnézzük van-e kategória
	if(!isset($_POST['category_id']) OR !is_numeric($_POST['category_id'])) {
		$error = true;
		$error_msg[] = 'Nem választottál kategóriát!';
	} 

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

        
		// MIME ellenőrzés (JPG)
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $filename);
		finfo_close($finfo);
		if($mime != 'image/jpeg') {
			$error = true;
			$error_msg[] = 'A fájl nem megfelelő formátumú!';
			
		}




		// Ellenőrizzük, van e EXIF adat
		
		$exif = exif_read_data($filename);
		if(!$exif OR !isset($exif['Model'])) {
			$error = true;
			$error_msg[] = 'Hiányzik az EXIF adat, a fájlnak tartalmaznia kell arra vonatkozó információkat, hogy az mivel készült!';
		}
		


	}

	// Megnézzük, volt-e hiba az ellenőrzések során
	if($error) {
		print '<div class="error">';
		foreach($error_msg as $msg) {
			print '<p>'.$msg.'</p>';
		}
		print '</div>';
	} else {

		if($notify) {
			print '<div class="notify">';
			foreach($notify_msg as $msg) {
				print '<p>'.$msg.'</p>';
			}
			print '</div>';
		}

		// Megnézzük, van-e már mapája a felhasználónak
		if(!is_dir('contest/'.$_SESSION['uid'])) {
			mkdir('contest/'.$_SESSION['uid']);
			
		}

		if(!is_dir('contest/'.$_SESSION['uid'].'/thumbs')) {
			mkdir('contest/'.$_SESSION['uid'].'/thumbs');	
		}

		// Ha minden rendben van, akkor feltöltjük a képet, egy új néven uid-dátum(YmdHis).jpg
		$path = 'contest/'.$_SESSION['uid'].'/';
		$category_id = (is_numeric($_POST['category_id'])) ? $_POST['category_id'] : 0;
		$filename = $_SESSION['uid'].'-'.$category_id.'-'.date('YmdHis').'.jpg';
		move_uploaded_file($_FILES['image']['tmp_name'], $path.$filename);

		// Kimentjük az EXIF adatokat
		$exif = exif_read_data($path.$filename);

		$selectedFields = [
			'Make',
			'Model',
			'ExposureTime',
			'FNumber',
			'ISOSpeedRatings',
			'DateTimeOriginal',
			'DateTimeDigitized',
			'FocalLength',
			'MakerNote'
		];
		
		$selectedExifData = [];
		
		foreach ($selectedFields as $field) {
			if (array_key_exists($field, $exif)) {
				$selectedExifData[$field] = $exif[$field];
			}
		}

		$exif = json_encode($selectedExifData);

		// Kimentjük az adatbázisba
		$stmt = $mysqli->prepare("INSERT INTO images (id, filename, user_id, uploaded_at) VALUES (?, ?, ?, ?, ?)");
		$stmt->bind_param("iisss", $_SESSION['id'], $_POST['user_id'], $filename, $_FILES['image']['name'], $exif);
		$stmt->execute();
		$stmt->close();
    }
    // Kicsinyített kép készítése
		$filename_small = $path.'thumbs/thumb_'.$filename;
		$im = imagecreatefromjpeg($path.$filename);
		$width = imagesx($im);
		$height = imagesy($im);
		$maxSize = 200; // Maximális oldalhosszúság

		// Meghatározzuk a nagyobb oldalt és korlátozzuk a méretét a maximálisra
		if ($width > $height) {
			$newwidth = min($maxSize, $width);
			$newheight = intval(($height / $width) * $newwidth);
		} else {
			$newheight = min($maxSize, $height);
			$newwidth = intval(($width / $height) * $newheight);
		}

		$thumb = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		imagejpeg($thumb, $filename_small, 100);


		// Forgatjuk a kis képet ha szükséges és ha tudjuk az orientációt
		if(isset($exif)) {
			$exif = exif_read_data($path.$filename);
			if(isset($exif['Orientation'])) {
				switch($exif['Orientation']) {
					case 3:
						$thumb = imagerotate($thumb, 180, 0);
						break;
					case 6:
						$thumb = imagerotate($thumb, -90, 0);
						break;
					case 8:
						$thumb = imagerotate($thumb, 90, 0);
						break;
				}
			}
			imagejpeg($thumb, $filename_small, 100);
		}
}


/*if(isset($_POST) && !empty($_FILES['image']['filename']))

    $filename = $_FILES['image']['filename'];
    //list($txt, $ext) = explode(",", $filename);
    $image_name = time().".".$ext;
    $tmp = $_FILES['image']['tmp_name'];

    if(move_uploaded_file($tmp, "../uploads/".$image_name)){
      $sql = "INSERT INTO images (filename, user_id) VALUES (?, ?)";

      $result = $conn->query($sql);

      if($result){
        echo "Sikeres feltöltés!";
        }else{
        echo "Hiba a feltöltéskor.";
        }
    }*/


/*if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $userId = $_POST['user_id'];  // Admin adja meg
    $file = $_FILES['image'];

    $targetDir = "../uploads/";
    $filename = uniqid() . "_" . basename($file["name"]);
    $targetFile = $targetDir . $filename;

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO images (filename, user_id) VALUES (?, ?)");
        $stmt->execute([$filename, $userId]);
        echo "Sikeres feltöltés!";
    } else {
        echo "Hiba a feltöltéskor.";
    }

}*/
?>