<?php
  // File di connessione al DataBase
  date_default_timezone_set('Europe/Rome');
  // Sito altervista: esperiasmartbox.altervista.org
  // Nome server
  $servername = "localhost";
  // Username
  $username = "root";
  // Password
  $pwd_db="";
  // Nome DataBase
  $database = "my_esperiasmartbox";
  // Connessione al DataBase
  $conn = new mysqli($servername, $username, $pwd_db,$database);
  // Definizione della codifica caratteri
  mysqli_set_charset($conn,"utf8");
?>
