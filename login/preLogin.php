<?php
  // Connessine al DataBase
  require '../conn/conn.php';

  // Avvio la sessione
  session_start();

  // Recupero i dati dell'utente dalle variabili di sessione
  // Cognome
  $cognome = ucwords(strtolower($_SESSION['cognome']));
  // Nome
  $nome = ucwords(strtolower($_SESSION['nome']));
  // Cognome con spazi (Cognomi multipli)
  $cognome_spazi = ucwords(strtolower($_SESSION['cognome_spazi']));
  // Matricola
  $matricola = $_SESSION['matricola'];

  // Se la richiesta alla pagina arriva con metodo POST
  if($_SERVER['REQUEST_METHOD']=="POST")
  {
    // RICHIESTA PROVENIENTE DA 'benvenuto.php' --> Devo settare la password nel Database
    // Se vengono passati in POST Email e Password (con le loro conferme)
    if(isset($_POST['pwd']) && isset($_POST['pwd_conf']) && isset($_POST['email']) && isset($_POST['email_conf']))
    {
      // Estraggo i dati dalle variabili di POST
      // Password
      $password=mysqli_real_escape_string($conn, $_POST['pwd']);
      // Conferma password
      $password_conf=mysqli_real_escape_string($conn, $_POST['pwd_conf']);
      // Email
      $email=mysqli_real_escape_string($conn, $_POST['email']);
      // Conferma Email
      $email_conf=mysqli_real_escape_string($conn, $_POST['email_conf']);

      // Se Email e Password coincidono con le loro conferme
      if($email == $email_conf && $password == $password_conf)
      {
        // Cripto la password
        $password = hash_hmac("sha256",$password,$matricola);
        // Query per settare Email e Password dell'utente nel DataBase
        $sqlquery="UPDATE utenti SET password='$password', email='$email' WHERE REPLACE(cognome,' ','')='$cognome' AND matricola='$matricola';";
        // Eseguo la query
        $result = mysqli_query($conn,$sqlquery);
      }
    }
    // RICHIESTA PROVENIENTE DA 'password.php' --> Significa che ha già settato una password
    // Se viene passata in POST la Password
    if(isset($_POST['password']))
    {
      // Estraggo la password
      $password=mysqli_real_escape_string($conn, $_POST['password']);
      // La cripto
      $password= hash_hmac ( "sha256" , $password, $matricola);
      // Query per estrarre la password (corretta) dell'utente
      $sqlquery="SELECT password FROM utenti WHERE REPLACE(cognome,' ','')='$cognome' AND matricola='$matricola';";
      $result = mysqli_query($conn,$sqlquery);
    	$esiste = mysqli_num_rows($result);

      // Se non esiste
      if($esiste == 0)
      {
        // Redirect alla pagina di login
        header("location: ../login/");
        die();
      }
      else
      {
         $tutto=mysqli_fetch_assoc($result);
         // Estraggo la password dai risultati della query
         $password_vera = $tutto['password'];

         // Se è diversa da quella inserita nella form (Passata in POST)
         if($password_vera!=$password)
         {
           // Password sbagliata

           // Flag di errore nelle variabili di sessione
           $_SESSION['errore_password']="SI";
           // Redirect alla pagina per l'inserimanto della password di accesso
           header("location: ../login/password.php");
           die();
         }

         //
         // UTENTE AUTENTICATO
         //

         // Query per recuperare dal DataBase i dati dell'utente
         $sqlquery="SELECT id,nome,classe,tipo FROM utenti WHERE REPLACE(cognome,' ','')='$cognome' AND matricola='$matricola'  AND password='$password_vera';";
         $result = mysqli_query($conn,$sqlquery);
         $roba=mysqli_fetch_assoc($result);

         // Classe
         $classe=$roba['classe'];
         // Privilegi
         $potere=$roba['tipo'];

         // Setto i valori nelle variabili di sessione
         // Privilegi
         $_SESSION['tipo']=$potere;
         // Classe
         $_SESSION['classe']=$classe;
         // Flag per comunicare che l'utente è autenticato correttamente
         $_SESSION['log']="CON";
         // Id
         $_SESSION['idUtente']=$roba['id'];;
      }
    }
  }
  $conn->close();

  // Redirect all'homepage
  header("location: ../index.php");
  die();
 ?>
