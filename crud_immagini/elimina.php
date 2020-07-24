<?php
  // Recupero il nome del file da eliminare
  $filename = $_POST['delete_file'];

  // Se il file esiste
  if (file_exists("../img/aule/" . $filename))
  {
    // Elimino il file
    unlink("../img/aule/" . $filename);
    echo 'File '.$filename.' has been deleted';
  }
  else
    echo 'Could not delete '.$filename.', file does not exist';

  // Elimino i dati della foto anche dal DataBase
  // Connessione al DataBase
  require "../conn/conn.php";

  // Compongo la query
  $sql= "DELETE FROM foto WHERE pathImmagine = '" . $filename;

  // Se c'è un errore nell'esecuzione della query
  if(!mysqli_query($con,$sql))
    die('Error: ' . mysqli_error($con));

  // Chiudo la connssione
  mysqli_close($con);

  // Redirect all'homepage
  header("location: ./index.php");
?>
