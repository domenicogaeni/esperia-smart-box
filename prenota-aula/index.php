<?php
  // Connessione al DataBase
  require '../conn/conn.php';
  // Controllo dati utente
  require '../controlla-accesso/index.php'; // Apre già la session

  // Recupero dei dati se la prenotazione
  // arriva dalla homepage
  // Aula selezionata
  $set_aula="0";
  $funzione = "";
  if(isset($_GET['aula']))
  {
    $set_aula = $_GET['aula'];
    $funzione = " posti();";
  }
  // Orario selezionato
  $set_ora='-1';
  if(isset($_GET['ora']))
    $set_ora = $_GET['ora'];

  // Data selezionata
  $set_data = '0';
  if(isset($_GET['data']))
    $set_data = $_GET['data'];

  // Prendo già la data successiva a quella scelta
  $dopodomani = date("Y-m-d",strtotime("+1 day"));

  // Funzione per popolare la select per l'ora d'inizio e di fine
  function scrivi_lista( $id , $tipo )
  {
    // Variabile globale dell'orario preimpostato
    global $set_ora;

    // Inizializzazione degli "orari limite"
    $inizio=8;
    $fine=18;

    // Combobox per la scelta dell'orario di inizio o fine della prenotazione
    echo '<select class="form-control" id="' . $id  . '" name="' . $id . '" required onchange="caricaAule(); posti();">';
    // Se devo popolare l'orario finale o iniziale
    if($tipo == 1)
      echo "<option value='' selected disabled>Seleziona l'orario di fine</option>";
    else
      echo "<option value='' selected disabled>Seleziona l'orario d'inizio</option>";

    // Ciclo per inserire gli orari
    for($i=$inizio;$i<=$fine;$i++)
    {
      $ora=$i . ":00";
      // Se l'orario è quello scelto nella homepage
      if(($i == $set_ora && $id == "orainizio") || ($i == ($set_ora+1) && $id == "orafine"))
        // Lo imposto come selezionato
        echo '<option value="' . $ora . '" selected>' . $ora . '</option>';
      else
        echo '<option value="' . $ora . '">' . $ora . '</option>';
    }
    echo "</select>";

  }

  // Recupero quante ore al massimo posso prenotare
  $oreLettere = array("un'ora", "due ore", "tre ore","quattro ore","cinque ore","sei ore");
  $query="SELECT valore FROM parametri WHERE descrizione='massimoOre'";
  $risu=$conn->query($query);
  $tutto=$risu->fetch_assoc();
  $maxOre=$tutto['valore'];

  $divoRichiesta="";
  // Inserisco il messaggio da mostrare (Banner)
  if(isset($_SESSION['inserimentoRichiesta'])&&$_SESSION['inserimentoRichiesta']!="")
  {
    $divoRichiesta=$_SESSION['inserimentoRichiesta'];
    $_SESSION['inserimentoRichiesta']="";
  }

  function isMobileDevice()
  {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
  }
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SmartBox | Prenota aula</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

  <script>
  // Funzioni per le operazioni Ajax
  var req;

  function loadPage(url, postvalue)
  {
    req = getAjaxControl();
    if(req)
    {
      req.open("POST", url, false); // sincrono
      req.setRequestHeader("Content-Type", "text/xml")
      req.send(postvalue);
    }
  }

  function getAjaxControl()
  {
    req = false;
    // branch for native XMLHttpRequest object
    if(window.XMLHttpRequest && !(window.ActiveXObject))
    {
      try
      {
        req = new XMLHttpRequest();
      }
      catch(e)
      {
        req = false;
      }
      // branch for IE/Windows ActiveX version
    }
    else if(window.ActiveXObject)
    {
      try
      {
        req = new ActiveXObject("Msxml2.XMLHTTP");
      }
      catch(e)
      {
        try
        {
          req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e)
        {
          req = false;
        }
      }
    }
    return req;
  }

  // Recupero le aule disponibili nella data e nelle ore indicate
  function caricaAule()
  {
    // Prendo i dati inseriti in input
    var data = document.getElementById('inputGiorno').value;
    var orai = document.getElementById('orainizio');
    var oraf = document.getElementById('orafine');

    var orainizio=orai.options[orai.selectedIndex].text;
    var orafine=oraf.options[oraf.selectedIndex].text;

    // Controllo della validità dei dati inseriti in input
    // Orari scelti non nulli, data non nulle e ora di inizio minore di ora di fine
    if(orai.selectedIndex!=0 && oraf.selectedIndex!=0 && data!="" && orai.selectedIndex<oraf.selectedIndex)
    {
      // Chiamata alla pagina php per ottenere il risultato Ajax
      loadPage("elencaAule.php?data="+data+"&orainizio="+orainizio+"&orafine="+orafine+"&aula="+<? echo $set_aula; ?>+"&tipo="+<? echo $_SESSION['tipo']; ?>,"");

      // Risposta della pagina
      document.getElementById('inputAula').innerHTML=req.responseText;
    }
    // Se l'ora di inizio è maggiore dell'ora di fine
    else if (orainizio>=orafine)
    {
      // Faccio vedere il banner con il messaggo di errore
      document.getElementById('inputAula').innerHTML="<option selected disabled>Seleziona la data e gli orari</option>";
    }
  }

  // Controllo dei dati inseriti in input
  function controlla()
  {
    // Inizializzazione variabili
    // Raccolta dati inseriti in input
    var valido=true;
    var data=document.getElementById("inputGiorno").value;
    var orai=document.getElementById("orainizio").selectedIndex;
    var oraf=document.getElementById("orafine").selectedIndex;
    var aula=document.getElementById("inputAula").selectedIndex;
    var limiteOre=<?php echo $maxOre ?>;

    // Nascondo tutti i banner
    document.getElementById('orario_inizio_non_selezionato').style.display="none";
    document.getElementById('orario_fine_non_selezionato').style.display="none";
    document.getElementById('giorno_non_selezionato').style.display="none";
    document.getElementById('orari_sbagliati').style.display="none";
    document.getElementById('aula_non_selezionata').style.display="none";
    document.getElementById('durata_ore_max').style.display="none";

    // Effettuo i controlli
    // Se nessun orario di inizio è selezionato
    if(orai==0)
    {
      // Mostro il banner
      document.getElementById('orario_inizio_non_selezionato').style.display="block";
      valido=false;
    }
    // Se nessun orario di fine è selezionato
    if(oraf==0)
    {
      // Mostro il banner
      document.getElementById('orario_fine_non_selezionato').style.display="block";
      valido=false;
    }
    // Se l'intervallo di tempo selezionato
    // supera il limite possibile
    if(oraf-orai>limiteOre)
    {
      // Mostro il banner
      document.getElementById('durata_ore_max').style.display="block";
      valido=false;
    }
    // Se non è selezionata nessuna data
    if(data=="")
    {
      // Mostro il banner
      document.getElementById('giorno_non_selezionato').style.display="block";
      valido=false;
    }
    // Se l'ora di inizio è maggiore di quella di fine
    if(orai>oraf&&orai!=0&&oraf!=0)
    {
      // Mostro il banner
      document.getElementById('orari_sbagliati').style.display="block";
      valido=false;
    }
    // Se non è stata selezionata nessuna aula
    if (aula==0)
    {
      // Mostro il banner
      document.getElementById('aula_non_selezionata').style.display="block";
      valido=false;
    }

    // Se non ci sono errori eseguo il submit
    return valido;
  }

  function carica_data()
  {
    // Inserisco la data scelta nell'homepage
    var data = '<? echo $set_data; ?>';

    if(data != '0')
      document.getElementById('inputGiorno').value = data;
  }

  function posti()
  {
    var data = document.getElementById("inputGiorno").value;
    var orai = document.getElementById("orainizio").value;
    var oraf = document.getElementById("orafine").value;
    var aula = document.getElementById("inputAula").value;

    if(data != "" && orai != "" && oraf != "" && aula != "")
    {
      // Chiamata alla pagina php per ottenere il risultato Ajax
      loadPage("conta_posti.php?data="+data+"&orainizio="+orai+"&orafine="+oraf+"&aula="+ aula,"");

      // Risposta della pagina
      document.getElementById('inputPosti').innerHTML = req.responseText;
    }
  }
  </script>
