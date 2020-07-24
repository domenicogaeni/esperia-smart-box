<?php
  // Connessione al Database
  require '../conn/conn.php';

  // Avvio sessione
  session_start();

  // Estraggo dalle variabili di sessione i privilegi dell'utente
  $potere = $_SESSION['tipo'];

  // Prendo già la data attuale
  $oggi = date("Y-m-d");

  // Recupero i dati dalla form
  // Data di inizio del filtro
  if(isset($_GET['data']))
    $data = $_GET['data'];

  // Aula da filtrare
  if(isset($_GET['aula']))
    $aula = $_GET['aula'];

  // Data di fine del filtro
  if(isset($_GET['persona']))
    $persona = $_GET['persona'];

  // Flag Tutte/In corso
  if(isset($_GET['tutte']))
    $tutte = $_GET['tutte'];

  // Query per filtrare le richieste
  $query = "SELECT richieste.id as id, richieste.data AS data, richieste.ora_inizio AS inizio, richieste.ora_fine AS fine, aule.nome AS aula";

  // Aggiungo il tipo di utente che ha prenotato
  // se la richiesta è fatta da un admin
  if($potere == 2)
    $query.= ", utenti.nome AS nome, utenti.cognome AS cognome";

  $query .= " FROM richieste
              INNER JOIN aule on richieste.idAula = aule.id
              INNER JOIN utenti on utenti.id = richieste.idUtente
            ";

  // Inizializzazione dell variabile per aggiungere gli AND
  $prima = false;

  // Se la richiesta non viene fatta da un admin
  if($potere != 2)
    // Filtro le richeste solo dell'utente che ha fatto la richiesta
    $query.= " WHERE utenti.id = " . $_SESSION['idUtente'];
  // Altrimenti se almeno un parametro ha valore settato
  elseif($data != "" || $aula != -1 || $persona != -1 || !$tutte)
  {
    // Aggiungo la condizione WHERE
    $query.= " WHERE";

    // Se devo filtrare per data di prenotazione
    if($data != "")
    {
      $query.= " richieste.data = '$data'";
      $prima = true;
    }

    // Se devo filtrare per aula prenotta
    if($aula != -1)
    {
      if($prima)
        $query.= " AND";

      $query.= " aule.id = $aula";
      $prima = true;
    }

    // Se devo filtrare per tipo di utente che ha prenotato
    if($persona != -1)
    {
      if($prima)
        $query.= " AND";

      $query.= " utenti.tipo = $persona";
      $prima = true;
    }
  }

  // Se devo filtrare solo le prenotazioni in corso
  if(!$tutte)
  {
    if($potere != 2 || $prima)
      $query.= " AND";

    $query.= " richieste.data >= '$oggi'";
  }

  // Finale
  $query.= " ORDER BY data DESC
            LIMIT 30
            ";

  $risultato=$conn->query($query);

  //
  // Output dati
  //
  if($risultato->num_rows != 0)
  {
    echo "<table class='table'>";

    // Intestazione tabella
    echo "<tr>
          <th>#</th>
          <th>Data</th>
          <th>Ora Inizio</th>
          <th>Ora Fine</th>
          <th>Aula</th>
         ";
    // Se la richiesta viene fatta da un admin
    if($potere == 2)
    {
      // Aggiungo l'intestazione per il tipo di
      // utente che ha prenotato
      echo "<th>Nome</th>";
      echo "<th>Cognome</th>";
    }

    echo "<th>Elimina</th>";

    echo "</tr>";

    $i=1;
    // Ciclo sui risultati dell query
    while($riga = $risultato->fetch_assoc())
    {
      echo "<tr>";
      // #
      echo "<td>" . $i. "</td>";
      // Data prenotazione
      echo "<td>" . date("d-m-Y",strtotime($riga['data'])) . "</td>";
      // Data di inizio prenotazione
      echo "<td>" . date("H:i",strtotime($riga['inizio'])) . "</td>";
      // Data di fine prenotazione
      echo "<td>" . date("H:i",strtotime($riga['fine'])) . "</td>";
      // Aula prenotata
      echo "<td>" . $riga['aula'] . "</td>";

      // Se la richiesta viene fatta da un admin
      if($potere == 2)
      {
        // Stampo anche il nome dell' utente
        echo "<td>";
          echo $riga['nome'];
        echo "</td>";

        // Stampo anche il cognome dell' utente
        echo "<td>";
          echo $riga['cognome'];
        echo "</td>";
      }

      // Bottone per eliminare la richiesta
      echo "<td>";

      if(date("Y-m-d") < $riga['data'])
        // Inserisco il bottone per cancellare la sospensione
        echo '<button type="button" class="btn btn-danger" id="cancella" value="' . $riga['id'] . '|' . $riga['data'] . '" onclick="cancella_richiesta(this.value);">x</button>';
      else
        echo "/";
        
      echo "</td>";

      echo "</tr>";
      $i++;
    }

    echo "</table>";
  }
  else
    echo "Non ci sono richieste";
?>
