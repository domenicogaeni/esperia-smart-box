<?php
  //
  // Pagina per l'aggiornamento della password dell'utente
  //

  // Avvio la sessione
  session_start();

  // Connessione al DataBase
  require '../../conn/conn.php';

  // Se viene passata la password in POST
  if(isset($_POST['password']))
    // La recupero
    $password = $_POST['password'];

  // Cripto la nuova password
  $hash_password = hash_hmac("sha256",$password,$_SESSION['matricola']);

  // Query per aggiornare la password nel DataBase
  $sqlquery="UPDATE utenti SET password = '$hash_password' WHERE matricola = '" . $_SESSION['matricola'] . "'";

  $result = mysqli_query($conn,$sqlquery);

  // Variabile di sessione per comunicare all'utente
  // la riuscita dell'operazione
  $_SESSION['password_aggiornata'] = "SI";

  // Redirect alla pagina di login
  header('Location: ../');
?>
