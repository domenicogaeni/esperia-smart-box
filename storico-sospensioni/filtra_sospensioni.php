<?php
  // Connessione al Database
  require '../conn/conn.php';

  // Recupero i dati dalla form
  // Data di inizio del filtro
  if(isset($_GET['datai']))
    $datai = $_GET['datai'];

  // Data di fine del filtro
  if(isset($_GET['dataf']))
    $dataf = $_GET['dataf'];

  // Aula da filtrare
  if(isset($_GET['aula']))
    $aula = $_GET['aula'];

  // Flag Tutte/In corso
  if(isset($_GET['tutte']))
    $tutte = $_GET['tutte'];

  // Prendo già la data successiva a quella scelta
  $oggi = date("Y-m-d");

  //
  // Query
  //

  // Base
  $query="SELECT aule.nome AS nomeAula, sospendiAula.idAula AS id, sospendiAula.sospendiDa AS inizio, sospendiAula.sospendiA AS fine, sospendiAula.motivazione AS motivo
          FROM sospendiAula
          INNER JOIN aule on aule.id=idAula
         ";

  // WHERE
  // Se devo filtrare per aula o per intervallo di date
  if($aula != '-1' || $datai != "")
  {
    // Aggiungo la condizione WHERE
    $query.= "WHERE ";

    // Se devo filtrare per aula
    if($aula != '-1')
      // Aggiungo la condizione
      $query.= "aule.id=$aula";

    // Se devo intervallo di date
    if($datai != "")
        // Aggiungo la condizione
        $query.= " AND sospendiAula.sospendiDa >= $datai AND sospendiAula.sospendiA <= $dataf";
  }

  // Elimino le sospensioni passate
  if($tutte == "false")
    $query.= " AND sospendiAula.sospendiDa >= '$oggi'";

  // Finale
  $query.= " ORDER BY inizio DESC
            LIMIT 30";

  $risultato=$conn->query($query);

  //
  // Output dati
  //

  if($risultato->num_rows!=0)
  {
    echo "<table class='table'>";
    // Intestazione tabella
    echo "<tr>
          <th>#</th>
          <th>Data inizio</th>
          <th>Data Fine</th>
          <th>Aula</th>
          <th>Motivazione</th>
          <th>Elimina</th>
          </tr>
        ";

    $i=1;
    // Ciclo sui risultati della query
    // Output delle righe dela tabella
    while($riga = $risultato->fetch_assoc())
    {
      echo "<tr>";
      // #
      echo "<td>" . $i. "</td>";
      // Data di inizio sospensione
      echo "<td>" . date("d-m-Y",strtotime($riga['inizio'])). "</td>";
      // Data di fine sospensione
      echo "<td>" . date("d-m-Y",strtotime($riga['fine'])). "</td>";
      // Nome aula sospesa
      echo "<td>" . ucwords(strtolower($riga['nomeAula'])) . "</td>";
      // Motivo sospensione
      echo "<td>" . ucwords(strtolower($riga['motivo'])) . "</td>";
      // Pulsante per eliminare la sospensione
      echo "<td>";
      // Se la sospensione è in corso o deve ancora cominciare
      if($oggi < $riga['fine'])
        // Inserisco il bottone per cancellare la sospensione
        echo '<button type="button" class="btn btn-danger" id="cancella" value="' . $riga['id'] . '|' . $riga['inizio'] . '" onclick="cancella_sospensione(this.value);">x</button>';
      else
        echo "/";
      echo "</td>";
      echo "</tr>";
      $i++;
    }

    echo "</table>";
  }
  else
    echo "Non ci sono sospensioni";

  function isMobileDevice()
  {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
  }
?>
