<?php
  // Connessione al DataBase
  require '../conn/conn.php';

  // Avvio sessione
  session_start();

  // Se l'utente è loggato
  if(isset($_SESSION['log']) && $_SESSION['log']=="CON")
  {
    // Se l'utente è autenticato con Google
    if(isset($_SESSION['token_accesso']) && $_SESSION['token_accesso']!="")
    {
      // Recupero il token di accessi dalla variabile di sessione
      $token=$_SESSION['token_accesso'];
      // Recupero il cognome dell'utente
      $cognome_spazi=ucwords(strtolower($_SESSION['cognome']));
    }
    else
    {
      // Utente autenticato con cognome.matricola e password
      // Recupero i dati dell'utente
      // Matricola
      $matricola=$_SESSION['matricola'];
      // Cognome
      $cognome_spazi=ucwords(strtolower($_SESSION['cognome_spazi']));
      // Classe
      $classe=$_SESSION['classe'];
      // Privilegi
      $potere=$_SESSION['tipo'];
    }
    // Sistemo Nome e Cognome (Iniziale maiuscola)
    $cognome=ucwords(strtolower($_SESSION['cognome']));
    $nome=ucwords(strtolower($_SESSION['nome']));
  }
  else
  {
    // Redirect alla pagina di login
    header("location: ../login/");
    die();
  }

  // Se la richiesta alla pagina arriva con metodo POST
  if($_SERVER['REQUEST_METHOD']=="POST")
  {
    // Estraggo i dati passati dalla form
    // Data prenotazione
    $giorno = mysqli_real_escape_string($conn,$_POST['giorno']);
    // Ora di inizio prenotazione
    $orainizio = mysqli_real_escape_string($conn,$_POST['orainizio']).":00";
    // Ora di fine prenotazione
    $orafine=mysqli_real_escape_string($conn,$_POST['orafine']).":00";
    // Aula scelta
    $idAula=mysqli_real_escape_string($conn,$_POST['aula']);
    // Numero di posti prenotati
    $posti=mysqli_real_escape_string($conn,$_POST['numero_posti']);
    // Se vuoto lo metto a 1
    if($posti=="")
    	$posti=1;
    // Id utente (Dalla sessione)
    $idUtente=$_SESSION['idUtente'];

    // Query per controllare se l'aula è sospesa nell'intervallo scelto
    $query="SELECT count(idAula) AS conta FROM sospendiAula WHERE idAula = $idAula AND ('$giorno' BETWEEN sospendiDa AND sospendiA)";
    $ris=$conn->query($query);
    $tutto=$ris->fetch_assoc();
    $quanti=$tutto['conta'];

    // Se non è sospesa
    if($quanti == 0)
    {
      // Query per controllare che l'aula non sia già prenotata dall'utente
      $query="SELECT id FROM richieste WHERE data='$giorno' and ora_inizio='$orainizio' and ora_fine='$orafine' and idUtente='$idUtente' and idAula='$idAula'";
      $risu=$conn->query($query);

      // Se non è già prenotata
      if($risu->num_rows==0)
      {
        // Query per inserire la prenotazione nel Database
        $queryInserimento="INSERT INTO richieste (data,ora_inizio,ora_fine,idUtente,idAula, posti)
                           VALUES ('$giorno','$orainizio','$orafine','$idUtente','$idAula', $posti)
                          ";
        $risultato=$conn->query($queryInserimento);

        // Banner che comunicano la riuscita o meno dell'operazione
        if($risultato)
          $_SESSION['inserimentoRichiesta'] = "<div class='alert alert-success text-center' role='alert'><strong>OTTIMO</strong>: Operazione conclusa con successo</div>";
        else
          $_SESSION['inserimentoRichiesta'] = "<div class='alert alert-danger text-center' role='alert'><strong>ATTENZIONE</strong>: Operazione non riuscita, riprova tra qualche minuto</div>";
      }
      else
        // Banner per dire che ha già prenotato l'aula per quel giorno-ora
        $_SESSION['inserimentoRichiesta'] = "<div class='alert alert-danger text-center' role='alert'><strong>ATTENZIONE</strong>: Nel database è già presente una tua richiesta per quest'aula</div>";

    }
    else
      // Banner per comunicare che l'aula è sospesa
      $_SESSION['inserimentoRichiesta'] = "<div class='alert alert-danger text-center' role='alert'><strong>ATTENZIONE</strong>: La prenotazione di quest'aula è stata sospesa per la data indicata.</div>";
  }

  // Redirect alla pagina di prenotazione
  header("location: ./");

?>
