<?php
	//
	// Pagina che riceve la risposta dalla API di accesso tramite Account Google
	//

	// Richiamo il file di configurazione dell'API Google
	require "config.php";
	// Connessione al DataBase
	require "../conn/conn.php";

	// Se il token di accesso (dato da Google) è già salvato nella sessione
	if (isset($_SESSION['token_accesso']))
		// Assegno il token al "profilo" dell'utente
		$gClient->setAccessToken($_SESSION['token_accesso']);
	else
		// Altrimenti, se presente, lo prende in GET dall'URL
		if (isset($_GET['code']))
		{
			// Assegno il token al "profilo" dell'utente
			$token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
			// Salvo il token nella variabile di sessione
			$_SESSION['token_accesso'] = $token;
		}
		else
		{
			// Se non c'è il token di accesso nell'URL, l'utente viene
			// redirezionato alla pagina iniziale di Login
			header('Location: index.php/');
			exit();
		}
/*
	***************************************************
	*				DATI DELL'UTENTE (FROM GOOGLE)						*
	***************************************************
*/
	$oAuth = new Google_Service_Oauth2($gClient);
	$userData = $oAuth->userinfo_v2_me->get();

	// Id
	$_SESSION['idGoogle'] = $userData['id'];
  $idGoogle=$userData['id'];
	// Email
	$_SESSION['email'] = $userData['email'];
  $email=$userData['email'];
	// Cognome
  $_SESSION['cognome'] = $userData['familyName'];
  $cognome=$userData['familyName'];
	// Nome
	$_SESSION['nome'] = $userData['givenName'];
  $nome=$userData['givenName'];
  	// Tipo utente (Docente)
	$_SESSION['tipo'] = 1;


	// Estraggo il dominio dalla email
	$domain=explode("@",$_SESSION['email'])[1];

	// Se il dominio è itispaleocapa.it
	if($domain == "itispaleocapa.it")
	{
		// Query per controllare che l'utente sia registrato o no
		$query="SELECT id FROM utenti WHERE cognome='$cognome' and nome='$nome' and email='$email' and tipo='1'";
		$risultato=$conn->query($query);

		// Se l'utente non esiste nel DataBase
		if(mysqli_num_rows($risultato) == 0)
		{
			// Inserisco l'utente nella tabella
			$query="INSERT INTO utenti (cognome,nome,email,tipo) VALUES ('$cognome','$nome','$email','1')";
			$riso=$conn->query($query);

			// Se c'è un errore nella creazione dell'utente
			if(!$riso)
			{
				$_SESSION['erroreGoogle']="<div class='alert alert-danger' role='alert' style='font-size:18px; text-align:center;'><strong>Attenzione!</strong> C'è stato qualche problema... Riprova tra qualche minuto</div>";
				header('Location: ../logout.php');
				die();
			}
			else
			{
				// Recupero l'id dell'utente
				$query="SELECT id FROM utenti WHERE cognome='$cognome' and nome='$nome' and email='$email' and tipo='1'";
				$risa=$conn->query($query);
				$tutto=$risa->fetch_assoc();
				// Lo inserisco nelle variabli di sessione
				$_SESSION['idUtente']=$tutto['id'];
				$_SESSION['tipo']='1';
			}
		}
		else
		// Se è già registrato
		{
			$tutto=$risultato->fetch_assoc();
			// Salvo l'id in una variabiel di sessione
			$_SESSION['idUtente']=$tutto['id'];
		}

		// Setto la variabile di sessione che conferma il login
		$_SESSION['log'] = "CON";

		// Redirect alla homepage
		header('Location: ../');
		die();
	}
  else
	// Mail non di dominio itispaleocapa.it
  {
		// Nella variabile di sessione inserisco il banner di errore
  	$_SESSION['erroreGoogle']="<div class='alert alert-danger' role='alert' style='font-size:18px; text-align:center;'><strong>Attenzione!</strong> Per accedere devi avere una email con il dominio <i>itispaleocapa.it</i></div>";
		// Redirezione alla pagina di logout => Pagina di login
		header('Location: ../logout.php');
    die();
  }
?>
