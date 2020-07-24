function psw_check()
{
  var psw_1 = document.getElementsByName('password')[0].value;
  var psw_2 = document.getElementsByName('password_2')[0].value;

  if(psw_1 != psw_2)
  {
    document.getElementById("messaggio").style.display = "block";
    document.getElementsByName('password_2')[0].value = "";
    document.getElementsByName('password')[0].value = "";
    document.getElementsByName('password')[0].focus();
    return false;
  }
}
