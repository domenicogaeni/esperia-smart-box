<?php
	// Connessione al DataBase
	require '../conn/conn.php';

	// Recupero i dati passati in GET
	// Data prenotazione
	$data=mysqli_real_escape_string($conn,$_GET['data']);
	// Ora di inizio prenotazione
	$orainizio=mysqli_real_escape_string($conn,$_GET['orainizio']) .":00";
	// Ora di fine prenotazione
	$orafine=mysqli_real_escape_string($conn,$_GET['orafine']).":00";
	// Aula scelta (Prenotazione dalla homepage)
	$seleziona_aula=mysqli_real_escape_string($conn,$_GET['aula']);
	// Aula scelta (Prenotazione dalla homepage)
	$tipo=mysqli_real_escape_string($conn,$_GET['tipo']);

	// Costruzione ed esecuzione della query per trovare le aule sospese nella data scelta
	$query="SELECT idAula FROM sospendiAula WHERE '$data' BETWEEN sospendiDa and sospendiA";
	$risultato=$conn->query($query);

	// Inizializzazione variabili
	$sospese="";
	$prima=false;

	// Ciclo sulle aule sospese
	// Le utilizzo per filtrare le aule disponibili
	// Costruzione della condizione NOT IN inserita nel WHERE della query successiva
	while($sospesa = $risultato->fetch_assoc())
	{
		if($prima)
			$sospese .= ",";
		else
			$prima = true;

		$sospese .= $sospesa['idAula'];
	}

	// Costruzione ed esecuzione della query per ottenere le aule disponibili
	$query="SELECT id,numeroPosti,nome FROM aule ";

	if($tipo == 0)
		$query.= "WHERE studenti = 1 ";
	elseif ($tipo == 1)
		$query.= "WHERE docenti = 1 ";

	// Se qualche aula è sospesa aggiungo la condizione
	if($risultato->num_rows != 0)
		if($sospese != "")
		{
			if($tipo != 2)
				$query.=" AND";
			else
				$query.=" WHERE";
			$query.=" id NOT IN ($sospese) ";
		}

	$query.="ORDER BY nome";

	$risultato=$conn->query($query);

	// Costruzione dell'output
	if($risultato->num_rows == 0)
  	echo "<option value='' selected disabled>Nessuna aula disponibile</option>";
	else
	{
		echo "<option value='' selected disabled>Seleziona un'aula</option>";
		// Ciclo sulle aule disponibili
	  while($aula = $risultato->fetch_assoc())
		{
			// Ottengo il numero dei posti disponibili per l'aula in questione
			$numeroPosti=$aula['numeroPosti'];

			// Costruzione della query per controllare che non ci siano altre prenotazioni per l'aula
			$query="SELECT SUM(posti) as conta FROM richieste
	        		WHERE ora_inizio < '$orafine' AND ora_fine > '$orainizio' AND data = '$data' AND idAula='" .$aula['id'] . "'";
			// Esecuzione della query
			$ris=$conn->query($query);

			// Se ci sono delle prenotazioni per l'aula nello stesso giorno
			if(mysqli_num_rows($ris)!=0)
			{
				$riga=$ris->fetch_assoc();
				// Sottraggo i posti già prenotati
				$numeroPosti-=$riga['conta'];
			}
			// Se rimangono posti
			if($numeroPosti!=0)
			{
				// Se la prenotazione arriva dalla homepage -> Aula già scelta
				if($seleziona_aula != 0 && $seleziona_aula == $aula['id'])
					// "Opzione" già selezionata
					echo "<option value='" . $aula['id'] . "' selected> " . $aula['nome'] . " - Posti disponibili: $numeroPosti </option>";
				else
					echo "<option value='" . $aula['id'] . "'> " . $aula['nome'] . " - Posti disponibili: $numeroPosti </option>";
			}
			else
				echo "<option value='" . $aula['id'] . "' disabled> " . $aula['nome'] . " - Posti esauriti</option>";
		}
	}
?>
