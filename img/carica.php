<?php
  // Connessione al DataBase e controllo dell'utente
  require '../conn/conn.php';
  require '../controlla-accesso/index.php';

  // Se non è admin lo sbatto fuori!
  if($potere!='2')
  {
    header("location: ../logout.php");
    die();
  }

  // Reset del flag per la creazione di un'aula
  $_SESSION['messaggioCreazioneAula']="";

  // Se vengono passati parametri in POST
  if($_SERVER['REQUEST_METHOD']=="POST")
  {
    // Recupero i dati passati dalla Form
    // Nome Aula
    $nome=mysqli_real_escape_string($conn, $_POST['nome']);
    // Descrizione Aula
    $desc=mysqli_real_escape_string($conn, $_POST['desc']);
    // Numero dell'aula
    $numero=mysqli_real_escape_string($conn, $_POST['numero']);
    // Note
    $note=mysqli_real_escape_string($conn, $_POST['note']);
    
    if(strlen($nome)>0 && strlen($desc)>0 && strlen($numero)>0 && filter_var($numero, FILTER_VALIDATE_INT))
    {
      $target_dir = "aule/";
      foreach($_FILES['file']['name'] as $key=>$val)
      {
        $indirizzo = basename($_FILES["file"]["name"][$key]);
        echo "<script>alert(" . $indirizzo . ");</script>";
        $target_file = $target_dir . $indirizzo;
        //echo $target_file;// -> è il percorso relativo verso la directory img_smartBox
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["file"]["tmp_name"][$key]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['messaggioCreazioneAula']="<div class='alert alert-danger' role='alert'>Attenzione: il file non è un immagine</div>";
            $uploadOk = 0;
        }
        if (file_exists($target_file))
        {
            $_SESSION['messaggioCreazioneAula']="<div class='alert alert-danger' role='alert'>Attenzione: rinomina il nome dell'immagine</div>";
            $uploadOk = 0;
        }
        if ($uploadOk == 0)
        {
            $_SESSION['messaggioCreazioneAula']="<div class='alert alert-danger' role='alert'>Attenzione: le immagini non sono state caricate.</div>";
        }
        else
        {
            if (move_uploaded_file($_FILES["file"]["tmp_name"][$key], $target_file))
            {
                $sql="INSERT INTO aule (nome, descrizione, numeroPosti, note) VALUES ('" . $nome . "','" . $desc . "','" . $numero . "','" . $note . "');";
                if ($conn->query($sql))
                  $last_id = mysqli_insert_id($conn);
                else
                {
                  $_SESSION['messaggioCreazioneAula']="<div class='alert alert-danger' role='alert'>Attenzione: Aula non creata, riprova tra qualche minuto</div>";
                  header("location: ./index.php");
                  die();
                 // header("location: ./index.php");
                }

                $sql="INSERT INTO foto (pathImmagine, idAula) VALUES ('" . $indirizzo . "','" . $last_id . "');";

                if($conn->query($sql))
                  $_SESSION['messaggioCreazioneAula']="<div class='alert alert-success' role='alert'>Ottimo: Aula creata correttamente</div>";
                else
                  $_SESSION['messaggioCreazioneAula']="<div class='alert alert-danger' role='alert'>Attenzione: le immagini dell'aula non sono state create correttamente</div>";
                mysqli_close($conn);
                header("location: ./index.php");
                die();
            }
            else
            {
              $_SESSION['messaggioCreazioneAula']="<div class='alert alert-danger' role='alert'>Attenzione: c'è stato un problema con il caricamento delle immagini. Riprova tra qualche minuto.</div>";
              header("location: ./index.php");
            }
        }
        header("location: ./index.php");
      }
    }
      else
        $_SESSION['messaggioCreazioneAula']="<div class='alert alert-danger' role='alert'>Attenzione: Controlla che tutti i campi siano stati compilati correttamente</div>";
    header("location: ./index.php");
  }
  else
  {
    header("location: ../logout.php");
    die();
  }

?>
