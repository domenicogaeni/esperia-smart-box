<?php
	// Recupero l'id dell'aula
	$id=$_POST["id"];
	// Cartella dove verranno salvate le immagini
	$target_dir = "../img/aule/";
	// Ciclo sui file caricati dall'utente
  foreach($_FILES['file']['name'] as $key=>$val)
	{
		// Creo l'indirizzo assoluto delle immagini
		// Nome immagine
	  $indirizzo = basename($_FILES["file"]["name"][$key]);
		// Includo il nome dell'immagine all'indirizzo della cartella definita sopra
		$target_file = $target_dir . $indirizzo;
		// Flag per il check del caricamento delle immagini
		$uploadOk = 1;
		// Estraggo l'estensione del file caricato
	  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		// Controllo che il file caricato sia un'immagine
		if(isset($_POST["submit"]))
		{
			$check = getimagesize($_FILES["file"]["tmp_name"][$key]);
			if($check !== false)
				$uploadOk = 1;
			else
			{
				echo "File is not an image.";
				$uploadOk = 0;
			}
		}

		// Controllo che il file non esista già
		if (file_exists($target_file))
		{
	    echo "Sorry, file already exists.";
	    $uploadOk = 0;
		}

		// Se ci sono errori
		if ($uploadOk == 0)
			echo "Sorry, your file was not uploaded.";
		else
		{
			// Sposto i file caricati nella cartella designata
    	if (move_uploaded_file($_FILES["file"]["tmp_name"][$key], $target_file))
			{
      	echo "The file ". basename( $_FILES["file"]["name"][$key]). " has been uploaded.";

				// Connessione al DataBase
        require "../conn/conn.php";
				
        // Inserisco i dati delle immagini nel DataBase
				// Conpongo la query
        $sql="INSERT INTO foto (pathImmagine, idAula)
          		VALUES ('" . $indirizzo . "','" . $id . "');";

				// Se c'è un errore nell'esecuzione della query
        if (!mysqli_query($con,$sql))
        	die('Error: ' . mysqli_error($con));

				// Chiudo la connessione
				mysqli_close($con);
			}
			else
      	echo "Sorry, there was an error uploading your file.";
      }
   }

	 // Redirect all'homepage
   header("location: ./index.php");
?>
