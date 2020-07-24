<?php
   require '../conn/conn.php';

   require '../controlla-accesso/index.php';
   if($potere!='2')
   {
      header("location: ../logout.php");
      die();
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

  <title>SmartBox | Aggiungi Aula</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

  <script src="upload.js"></script>
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
      <li class="nav-item active">
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
                <img class="img-profile rounded-circle" src="../img/man.png">
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
            <h1 class="h3 mb-0 text-gray-800">Modifica aule</h1>
          </div>

          <div class="container card" style="padding-top:15px; padding-bottom:15px;">
            <table class="table table-striped table-responsive">
              <thead>
                <tr>
                  <th>Nome Aula</th>
                  <th>Descrizione</th>
                  <th>Capienza</th>
                  <th>Note</th>
                  <th>Elimina</th>
                </tr>
              </thead>
              <tbody>
              <?php
                require '../conn/conn.php';
                $sql="SELECT id,nome,descrizione,numeroPosti, note FROM aule";
                $ris=$conn->query($sql);
                while($riga = $ris->fetch_assoc())
                {
                    $id=$riga['id'];
                    ?>
                    <form action="elimina.php" class="eliminare" method="post">
                      <input type="hidden" name="id" id="id" value="<?=$id?>"/>
                      <tr>
                        <td width="25%"><?=$riga['nome'] ?></td>
                        <td width="35%"><?=$riga['descrizione'] ?></td>
                        <td width="10%"><?=$riga['numeroPosti'] ?></td>
                        <td width="20%"><?=$riga['note'] ?></td>
                        <td width="10%"><button type="submit" class="btn btn-danger">Elimina</button></td>
                      </tr>
                     </form>
                    <?php
                }
                mysqli_close($conn);
              ?>
              </tbody>
            </table>
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
