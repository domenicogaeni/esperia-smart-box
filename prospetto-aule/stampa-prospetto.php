<?php
  // Connessione al DataBase
  require '../conn/conn.php';
  // Controllo dati utente
  require '../controlla-accesso/index.php';


// Se la richiesta alla pagina arriva con metodo POST
if($_SERVER['REQUEST_METHOD']=="POST")
{
  // Estraggo i dati passati dalla form
  // Data prospetto
  $data = mysqli_real_escape_string($conn,$_POST['data']);
  // Aula/e prospetto
  $aula = mysqli_real_escape_string($conn,$_POST['aula']);

  // Se il prospetto è di tutte le aule le estraggo
  if($aula == -1)
  {
    // Estraggo l'elenco delle aule
    $queryAule="SELECT id,nome FROM aule";
    $elencoAule=$conn->query($queryAule);
  }

  // Estraggo le prenotazioni del giorno
  $query="SELECT aule.nome AS nome_aula, richieste.ora_inizio AS inizio, richieste.ora_fine AS fine, utenti.nome AS nome_utente, richieste.posti AS posti FROM richieste
          INNER JOIN aule on richieste.idAula = aule.id
          INNER JOIN utenti on richieste.idUtente = utenti.id
          WHERE data = '$data'
          ORDER BY aule.nome
          ";
  echo $query;
  //$elencoAule=$conn->query($queryAule);


}
?>
