<?php
  // File di connessione al DataBase
  date_default_timezone_set('Europe/Rome');
  // Sito altervista: esperiasmartbox.altervista.org
  // Nome server
  $servername = "SERVER_NAME";
  // Username
  $username = "USERNAME";
  // Password
  $pwd_db="PASSWORD";
  // Nome DataBase
  $database = "DATABASE_NAME";
  // Connessione al DataBase
  $conn = new mysqli($servername, $username, $pwd_db,$database);
  // Definizione della codifica caratteri
  mysqli_set_charset($conn,"utf8");
?>
