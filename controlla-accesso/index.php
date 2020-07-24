<?php
  //
  // Controllo dell'utente loggato
  //

  // Avvio sessione
  session_start();

  // Se l'utente ha effettuato il login
  if(isset($_SESSION['log']) && $_SESSION['log']=="CON")
  {
    // Se l'utente ha effettuato il login con Google
    if(isset($_SESSION['token_accesso']) && $_SESSION['token_accesso']!="")
    {
      // Recupero dei dati dell'utente
      // Token di accesso Google
      $token=$_SESSION['token_accesso'];
      // Cognome dell'utente
      $cognome_spazi=ucwords(strtolower($_SESSION['cognome']));
      // Provilegi dell'utente
      $potere=$_SESSION['tipo'];
    }
    else
    {
      // Se l'utente ha effettuato il login con cognome.matricola e password
      // Recupero dei dati dell'utente
      // Matricolad dell' utente
      $matricola=$_SESSION['matricola'];
      // Cognome dell'utente
      $cognome_spazi=ucwords(strtolower($_SESSION['cognome_spazi']));
      // Classe dell'utente
      $classe=$_SESSION['classe'];
      // Provilegi dell'utente
      $potere=$_SESSION['tipo'];
    }

    // Cognome dell'utente
    $cognome=ucwords(strtolower($_SESSION['cognome']));
    // Nome dell'utente
    $nome=ucwords(strtolower($_SESSION['nome']));
  }
  else
  {
    // Altrimenti lo mando alla pagina di login
    header("location: ../login/");
    die();
  }
?>
