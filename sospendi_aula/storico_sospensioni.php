<?php
  // Connessione al DataBase
  require '../conn/conn.php';
  // Controllo dati utente
  require '../controlla-accesso/index.php'; // Apre già la session

  // Query per estrarre tutte le aule sospese
  $query="SELECT aule.nome AS nomeAula, sospendiAula.sospendiDa AS inizio, sospendiAula.sospendiA AS fine FROM sospendiAula
        INNER JOIN aule on aule.id=idAula
        ORDER BY inizio DESC
        LIMIT 30";

   $risultato=$conn->query($query);

   // Query per estrarre id e nome di tutte le aule
   $queryAule="SELECT id,nome FROM aule";
   $elencoAule=$conn->query($queryAule);

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

  <title>SmartBox | Storico sospensioni aule</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

  <script>
    // Variabile globale utile per le operazioni Ajax
    var tutte = true;

    // Funzioni default per le operazioni Ajax
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

    // Funzione per filtrare le aule sospese
    function filtra()
    {
      // Recupero dati dalla form
      var aula = document.getElementById('aulaFiltro').value;
      var datai = document.getElementById('dataFiltroI').value;
      var dataf = document.getElementById('dataFiltroF').value;
      var tabella = document.getElementById('tabella');

      // Controllo sulle date => Non nulle e valide (Data inizio < data fine) OPPURE Entrambe nulle
      if((datai != "" && dataf != "" && datai < dataf) ||  (datai == "" && dataf == ""))
      {
        // Chiamata alla pagina per le operazioni Ajax
        loadPage("filtra_sospensioni.php?datai="+datai+"&dataf="+dataf+"&aula="+aula+"&tutte="+tutte,"");

        // Riporto i risultati nella tabella
        tabella.innerHTML=req.responseText;
      }

      // Se le date inserite non sono valide => Non nulle e Data inizio > data fine
      if(datai != "" && dataf != "" && datai > dataf)
        // Mostro il banner di errore
        document.getElementById('banner').style.display = "block";
      else
        document.getElementById('banner').style.display = "none";
  }

  // Funzione per cancellare la sospensione di un'aula
  function cancella_sospensione(dati_aula)
  {
    // Recupero dal parametro l'Id dell'aula e la data di inizio della sospensione
    var id_aula = dati_aula.split("|")[0];
    var data_inizio = dati_aula.split("|")[1];


    // Chiamata Ajax
    loadPage("elimina_sospensione.php?id="+id_aula+"&data="+data_inizio,"");

    // Se va tutto bene
    if(req.responseText == "OK")
      // Mostro il banner che comuninca l'avvenuta cancellazione della sospensione
      document.getElementById('banner_2').style.display = "block";

    // Aggiorno le sospensioni mostrate in tabella
    filtra();
  }
  </script>
  <style>
    #cancella
    {
      font-weight: bold;
      height: 25px;
      width: 25px;
      padding: 0;
    }

    tr
    {
      text-align: center;
    }
  </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
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
      <li class="nav-item">
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
      <!-- Parte del menù riservata all'admin -->
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
        <a class="nav-link" href="./sospendi_aule.php">
          <i class="fas fa-ban"></i>
          <span>Sospendi aula</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="./storico_sospensioni.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Storico sospensioni</span>
        </a>
      </li>
      <?php
        }
      ?>
      <hr class="sidebar-divider d-none d-md-block">
      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- End of Sidebar -->

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
            <h1 class="h3 mb-0 text-gray-800">Storico Sospensioni</h1>
          </div>
          <div class="row">
            <div class="container">
              <center>
                <br><br>
                <!-- Form per filtrare le sospensioni -->
                <form class="form-row" id="miaForm">
                  <!-- Data di inizio del filtro -->
                	<div class="form-group">
                    <label for="dataFiltroI" style="font-weight:bold;">Data di inizio</label>
                		<input class="form-control" type="date" id='dataFiltroI' name="dataFiltroI" onchange="filtra()" value="">
                  </div>
                  <!-- Data di fine del filtro -->
                  <div class="form-group" style="margin-left:10px;">
                    <label for="dataFiltroF" style="font-weight:bold;">Data di fine</label>
                    <input class="form-control" type="date" id='dataFiltroF' name="dataFiltroF" onchange="filtra()" value="">
                  </div>
                  <!-- Sospensione per solo un'aula -->
                	<div class="form-group" style="margin-left:10px;">
                    <label for="aulaFiltro" style="font-weight:bold;">Aula</label>
                    <select name="aulaFiltro" id="aulaFiltro" class="form-control" onchange="filtra()">
                      <option value='-1'>Tutte</option>
                      <?php
                        // Elenco delle aule
                        while($riga=$elencoAule->fetch_assoc())
                        {
                        	if($riga['id']==$aulaFiltro)
                        		echo "<option selected value='". $riga['id'] . "' >".$riga['nome'] . "</option>";
                            else
                            	echo "<option value='". $riga['id'] . "' >".$riga['nome'] . "</option>";
                        }
                      ?>
                    </select>
                  </div>
                  <div class="form-group" style="margin-left:10px;">
                    <label style="font-weight:bold;">Tipo sospensione</label>
                    <!-- Bottoni per selezionare le sospensioni in corso/future o anche quelle passate  -->
                    <div class="btn-group form-control" role="group" style="padding:0px;">
                      <button type='button' class="btn btn-primary" onclick="tutte=true; filtra();">Tutte</button>
                      <button type='button' class="btn btn-success" onclick="tutte=false; filtra();">In corso</button>
                    </div>
                  </div>
                </form>
              </center>
              <br>
              <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="font-weight-bold text-primary" style="margin:auto;">Storico sospensioni</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive" id="tabella">
                    <!-- Chiamata della funzione al caricamento della pagina -->
                    <script>filtra();</script>
                  </div>
                </div>
              </div>
              <!-- Banner di errore/comunicazione -->
              <center>
                <div class="alert alert-success" role="alert" id="banner_2" style="margin-top: 15px; font-size:18px; display: none;">
                  La sospensione è stata eliminata con successo
                </div>
                <div class="alert alert-danger col-md-8" role="alert" id="banner" style="display: none; text-align: center; font-size:18px; margin-top:10px;">
                  <b>Attenzione!</b> Per filtrare i risultati, la data di inizio deve essere minore di quella di fine.
                </div>
              </center>
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
