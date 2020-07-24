<?php
  // Connessione al DataBase
  require '../conn/conn.php';

  // Recupero i valori passati in GET
  // Id Aula
  if(isset($_GET['aula']))
    $aula = $_GET['aula'];
  // Nome dell'aula
  if(isset($_GET['nome']))
    $nome = $_GET['nome'];
  // Descrizione dell'aula
  if(isset($_GET['descrizione']))
    $descrizione = $_GET['descrizione'];
  // Posti disponibili per l'aula
  if(isset($_GET['posti']))
    $posti = $_GET['posti'];
  // Note alla descrizione dell'aula
  if(isset($_GET['note']))
    $note = $_GET['note'];

  // Query per modificare chi può prenotare
  $query = "UPDATE `aule`
            SET nome = '$nome', descrizione = '$descrizione', numeroPosti = $posti, note = '$note'
            WHERE `id`= $aula
            ";
  // Eseguo la query
  $risultato=$conn->query($query);
?>
