<?php
  // Connessione al DataBase
  require './conn/conn.php';
  // Avvio sessione
  session_start();

  // Se l'utente è loggato
  if(isset($_SESSION['log'])&&$_SESSION['log']=="CON")
  {
    // Se l'utente è autenticato con Google
    if(isset($_SESSION['token_accesso']) && $_SESSION['token_accesso']!="")
    {
      // Recupero il token di accesso Google
      $token=$_SESSION['token_accesso'];
      // Recupero il cognome dell'utente
      $cognome_spazi=ucwords(strtolower($_SESSION['cognome']));
    }
    else
    {
      // Utente autenticato con cognome.matricola e password
      // Recupero i dati dell'utente
      // Matricola
      $matricola=$_SESSION['matricola'];
      // Cognome
      $cognome_spazi=ucwords(strtolower($_SESSION['cognome_spazi']));
      // Classe
      $classe=$_SESSION['classe'];
      // Privilegi
      $potere=$_SESSION['tipo'];
    }

    // Sistemo Cognome e Nome - Iniziale maiuscola
    $cognome=ucwords(strtolower($_SESSION['cognome']));
    $nome=ucwords(strtolower($_SESSION['nome']));
  }
  else
  {
    // Redirect alla pagina di login
    header("location: ./login/");
    die();
  }

  // Prendo la data di oggi o domani se è un admin
  $data = date("Y-m-d");
  if($potere != 2)
    $data = date('Y-m-d', strtotime($data . ' +1 day'));

  // Se la richiesta alla pagina arriva tramite POST
  if($_SERVER['REQUEST_METHOD']=="POST")
  {
    // Se viene passato una data
    if(isset($_POST['giorno']))
      // La recupero
      $data=mysqli_real_escape_string($conn, $_POST['giorno']);
  }

  // Tiro fuori l'elenco delle aule
  $query = "SELECT id,nome,descrizione,numeroPosti";

  // Aggiungo la variabile della disponibilità in base al tipo di utente
  if($_SESSION['tipo'] == 0)
    $query.= ", studenti";
  elseif($_SESSION['tipo'] == 1)
    $query.= ", docenti";

  $query.= " FROM aule ORDER BY nome";
  $elencoAule=$conn->query($query);

  // Inizializzo la variabile per comunicare messaggi/errori
  $messaggio="";
  // Email di ripristino password
  if(isset($_SESSION['email_inviata']) && $_SESSION['email_inviata']=="SI")
  {
    $messaggio="<div class='alert alert-success' role='alert' style='font-size:18px; text-align:center;'>Email per il ripristino della password inviata. </div>";
    $_SESSION['email_inviata']="NO";
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

  <title>SmartBox</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <script>
    function cambia()
    {
      document.getElementById('form').submit();
    }

    // Funzione per mostrare gli utenti che hanno prenotato un'aula (Solo admin)
    $(function ()
    {
      $('[data-toggle="popover"]').popover({ trigger: "hover" });
      $(".dropdown-toggle").dropdown('update');
    })

    // Funzione per il redirect alla pagina di prenotazione (Precompilata)
    function redirect(aula,ora)
    {
      data = document.getElementsByName('giorno')[0].value;
      window.location.href="./prenota-aula/index.php?aula=" + aula + "&ora=" + ora + "&data=" + data;
    }
  </script>

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
      <li class="nav-item active">
        <a class="nav-link" href="./">
          <i class="fas fa-home"></i>
          <span>Home</span></a>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Aule
      </div>
      <li class="nav-item">
        <a class="nav-link" href="./prenota-aula/">
          <i class="fas fa-check-circle"></i>
          <span>Prenota aula</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./visualizza-aule/">
          <i class="fas fa-info-circle"></i>
          <span>Visualizza aule</span></a>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Addons
      </div>
      <li class="nav-item">
        <a class="nav-link" href="./storico-richieste/">
          <i class="fas fa-fw fa-table"></i>
          <span>Storico richieste</span></a>
      </li>
      <!-- Parte del menù solo per admin -->
      <?php
        if($potere=='2')
        {
      ?>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Admin
      </div>
      <li class="nav-item">
        <a class="nav-link" href="./img/">
          <i class="fas fa-plus-circle"></i>
          <span>Aggiungi aula</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./crud_immagini/">
          <i class="fas fa-image"></i>
          <span>Modifica aula</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./img/crudAule.php">
          <i class="fas fa-trash-alt"></i>
          <span>Elimina aule</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./sospendi_aula/sospendi_aule.php">
          <i class="fas fa-ban"></i>
          <span>Sospendi aula</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../sospendi_aula/storico_sospensioni.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Storico sospensioni</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../prospetto-aule">
          <i class="fas fa-fw fa-table"></i>
          <span>Prospetto Aule</span>
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
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $nome . " " . $cognome_spazi?></span>
                <img class="img-profile rounded-circle" src="./img/man.png">
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
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
            <h1 class="h3 mb-0 text-gray-800">Disposizione aule</h1>
          </div>

          <div class="row card" style="padding-top:15px; padding-bottom:15px;">
            <!-- Form per il cambio data -->
            <form class="col-md-4 offset-md-4" method="post" id="form">
              <input class="form-control" style="text-align:center;" type="date" name="giorno" value="<?=$data?>" min="<?=$data?>" onchange="cambia()">
            </form>
            <br>
            <!-- Tabelle disposizione aule -->
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <!-- Intestazione -->
                  <tr>
                    <!-- Colonna orari 8-18 -->
                    <th><center>Orario</center></th>
                    <?php
                      // Inizializzo gli array
                      $idAula=array();
                      $postiAule=array();
                      $disponibile=array();
                      $numeroTotaleAule=0;
                      $conta=0;
                      // Ciclo sulle aule
                      while($dati = $elencoAule->fetch_assoc())
                      {
                        // Colonna aula
                        echo "<th onclick='window.location=\"./visualizza-aule/\"' style='cursor: pointer' title='". ucfirst(strtolower($dati['descrizione'])) . "\nNumero di posti totali: " . $dati['numeroPosti'] . "'>
                                <center>" . ucwords(strtolower($dati['nome'])) . "</center>
                              </th>";
                        $idAula[$numeroTotaleAule]=$dati['id'];
                        $postiAule[$numeroTotaleAule]=$dati['numeroPosti'];
                        $numeroTotaleAule++;

                        if($_SESSION['tipo'] == 0)
                          $disponibile[$conta] = $dati['studenti'];
                        elseif($_SESSION['tipo'] == 1)
                          $disponibile[$conta] = $dati['docenti'];

                        $conta++;
                      }
                    ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // Id(s) delle aule sospese nella data corrente
                    $query="SELECT idAula AS id FROM `sospendiAula` WHERE '$data' BETWEEN `sospendiDa` AND `sospendiA`";
                    $risultato=$conn->query($query);
                    // Inizializzo l'array delle aule sospese
                    $sospese=array();
                    $conta=0;
                    // Ciclo sulle aule sospese
                    while($riga = $risultato->fetch_assoc())
                    {
                      $sospese[$conta]=$riga['id'];
                      $conta++;
                    }

                    for($ora=8;$ora<18;$ora++)
                    {
                      $app=$ora+1;
                      echo "<tr>";
                        // Colonna orari
                        echo "<td style='font-weight:bold; text-aling:center;'><center>$ora:00 - $app:00</center></td>";
                        // Ciclo per ogni aula
                        for ($sing=0; $sing < $numeroTotaleAule; $sing++)
                        {
                          $sospesa = false;
                          foreach ($sospese as $key => $appo)
                          {
                            if($appo == $idAula[$sing])
                              $sospesa = true;
                          }


                          if($sospesa)
                            echo "<td class='bg-warning' style='color:white; font-weight:bold;'>L'aula è sospensa nella data corrente</td>";
                          elseif(($_SESSION['tipo'] == 0 || $_SESSION['tipo'] == 1) && !$disponibile[$sing])
                          {
                            echo "<td class='bg-danger' style='color:white; font-weight:bold;'>L'aula non è disponibile per ";
                            if($_SESSION['tipo'] == 0)
                            echo " gli studenti";
                            elseif($_SESSION['tipo'] == 1)
                              echo "i docenti";
                            echo "</td>";
                          }
                          else
                          {
                            // Altrimenti procedo normalmente
                            $orafine=date("H:i:s", $app*3600 - 3600);
                            $orainizio=date("H:i:s", $ora*3600 - 3600);
                            if($potere=='2')
                            {
                              $query="SELECT utenti.cognome as cognome, utenti.nome as nome, utenti.classe as classe
                              FROM richieste INNER JOIN utenti on richieste.idUtente=utenti.id
                              WHERE richieste.ora_inizio<'$orafine' and richieste.ora_fine>'$orainizio' and richieste.data='$data' and richieste.idAula='" .$idAula[$sing] . "'";
                              $singolaQuery=$conn->query($query);
                              $elencoPersone="<i class='fas fa-users' tabindex='0' data-trigger='focus' data-html='true' data-toggle='popover' style='margin-left:3px;' title='Elenco delle persone' data-content='";

                              while($rigo=$singolaQuery->fetch_assoc())
                              {
                                $elencoPersone.=$rigo['cognome']. " " . $rigo['nome'];
                                if($rigo['classe']!="")
                                  $elencoPersone.=" (".$rigo['classe'].")";
                                $elencoPersone.="<br>";
                              }
                              $elencoPersone.="'></i>";
                              $query="SELECT SUM(posti) as conta
                                      FROM richieste
                                      WHERE ora_inizio < '$orafine' AND ora_fine > '$orainizio' AND data = '$data' AND idAula=" . $idAula[$sing];

                              $singolaQuery=$conn->query($query);
                              $tutto=$singolaQuery->fetch_assoc();
                              $postiDispo=$tutto['conta'];

                              $percentuale=($postiDispo / $postiAule[$sing])*100;
                              $postiD=$postiAule[$sing]-$postiDispo;
                              if($percentuale==0)
                                $elencoPersone="";
                              if($percentuale==100)
                              {
                                echo "<td class='bg-danger' style='color:white; font-weight:bold;'>Non ci sono posti disponibili $elencoPersone</td>";
                              }
                              else if ($percentuale <=50)
                              {
                                echo "<td class='bg-success' style='color:white; font-weight:bold; cursor: pointer;' ";
                                if($data <= date("Y-m-d"))
                                  echo "title=\"Non puoi prenotare un'aula per oggi\">Posti: $postiD / " . $postiAule[$sing] . " $elencoPersone</td>";
                                else
                                  echo "title='Clicca per prenotare questa aula' onclick='redirect($idAula[$sing],$ora)'>Posti: $postiD / " . $postiAule[$sing] . " $elencoPersone</td>";
                              }
                              else
                              {
                                echo "<td class='bg-warning' style='color:white; font-weight:bold; cursor: pointer;' ";
                                if($data <= date("Y-m-d"))
                                  echo "title=\"Non puoi prenotare un'aula per oggi\">Posti: $postiD / " . $postiAule[$sing] . " $elencoPersone</td>";
                                else
                                  echo "title='Clicca per prenotare questa aula' onclick='redirect($idAula[$sing],$ora)'>Posti: $postiD / " . $postiAule[$sing] . " $elencoPersone</td>";
                              }

                            }
                            else
                            {
                              $query="SELECT SUM(posti) as conta
                                      FROM richieste
                                      WHERE ora_inizio < '$orafine' AND ora_fine > '$orainizio' AND data = '$data' AND idAula=" . $idAula[$sing];
                              $singolaQuery=$conn->query($query);
                              $tutto=$singolaQuery->fetch_assoc();
                              $postiDispo=$tutto['conta'];
                              $percentuale=($postiDispo / $postiAule[$sing])*100;
                              $postiD=$postiAule[$sing]-$postiDispo;
                              if($percentuale==100)
                              {
                                echo "<td class='bg-danger' style='color:white; font-weight:bold;'>Non ci sono posti disponibili</td>";
                              }
                              else if ($percentuale <=50)
                              {
                                echo "<td class='bg-success' style='color:white; font-weight:bold; cursor: pointer;' ";
                                if($data == date("Y-m-d"))
                                  echo "title=\"Non puoi prenotare un'aula per oggi\">Posti: $postiD / " . $postiAule[$sing] . " $elencoPersone</td>";
                                else
                                  echo "title='Clicca per prenotare questa aula' onclick='redirect($idAula[$sing],$ora)'>Posti: $postiD / " . $postiAule[$sing] . " $elencoPersone</td>";
                              }
                              else
                              {
                                echo "<td class='bg-warning' style='color:white; font-weight:bold; cursor: pointer;' ";
                                if($data == date("Y-m-d"))
                                  echo "title=\"Non puoi prenotare un'aula per oggi\">Posti: $postiD / " . $postiAule[$sing] . " $elencoPersone</td>";
                                else
                                  echo "title='Clicca per prenotare questa aula' onclick='redirect($idAula[$sing],$ora)'>Posti: $postiD / " . $postiAule[$sing] . " $elencoPersone</td>";
                              }
                            }
                          }
                        }
                      echo "</tr>";
                    }

                  ?>
                </tbody>
              </table>
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
          <a class="btn btn-primary" href="logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
</body>

</html>
