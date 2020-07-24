<?php
  //
  // Pagina per l'invio via mail del link per il reset della password
  //

  // Connessione al Database
  require '../../conn/conn.php';
  // Libreria per l'invio delle email
  require '../../email/mandaEmail.php';

  // Avvio sessione
  session_start();

  // Se nella sessione esiste la variabile che contiene la matricola
  if(isset($_SESSION['matricola']))
  {
    // La estraggo
    $matricola = $_SESSION['matricola'];

    // Compongo la query per estrarre l'email dell'utente con quella matricola
    $sqlquery="SELECT email FROM utenti WHERE matricola = '$matricola'";
    $result = mysqli_query($conn,$sqlquery);
    $tutto=mysqli_fetch_assoc($result);
    $email = $tutto['email'];

    // Recupero dalle variabili di sessione Nome e Cognome dell'utente
    $user = ucfirst($_SESSION['nome']) . " " . ucfirst($_SESSION['cognome']);

    // Ricavo la matricola dell'utente crittografata
    $hash_matricola = hash("sha256", $_SESSION['matricola']);

    // Setto la variabile di sessione che viene utilizzata
    // per comunicare all'utente la riuscita dell'operazione
    $_SESSION['email_inviata'] = 'SI';

    // Invio l'email
    sendMail($user, $email, $hash_matricola);

    // Redirect alla pagina di login
    header('Location: ../');
  }
?>
