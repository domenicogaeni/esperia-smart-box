<?php
  // Avvio sessione()
  session_start();

  // Prendo i dati inviati dalla form

  // Data di inizio del filtro
  if(isset($_POST['inputDa']))
    $Da = $_POST['inputDa'];

  // Data di fine del filtro
  if(isset($_POST['inputA']))
    $A = $_POST['inputA'];

  // Id aula da sospendere
  if(isset($_POST['bottone']))
    $aula = $_POST['bottone'];

  // Motivo della sospensione
  if(isset($_POST['motivo_' . $aula]))
    $motivo = $_POST['motivo_' . $aula];

  //

  require '../conn/conn.php';

  // Controllo se l'aula è già stata sospesa nel periodo scelto
  $query="SELECT count(idAula) AS conta FROM sospendiAula WHERE idAula = $aula AND ('$Da' >= sospendiDa AND '$A' <= sospendiA)";
  $ris=$conn->query($query);
  $tutto=$ris->fetch_assoc();
  $quanti=$tutto['conta'];

  // Se non è stata gi sospesa
  if($quanti=="0")
  {
    // Inserisco la sospensione
    $sql="INSERT INTO sospendiAula (idAula,sospendiDa,sospendiA, motivazione) VALUES ($aula, '$Da', '$A', '$motivo')";
    echo $sql;
    $ris=$conn->query($sql);

    // Estraggo, se ci sono, le prenotazioni per l'aula sospesa
    $sql="SELECT utenti.email, richieste.data, aule.nome
          FROM utenti
          INNER JOIN richieste ON utenti.id = richieste.idUtente
          INNER JOIN aule ON richieste.idAula = aule.id
          WHERE richieste.data BETWEEN '$Da' AND '$A' AND richieste.idAula = $aula
          ";

    $ris=$conn->query($sql);
    // Numero di righe estratte
    $righe = $ris->num_rows;

    // Se ci sono prenotazioni nel periodo di sospensione
    if($righe != 0)
      while($tutto=$ris->fetch_assoc())
      {
        // Invio email a chi ha prenotato
        $useremail = $tutto['email'];

        $_SESSION['email_inviata'] = 'SI';

        require ("../email/sendgrid-php/sendgrid-php.php");

        $sendgrid = new SendGrid("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");
        $email = new SendGrid\Email();
        $email->addTo("$useremail")
              ->setFrom("no-reply@esperiasmartbox.org")
              ->setFromName("Esperia SmartBox")
              ->setSubject("SmartBox - Prenotazione annullata")
              ->setHtml('La tua prenotazione dell\'aula ' . $tutto['nome'] . ' per il giorno ' . $tutto['data'] . ' è stata annullata.');
        $sendgrid->send($email);

        // Elimino le prenotazioni dell'aula nel periodo in cui viene sospesa
        $sql="DELETE FROM richieste
              WHERE richieste.data BETWEEN '$Da' AND '$A' AND richieste.idAula = '$aula'
              ";

        $risultato=$conn->query($sql);
      }

    // Variabile di sessione per confermare la sospensione tramite banner
    $_SESSION['sospesa'] = "OK";

    // Redirect alla pagina per sospendere le aule
    header('Location: sospendi_aule.php');
  }
  else
  // Altrimenti
  {
    // Variabile di sessione per comunicare l'errore delle date tramite banner
    $_SESSION['err_intervello'] = "OK";

    // Redirect alla pagina per sospendere le aule
    header('Location: sospendi_aule.php');
  }
?>
