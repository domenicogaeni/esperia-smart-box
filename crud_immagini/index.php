<?php
  // Connessione al DataBase e controllo dell'utente
  require '../conn/conn.php';
  // Controllo dati utente
  require '../controlla-accesso/index.php';

  // Se non è un admin lo sbatto fuori
  if($potere != '2')
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

  <title>SmartBox | Gestione aule</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

  <script src="upload_image.js?v18"></script>
  <script>
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

    // Funzione per modificare la possibilità di prenotare
    function prenotabile(valore)
    {
      var tipo = valore.split("_")[0];
      var aula = valore.split("_")[1];
      if(tipo == "S")
        valore = document.getElementById('studenti_' + aula).checked;
      else
        valore = document.getElementById('docenti_' + aula).checked;

      // Chiamata alla pagina per le operazioni Ajax
      loadPage("prenotabile.php?tipo=" + tipo + "&aula=" + aula + "&valore=" + valore,"");
    }

    // Funzione per aprire l'immagine in un'altra scheda
    function mostra(nome)
    {
      window.open("http://esperiasmartbox.altervista.org/img/aule/"+nome);
    }

    // Funzione per salvare le modifiche ai dati delle aule
    function aggiorna(numero)
    {
      // Recupero i dati della form
      id_aula = document.getElementsByName('id_aula')[numero - 1].value;
      nome = document.getElementsByName('nome')[numero - 1].value;
      descrizione = document.getElementsByName('desc')[numero - 1].value;
      posti = document.getElementsByName('numero')[numero - 1].value;
      note = document.getElementsByName('note')[numero - 1].value;

      // Chiamata alla pagina per le operazioni Ajax
      loadPage('aggiorna.php?aula=' + id_aula + '&nome=' + nome + '&descrizione=' + descrizione + '&posti=' + posti + '&note=' + note,"");

      banners = document.getElementsByName('banner');

      for(i=0;i<banners.length;i++)
        banners[i].style.display = "none";

      document.getElementsByName('banner')[numero - 1].style.display = "block";
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

    <li class="nav-item">
      <a class="nav-link" href="../">
        <i class="fas fa-home"></i>
        <span>Home</span>
      </a>
    </li>
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
      Aule
    </div>

    <li class="nav-item">
      <a class="nav-link" href="../prenota-aula/">
        <i class="fas fa-check-circle"></i>
        <span>Prenota aula</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="../visualizza-aule/">
        <i class="fas fa-info-circle"></i>
        <span>Visualizza aule</span>
      </a>
    </li>
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
      Addons
    </div>

    <li class="nav-item">
      <a class="nav-link" href="../storico-richieste/">
        <i class="fas fa-fw fa-table"></i>
        <span>Storico richieste</span>
      </a>
    </li>
    <!-- Parte solo per Admin -->
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
        <span>Aggiungi aula</span>
      </a>
    </li>

    <li class="nav-item active">
      <a class="nav-link" href="../crud_immagini/">
        <i class="fas fa-image"></i>
        <span>Modifica aula</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="../img/crudAule.php">
        <i class="fas fa-trash-alt"></i>
        <span>Elimina aule</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="../sospendi-aula/">
        <i class="fas fa-ban"></i>
        <span>Sospendi aula</span>
      </a>
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
          <h1 class="h3 mb-0 text-gray-800">Gestione aule</h1>
        </div>

        <!-- Card Container -->
        <div class="container card" style="padding-top:15px; padding-bottom:15px;">
          <?php
            // Compongo la query per estrarre tutte le aule
            $query = "SELECT id,nome,studenti,docenti,descrizione,numeroPosti,note FROM aule ORDER BY nome";

            // Se l'esecuzione della query va a buon fine
            if($ris = mysqli_query($conn,$query))
            {
              $conta = 0;
              // Ciclo sulle aule
              while ($row = mysqli_fetch_row($ris))
              {
                $conta++;
                // Recupero i dati delle Aule
                // Id dell'aula
                $id = $row[0];
                // Nome dell'aula
                $nome = $row[1];
                // Prenotabile per gli studenti
                $studenti = $row[2];
                // Prenotabile per i docenti
                $docenti = $row[3];
                // Descrizione Aula
                $descrizione = $row[4];
                // Numero Posti Aula
                $posti = $row[5];
                // Note Aula
                $note = $row[6];
          ?>
          <div class="container">
            <form action="aggiungi.php" method="post" class="form" multipart="" enctype="multipart/form-data" style="padding:15px;">
              <!-- Id dell'aula (Nascosto) -->
              <input type="hidden" name="id" value="<?php echo $id;?>">
              <div style="text-align:center;">
                <!-- Nome Aula -->
                <h4 name="nome_aula" style="font-weight: bold;"> <?php echo $nome; ?></h4>
                <!-- Utenti che possono prenotarla -->
                <!-- Studenti -->
                <div class="form-check" style="font-size: 17px;">
                  <b>Prenotabile da:</b>
                  <span style="vertical-align: middle;">
                    <div class="form-check form-check-inline" style="margin-left: 5px;">
                      <input class="form-check-input" type="checkbox" id="studenti_<? echo $id ?>" onclick="prenotabile(this.value)" value="S_<? echo $id ?>" <? if($studenti) echo "checked"?>>
                      <label class="form-check-label" for="studenti">Studenti</label>
                    </div>
                    <!-- Docenti -->
                    <div class="form-check form-check-inline" style="margin-left: -5px;">
                      <input class="form-check-input" type="checkbox" id="docenti_<? echo $id ?>" onclick="prenotabile(this.value)" value="D_<? echo $id ?>" <? if($docenti) echo "checked"?>>
                      <label class="form-check-label" for="docenti">Docenti</label>
                    </div>
                  <span>
                </div>
                <hr>
                <!-- Modifica dati Aula -->
                <center>
                  <div class="col-10">
                  <!-- <div> -->
                    <!-- Nome Aula -->
                    <div class="form-group">
                      <label for="nome" style="font-weight:bold; font-size:17px;">Nome Aula</label>
                      <input type="text" class="form-control" id="nome" name="nome" placeholder="Inserisci il nome dell'aula" required value="<? echo $nome ?>">
                    </div>
                    <!-- Descrizione Aula -->
                    <div class="form-group">
                      <label for="desc" style="font-weight:bold; font-size:17px;">Descrizione</label>
                      <textarea type="text" class="form-control" id="desc" name="desc" placeholder="Inserisci una breve descrizione (es. Il materiale presente)" required><? echo $descrizione ?></textarea>
                    </div>
                    <!-- Numero di posti dell'aula -->
                    <div class="form-group">
                      <label for="numero" style="font-weight:bold; font-size:17px;">Numero Posti</label>
                      <input type="text" class="form-control" id="numero" name="numero" placeholder="Inserisci il numero di posti disponibili" required value="<? echo $posti ?>">
                    </div>
                    <!-- Note della descrizione dell'aula -->
                    <div class="form-group">
                      <label for="note" style="font-weight:bold; font-size:17px;">Note</label>
                      <input type="text" class="form-control" id="note" placeholder="Inserisci eventuali note" name="note" value="<? echo $note ?>">
                    </div>
                    <!-- Id Aula -->
                    <input name="id_aula" style="display:none;" value="<? echo $id ?>"></input>
                    <!-- Salva le modifiche -->
                    <input type="button" class="btn btn-primary" name="bottone" onclick="aggiorna(this.id)" id = "<? echo $conta ?>" value="Salva">
                    <!-- Banner errori/comunicazioni -->
                    <div class="alert alert-success" role="alert" style="display:none; margin-top: 10px; font-size:17px;" name="banner">
                      Dati dell'aula aggiornati con successo
                    </div>
                  </div>
                </center>
                <hr>
                <!-- Bottone per caricare le immagini -->
                <span class="btn btn-default btn-file">
                  Seleziona immagine
                  <br>
                  <input type="file" name="file[]" multiple style="display:inline;"/>
                </span>
                <br>
                <!-- Pulsante di conferma -->
                <input type="submit" id="invia" class="btn btn-success" value="Carica" style="margin-top: 10px;"/>
              </div>
            </form>

            <!-- Tabella Aula - Immagini -->
            <div class="table-responsive">
              <table class="table table-bordered">
                <!-- Intestazione Tabella -->
                <thead>
                  <tr>
                    <th>Nome</th>
                    <th>Immagine</th>
                    <th>Elimina</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // Query per recuperare il path delle immagini
                    $query="SELECT pathImmagine FROM foto WHERE idAula = " . $id;

                    // Se la query viene eseguita con successo
                    if($ris2 = mysqli_query($conn,$query))
                    {
                      // Ciclo sui path
                      while ($row2=mysqli_fetch_row($ris2))
                      {
                  ?>
                  <tr>
                    <!-- Form per eliminare la foto -->
                    <form action="elimina.php" class="eliminare" method="post">
                      <!-- Path immagine -->
                      <td><?php echo $row2[0] ?></td>
                      <!-- Immagine -->
                      <td>
                        <center>
                          <input type="hidden" value="<?php echo $row2[0] ?>" name="delete_file" /><img style="cursor:pointer;" onclick="mostra('<?php echo $row2[0]; ?>')" src="<?php echo ("../img/aule/" . $row2[0])?>" width="150" height="150">
                        </center>
                      </td>
                      <!-- Pulsante per eliminare (Submit Form) -->
                      <td>
                        <center>
                          <input type="submit" value="Elimina Foto" class="btn btn-danger"/>
                        </center>
                      </td>
                    </form>
                  </tr>
                  <?php
                    }
                  ?>
                </tbody>
              </table>
          	</div>
          </div>
          <hr>
          <?php
                }
                else
                  echo "Errore";
                }
              }
              else
                echo "Errore";

              // Chiudo la connessione
              mysqli_close($conn);
            ?>
        </div>
      </div>
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
