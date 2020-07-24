<?php
	//
	// Pagina di configurazione dell'accesso tramite l'API di Google
	//

	// Avvio la sessione
	session_start();

	require "GoogleAPI/vendor/autoload.php";

	// Creo un nuovo client Google
	$gClient = new Google_Client();
	// Setto Id e Chiave dell'API
	$gClient->setClientId("143110333444-1abf0k5c4kpadnbgtbvlk937gcpvoa4u.apps.googleusercontent.com");
	$gClient->setClientSecret("wA5Dn6txJeA_I7SKHIOzA1Dr");

	// Nome applicazione (Secondario)
	$gClient->setApplicationName("SmartBox");

	// Indirizzo di redirect della callback
	// Dopo aver effettuato l'accesso a Google ed aver accettato le condizioni
	// viene eseguito il redirect ad una pagina intermedia tra quella di Login
	// e la landing page. Vengono passati in GET vari parametri tra cui il token di accesso
	$gClient->setRedirectUri("http://esperiasmartbox.altervista.org/login/callback.php");
	// Scopes dell'applicazione. Utili per la richiesta di consenso dell'utente
	$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
?>
