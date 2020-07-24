<?php
  // Connessione al DataBase
  require "../../conn/conn.php";

  // Avvio la sessione
  session_start();

  // Se viene passato in GET l'hash della matricola dell'utente
  if(isset($_GET['matricola_hash']))
  {
    // Lo recupero
    $hash_matricola = $_GET['matricola_hash'];
    // Query per recuperare i cognome e matricola dell'utente attraverso l'hash della matricola
    $sqlquery="SELECT cognome, matricola FROM utenti WHERE SHA2(matricola, 256) = '$hash_matricola'";
    $result = mysqli_query($conn,$sqlquery);
    $tutto=mysqli_fetch_assoc($result);
    // Estraggo il cognome
    $cognome=$tutto['cognome'];
    // Estraggo la matricola
    $matricola=$tutto['matricola'];
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMART BOX | Reset Password</title>
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

    <!-- CUSTOM STYLE -->
    <link rel="stylesheet" href="../../style/accesso.css" type="text/css">
    <!-- JS -->
    <script src="../../js/script.js"></script>
    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <!-- Card Container -->
        <div class="card col-lg-4 offset-lg-4 col-md-6 offset-md-3" id="aspetto_login">
          <h4 class="lead" id="titolo">SMART BOX<h4>
          <hr>
          <!-- Form di inserimento della nuova password -->
          <form action="./password_update.php" method="post" onsubmit="return psw_check()">
              <h4 id="etichetta">Utente: <span id="colora"><?php echo strtolower($cognome) . "." . $matricola ?></span></h4>
              <!-- Nuova password -->
              <div class="input-group">
                <input type="password" class="form-control casella" id="inlineFormInputGroup" placeholder="Inserisci la nuova password..." name="password" minlength = 8 required autofocus>
              </div>
              <!-- Reinserimento della nuova password -->
              <div class="input-group" id="textbox">
                <input type="password" class="form-control casella" id="inlineFormInputGroup" placeholder="Reinserisci la nuova password..." name="password_2" minlength = 8 required>
              </div>
              <!-- Messaggio di errore nel caso in cui le password fossero diverse -->
              <div class="alert alert-danger" role="alert" id="messaggio" style="display:none">
                <b>Attenzione!</b> Le nuove password inserite devono essere uguali!
              </div>
              <!-- Pulsante per il submit della form -->
              <center><button type="submit" class="btn btn-primary" id="bottone">Invia</button></center>
          </form>
          <h4 class="lead" id="problemi">Problemi con l'accesso?<br>Scrivi <a href="mailto:domenicogaeni@gmail.com" target="_blank">domenicogaeni@gmail.com</a></h4>
        </div>
      </div>
    </div>
    <script>
      var check = false;
      (function(a,b){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
      if(check){
        document.getElementById('aspetto_login').style.marginTop="55px";
        document.getElementById('aspetto_login').style.marginBottom="55px";
      }
    </script>
  </body>
</html>
