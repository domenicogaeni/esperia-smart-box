<?php
  // Connessione al DataBase
  require '../conn/conn.php';

  // Recupero i valori passati in GET
  // Tipo di utente
  if(isset($_GET['tipo']))
    $tipo = $_GET['tipo'];
  // Aula
  if(isset($_GET['aula']))
    $aula = $_GET['aula'];
  // Valore
  if(isset($_GET['valore']))
    $valore = $_GET['valore'];

  // Query per modificare chi può prenotare
  $query = "UPDATE `aule` SET ";

  // In base alla Checkbox cliccata compongo la condizione
  if($tipo == "S")
    $query.= "studenti = ";
  else
    $query.= "docenti = ";

  // Setto il valore
  $query.= $valore;

  // Creo la condizione WHERE con l'aula da modificare
  $query.= " WHERE `id`= $aula";

  // Eseguo la query
  $risultato=$conn->query($query);
?>