</head>

<body id="page-top" onload="carica_data(); caricaAule(); <? echo $funzione ?>">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Menù laterale -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion
      <?
        if(isMobileDevice())
          echo 'toggled'
      ?>
    " id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="./">
        <div class="sidebar-brand-text mx-3">Smart Box</div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item">
        <a class="nav-link" href="../">
          <i class="fas fa-home"></i>
          <span>Home</span></a>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Aule
      </div>
      <li class="nav-item active">
        <a class="nav-link" href="../prenota-aula/">
          <i class="fas fa-check-circle"></i>
          <span>Prenota aula</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../visualizza-aule/">
          <i class="fas fa-info-circle"></i>
          <span>Visualizza aule</span></a>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Addons
      </div>
      <li class="nav-item">
        <a class="nav-link" href="../storico-richieste/">
          <i class="fas fa-fw fa-table"></i>
          <span>Storico richieste</span></a>
      </li>
      <!-- Parte del menù riservata all'Admin -->
      <?php
        if($potere=='2')
        {
      ?>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Admin
      </div>
      <li class="nav-item">
        <a class="nav-link" href="../img/">
          <i class="fas fa-plus-circle"></i>
          <span>Aggiungi aula</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../crud_immagini/">
          <i class="fas fa-image"></i>
          <span>Modifica aula</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../img/crudAule.php">
          <i class="fas fa-trash-alt"></i>
          <span>Elimina aule</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../sospendi-aula/">
          <i class="fas fa-ban"></i>
          <span>Sospendi aula</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../storico-sospensioni/">
          <i class="fas fa-fw fa-table"></i>
          <span>Storico sospensioni</span>
        </a>
      </li>
      <?php
        }
      ?>

      <hr class="sidebar-divider d-none d-md-block">
      <!-- Pulsante per espandere/chiudere -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- Fine Menù -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <div class="topbar-divider d-none d-sm-block"></div>
            <!-- User -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo ucwords($nome) . " " . ucwords($cognome_spazi)?></span>
                <img class="img-profile rounded-circle" src="../img/man.png">
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>
          </ul>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Prenota aula</h1>
          </div>

          <div class="row">
            <div class="container">
              <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="font-weight-bold text-primary" style="margin:auto;">Prenota aula</h6>
                </div>
                <div class="card-body">
                  <!-- Form per richiedere un'aula -->
                  <form action="richiesta-aula.php" method="post" onsubmit="return controlla()">
                    <!-- Data della prenotazione -->
                    <div class="form-group row">
                      <label for="inputGiorno" class="offset-md-1 col-md-3 col-form-label">Giorno </label>
                      <div class="col-md-7">
                        <input type="date" class="form-control" id="inputGiorno" name="giorno" value="<?php echo $dopodomani ?>" min="<?php echo $dopodomani ?>" required onchange='caricaAule(); posti();'>
                      </div>
                    </div>
                    <!-- Ora di inizio della prenotazione -->
                    <div class="form-group row">
                      <label for="orainizio" class="offset-md-1 col-md-3 col-form-label">Ora di inizio </label>
                      <div class="col-md-7">
                        <?php scrivi_lista("orainizio",0) ?>
                      </div>
                    </div>
                    <!-- Ora di fine della prenotazione -->
                    <div class="form-group row">
                      <label for="orafine" class="offset-md-1 col-md-3 col-form-label">Ora di fine </label>
                      <div class="col-md-7">
                        <?php scrivi_lista("orafine",1) ?>
                      </div>
                    </div>
                    <!-- Aula da prenotare -->
                    <div class="form-group row">
                      <label for="inputAula" class="offset-md-1 col-md-3 col-form-label">Aula </label>
                      <div class="col-md-7">
                        <select class="form-control" name="aula" id="inputAula" onchange="posti();" required>
                          <option value='' selected disabled>Seleziona la data e gli orari</option>
                        </select>
                      </div>
                    </div>
                    <!-- Seleziona più posti -->
                    <?
                    // Se l'utente è un docente
                    if($potere == 1)
                    {
                    ?>
                    <div class="form-group row">
                      <label for="inputPosti" class="offset-md-1 col-md-3 col-form-label">Numero di posti</label>
                      <div class="col-md-7">
                        <select class="form-control" name="numero_posti" id="inputPosti" required>
                          <option selected disabled>Seleziona quanti posti prenotare</option>
                        </select>
                      </div>
                    </div>
                    <?
                    }
                    ?>

                    <!-- Serie di banner per le comunicazioni/errori -->
                    <div id="segnalazioni" style="margin:auto; margin-top:15px; margin-bottom:15px; width: 90%;">
                      <?php echo $divoRichiesta ?>
                      <div class="alert alert-danger text-center" id="orario_inizio_non_selezionato" style="display: none" role="alert">
                        <strong>Attenzione</strong>: Selezionare l'orario d'inizio
                      </div>
                      <div class="alert alert-danger text-center" id="orario_fine_non_selezionato" style="display: none" role="alert">
                        <strong>Attenzione</strong>: Selezionare l'orario di fine
                      </div>
                      <div class="alert alert-danger text-center" id="giorno_non_selezionato" style="display: none" role="alert">
                        <strong>Attenzione</strong>: Selezionare un giorno
                      </div>
                      <div class="alert alert-warning text-center" id="durata_ore_max" style="display: none" role="alert">
                        <strong>Attenzione</strong>: Puoi prenotare un'aula per <?php echo $oreLettere[$maxOre-1] ?> al massimo
                      </div>
                      <div class="alert alert-danger text-center" id="aula_non_selezionata" style="display: none" role="alert">
                        <strong>Attenzione</strong>: Selezionare un'aula
                      </div>
                      <div class="alert alert-warning text-center" id="orari_sbagliati" style="display: none" role="alert">
                        <strong>Attenzione</strong>: Ricontralla gli orari che hai selezionato
                      </div>
                    </div>

                    <!-- Bottone per confermare la prenotazione -->
                    <center>
                      <button type="submit" class="btn btn-primary">Richiedi aula</button>
                    </center>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Smart Box Esperia 2019</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Sei sicuro di voler uscire?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Seleziona "Logout" se sei sicuro di uscire dalla sessione.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Annulla</button>
          <a class="btn btn-primary" href="../logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>
  <!-- Page level plugins -->
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <!-- Page level custom scripts -->
  <script src="../js/demo/chart-area-demo.js"></script>
  <script src="../js/demo/chart-pie-demo.js"></script>
</body>

</html>
