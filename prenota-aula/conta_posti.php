<?php
  // Connessione al DataBase
  require '../conn/conn.php';

  // Recupero i dati passati in GET
  // Data prenotazione
  if(isset($_GET['data']))
    $data = $_GET['data'];
  // Ora inizio prenotazione
  if(isset($_GET['orainizio']))
    $orafine = $_GET['orainizio'];
  // Ora fine prenotazione
  if(isset($_GET['orafine']))
    $orainizio = $_GET['orafine'];
  // Aula prenotata
  if(isset($_GET['aula']))
    $aula = $_GET['aula'];

  // Costruzione della query per controllare che non ci siano altre prenotazioni per l'aula
  $query="SELECT SUM(posti) as conta FROM richieste
          WHERE ora_inizio < '$orafine' AND ora_fine > '$orainizio' AND data = '$data' AND idAula=$aula";

  $ris = $conn->query($query);
  $riga = $ris -> fetch_assoc();

  if($riga['conta'] == "")
    $conta = 0;
  else
    $conta = $riga['conta'];

  $query="SELECT numeroPosti as posti FROM aule
          WHERE id = $aula";

  $ris = $conn->query($query);
  $riga = $ris -> fetch_assoc();

  $posti = $riga['posti'] - $conta;

  if($posti >= 1)
    echo "<option value = '' disabled>Seleziona il numero di posti</option>";
    echo "<option value = '1' selected>1</option>";

  for ($i = 2; $i <= $posti; $i++)
    echo "<option value = '$i'>$i</option>";
?>
