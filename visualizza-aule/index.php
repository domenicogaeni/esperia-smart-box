<?php
  // Connessione al DataBase
  require '../conn/conn.php';

  // Avvio sessione
  session_start();

  // Se l'utente è loggato
  if(isset($_SESSION['log'])&&$_SESSION['log']=="CON")
  {
    // Se l'utente autenticato con Google
    if(isset($_SESSION['token_accesso']) && $_SESSION['token_accesso']!="")
    {
      // Estraggo il token di ccesso a Google
      $token=$_SESSION['token_accesso'];
      // Estraggo il cognome dell'utente
      $cognome_spazi=ucwords(strtolower($_SESSION['cognome']));
     }
     else
     {
       // Utente autenticato con cognome.matricola e password
       // Estraggo i dati dell'utente
       // Matricola
       $matricola=$_SESSION['matricola'];
       // Cognome
       $cognome_spazi=ucwords(strtolower($_SESSION['cognome_spazi']));
       // Classe
       $classe=$_SESSION['classe'];
       // Privilegi
       $potere=$_SESSION['tipo'];
     }
     // Sistemo nome e cognome => Iniziale maiuscola
     $cognome=ucwords(strtolower($_SESSION['cognome']));
     $nome=ucwords(strtolower($_SESSION['nome']));
   }
   else
   {
     // Redirect alla pagina di login
     header("location: ../login/");
     die();
   }
    // Devo tirar fuori tutte le aule presenti nel db: nome-descrizione-note
    // Devo tirar fuori il nome delle immagini collegate a quella aula
    $sqlquery="SELECT id,nome,descrizione,numeroPosti,note FROM aule ORDER BY nome";
    $result = mysqli_query($conn,$sqlquery);

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

  <title>SmartBox | Visualizza aule</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

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
      <li class="nav-item active">
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
      <!-- Parte del menù riservata agli admin -->
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
        <a class="nav-link" href="../sospendi-aula/sospendi_aule.php">
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
            <h1 class="h3 mb-0 text-gray-800">Visualizza aule</h1>
          </div>

          <div class="row">
            <?php
              $conta=0;
              // Ciclo sulle aule
              while($riga = $result->fetch_assoc())
              {
                echo "<div class='col-md-6' style='padding:10px;'>
                      <div class='card' style='padding: 0px;'>";
                $idAula=$riga['id'];

                // Verifico che l'aula abbia delle immagini
                $query="SELECT foto.pathImmagine FROM foto INNER JOIN aule ON foto.idAula=aule.id where aule.id='$idAula';";

                $risultatoImmagini=$conn->query($query);

                // Se sono memorizzate immagini
                if($risultatoImmagini->num_rows!=0)
                {
                  // Creo il carousel con le immagini dell'aula
                  echo "<div id='carouselExampleIndicators$conta' class='card-img-top carousel slide' data-ride='carousel'>
                          <ol class='carousel-indicators'>";
                  for ($i=0; $i < $risultatoImmagini->num_rows; $i++)
                    echo "<li data-target='#carouselExampleIndicators$conta' data-slide-to='$i' class='active'></li>";

                  echo "</ol>
                        <div class='carousel-inner'>";
                  $app=0;
                  while($rigaImmagine = $risultatoImmagini->fetch_assoc())
                  {
                    echo "<div class='carousel-item";
                    if($app==0)
                       echo " active";
                    echo "'>
                            <img src='../img/aule/" . $rigaImmagine['pathImmagine'] . "' class='d-block w-100'>
                          </div>";
                    $app=1;
                  }
                  echo "</div>
                          <a class='carousel-control-prev' href='#carouselExampleIndicators$conta' role='button' data-slide='prev'>
                            <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                            <span class='sr-only'>Previous</span>
                          </a>
                          <a class='carousel-control-next' href='#carouselExampleIndicators$conta' role='button' data-slide='next'>
                            <span class='carousel-control-next-icon' aria-hidden='true'></span>
                            <span class='sr-only'>Next</span>
                          </a>
                        </div>";
                }
                echo  " <div class='card-body'>
                            <h5 class='card-title font-weight-bold text-primary'>" . strtoupper($riga['nome']) . "</h5>
                            <p class='card-text'> Numero di posti: <span class='font-weight-bold'>" . $riga['numeroPosti'] . "</span></p>
                            <p class='card-text'>" . $riga['descrizione'] . "</p>
                            <p class='card-text'><small class='text-muted'>" . $riga['note'] . "</small></p>
                          </div>
                        </div>
                      </div>";
              $conta++;
            }
            ?>
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
