<?php
  // Avvio la sessione
  session_start();

  // Recupero i dati dell'utente dalle variabili di sessione
  // e li metto con iniziale maiuscola
  // Nome
  $nome=ucfirst(strtolower($_SESSION['nome']));
  // Cognome
  $cognome=ucfirst(strtolower($_SESSION['cognome']));
  $cognome_spazi=ucfirst(strtolower($_SESSION['cognome_spazi']));

  // Se Nome e Cognome non sono nulli
  if($nome != "" && $cognome != "")
  {
    // Aggiorno le variabili di sessione con i valori sistemati
    $_SESSION['nome']=$nome;
    $_SESSION['cognome']=$cognome;
  }
  else
  {
    // Altrimenti redireziono alla pagina di login
    header("Location: ../");
    die();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMART BOX | Imposta email e password</title>
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <!-- CUSTOM STYLE -->
    <link rel="stylesheet" href="../style/benvenuto.css" type="text/css">
    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <script>
      // Funzione per il focus sulla textbox
      function posiziona(quale_text)
      {
        document.getElementById(quale_text).select();
      }

      // Funzione per validare l'email inserita
      function validateEmail(email)
      {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
      }

      // Funzione per il controllo dei dati inseriti dall'utente
      function controlla()
      {
        // Recupero i dati inseriti dall'utente
        // Email
        var email=document.getElementById('email').value;
        // Conferma Email
        var email_conferma=document.getElementById('email_conf').value;
        // Password
        var password=document.getElementById('pwd').value;
        // Conferma Password
        var password_conferma=document.getElementById('pwd_conf').value;
        // Inizializzo i flag di controllo a false
        var email_controllo=false;
        var password_controllo=false;

        // Se le due email inserite non coincidono o la prima ha valore nullo
        if(email!==email_conferma||email==="")
        {
          // Mostro il banner di errore
          document.getElementById('email_mex').style.display='block';
          // Metto il flag di controllo a false
          email_controllo=false;
          // Focus sulla textbox dell'email
          posiziona('email');
        }
        else
        {
          // Se l'email è valida
          if(validateEmail(email))
          {
            // Nascondo il banner di errore
            document.getElementById('email_non_corretta').style.display='none';
            // Flag di controllo a true
            email_controllo=true;
          }
          else
          {
            // Mostro il banner di errore
            document.getElementById('email_non_corretta').style.display='block';
            // Flag di controllo a false
            email_controllo=false;
          }
          // Nascondo il banner di errore dell'email
          document.getElementById('email_mex').style.display='none';
        }

        // Se le due password inserite non coincidono o la prima ha valore nullo
        if(password!==password_conferma||password==="")
        {
          // Mostro il banner di errore
          document.getElementById('password_mex').style.display='block';
          // Metto il flag di controllo a false
          password_controllo=false;
          // Focus sulla textbox della password
          posiziona('pwd');
        }
        else
        {
          // Se la password è lunga meno di 8 caratteri
          if(password.length<8)
          {
            // Mostro il banner di errore
            document.getElementById('password_lun').style.display='block';
            // Metto il flag di controllo a false
            password_controllo=false;
          }
          else
          {
            // Nascondo il banner di errore
            document.getElementById('password_lun').style.display='none';
            // Metto il flag di controllo a true
            password_controllo=true;
          }
          // Nascondo il banner di errore della password
          document.getElementById('password_mex').style.display='none';
        }

        // Se i due flag di controllo hanno valore true
        if(password_controllo && email_controllo)
        {
          // Procedo con il Submit della form
          document.getElementById("mia_form").submit();
        }
      }
    </script>
  </head>
  <!-- Focus sull'email al caricamento della pagina -->
  <body onload="posiziona('email');">
    <!-- Card Container -->
    <div class="container card" id="contenitore">
      <!-- Scrivo nome e cognome dell'utente -->
      <h4 id="titolo">Benvenuto <span id="nome_titolo"><?php echo ucwords($nome) . " " . ucwords($cognome_spazi)?></span></h4>
      <h5 class="lead" id="sottotitolo">Per garantire la sicurezza del tuo account devi inserire una <span class="grasse">password</span> e una <span class="grasse">email</span></h5>
      <hr id="intestazione">
      <!-- Form per la raccolta dell'email e della password -->
      <form action="./preLogin.php" method="post" class="distanza_basso" id="mia_form">
        <!-- Input della email -->
        <div class="form-group row distanza_basso">
          <label class="col-md-3 offset-md-2 col-form-label etichetta">Email:</label>
          <div class="col-md-5">
            <input type="email" class="form-control cornice" name="email" id="email" placeholder="Inserisci la tua email..." required>
          </div>
        </div>
        <!-- Input della mail (Conferma) -->
        <div class="form-group row">
          <label class="col-md-3 offset-md-2 col-form-label etichetta">Conferma Email:</label>
          <div class="col-md-5">
            <input type="email" class="form-control cornice"  name="email_conf" id="email_conf" placeholder="Conferma la tua email..." required>
          </div>
        </div>

        <hr id="linea">
        <!-- Input della password -->
        <div class="form-group row distanza_basso">
         <label class="col-md-3 offset-md-2 col-form-label etichetta">Password:</label>
         <div class="col-md-5">
           <input type="password" class="form-control cornice" name="pwd" id="pwd" placeholder="Inserisci la tua password..." required>
         </div>
        </div>
        <!-- Input della password (Conferma) -->
        <div class="form-group row distanza_basso">
          <label class="col-md-3 offset-md-2 col-form-label etichetta">Conferma password:</label>
          <div class="col-md-5">
            <input type="password" class="form-control cornice" name="pwd_conf" id="pwd_conf" placeholder="Conferma la tua password..." required>
          </div>
        </div>
        <!-- Pulsante di Submit della form "vero" -->
        <input type="submit" name="formSettaggioPassword" style="display:none"></input>
      </form>
      <!-- Pulsante di submit associato al controllo -->
      <center>
        <button class="btn btn-primary distanza_basso" onclick="controlla()">Continua</button>
      </center>
      <!-- Banner di errore delle email - Email inserite non coincidenti-->
      <div class="alert alert-danger attenzione" role="alert" style="display:none;" id="email_mex">
        <span class='alert-link'>Attenzione</span>: Le email inserite non coincidono
      </div>
      <!-- Banner di errore delle password - Password inserite non coincidenti-->
      <div class="alert alert-danger attenzione" role="alert" style="display:none;" id="password_mex">
        <span class='alert-link'>Attenzione</span>: Le password inserite non coincidono
      </div>
      <!-- Banner di errore delle email - Email inserite non valide-->
      <div class="alert alert-danger attenzione" role="alert" style="display:none;" id="email_non_corretta">
        <span class='alert-link'>Attenzione</span>: Le email inserite non sono valide
      </div>
      <!-- Banner di errore delle password - Password inserite non valide-->
      <div class="alert alert-warning attenzione" role="alert" style="display:none;" id="password_lun">
        <span class='alert-link'>Attenzione</span>: La password deve essere lunga almeno 8 caratteri
      </div>
    </div>
    <script>
    var check = false;
    (function(a,b){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
    if(check)
    {
      var cont=document.getElementById('contenitore');
      cont.style.marginTop="10px";
      cont.style.marginBottom="10px";
    }
    </script>
  </body>
</html>
