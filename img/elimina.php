<?php
  require '../conn/conn.php';

  $id=$_POST["id"];

	$sql= "SELECT pathImmagine FROM foto WHERE idAula = '$id'";
	$ris=$conn->query($sql);
  while($record = $ris->fetch_assoc())
  {
  	$filename = $record['pathImmagine'];    
    if (file_exists("../img/aule/" . $filename))
    {
        unlink("../img/aule/" . $filename);
    }
    $sql= "DELETE FROM foto WHERE pathImmagine = '" . $filename . "';";
    $riso=$conn->query($sql);
  }
  $conn->query("DELETE FROM aule WHERE id = '$id';");
	
  mysqli_close($conn);
  header("location: crudAule.php");
?>