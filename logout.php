<?php
  // Avvio sessione
  session_start();

  // Inizializzazione variabile per errori
  $app = "";
  $eccezione = false;

  if (isset($_SESSION['eccezione_google']) && $_SESSION['eccezione_google'] != "")
  {
    // Salvo l'errore
    $app = $_SESSION['eccezione_google'];
    $eccezione = true;
  }
  // Se c'è un l'errore del dominio della mail (Accesso Google)
  elseif(isset($_SESSION['erroreGoogle']) && $_SESSION['erroreGoogle'] != "")
    // Salvo l'errore
  	$app = $_SESSION['erroreGoogle'];
  // Richiamo il file di configurazione dell'API per l'accesso a Google
  require_once "login/config.php";

  // Elimino il token di accesso Google dalla variabile di sessione
  unset($_SESSION['access_token']);

  // Elimino il token di accesso dal client Google
  $gClient->revokeToken();

  // Distruggo le variabili di sessione
  unset($_SESSION);
  // Distruggo la sessione
  session_destroy();

  // Avvio una nuova sessione
  session_start();

  if($eccezione)
    $_SESSION['eccezione_google'] = $app;
  else
    // Recupero l'errore di accesso a Google
    $_SESSION['erroreGoogle'] = $app;

  // Redirect all'homepage => Pagina di login
  header("location: ./");
  die();
?>
