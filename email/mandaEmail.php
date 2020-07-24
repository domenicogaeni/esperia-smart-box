<?php
    function sendMail($username, $useremail, $hash_matricola)
    {
        require ("sendgrid-php/sendgrid-php.php");

        $sendgrid = new SendGrid("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");
        $email = new SendGrid\Email();
        $email->addTo("$useremail")
              ->setFrom("no-reply@esperiasmartbox.org")
              ->setFromName("Esperia SmartBox")
              ->setSubject("SmartBox - Resetta la password")
              ->setHtml('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html data-editor-version="2" class="sg-campaigns" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" /><!--[if !mso]><!-->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" /><!--<![endif]-->
    <!--[if (gte mso 9)|(IE)]>
    <xml>
    <o:OfficeDocumentSettings>
    <o:AllowPNG/>
    <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
    <!--[if (gte mso 9)|(IE)]>
    <style type="text/css">
      body {width: 600px;margin: 0 auto;}
      table {border-collapse: collapse;}
      table, td {mso-table-lspace: 0pt;mso-table-rspace: 0pt;}
      img {-ms-interpolation-mode: bicubic;}
    </style>
    <![endif]-->

    <style type="text/css">
      body, p, div {
        font-family: arial;
        font-size: 14px;
      }
      body {
        color: #000000;
      }
      body a {
        color: #1188e6;
        text-decoration: none;
      }
      p { margin: 0; padding: 0; }
      table.wrapper {
        width:100% !important;
        table-layout: fixed;
        -webkit-font-smoothing: antialiased;
        -webkit-text-size-adjust: 100%;
        -moz-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
      }
      img.max-width {
        max-width: 100% !important;
      }
      .column.of-2 {
        width: 50%;
      }
      .column.of-3 {
        width: 33.333%;
      }
      .column.of-4 {
        width: 25%;
      }
      @media screen and (max-width:480px) {
        .preheader .rightColumnContent,
        .footer .rightColumnContent {
            text-align: left !important;
        }
        .preheader .rightColumnContent div,
        .preheader .rightColumnContent span,
        .footer .rightColumnContent div,
        .footer .rightColumnContent span {
          text-align: left !important;
        }
        .preheader .rightColumnContent,
        .preheader .leftColumnContent {
          font-size: 80% !important;
          padding: 5px 0;
        }
        table.wrapper-mobile {
          width: 100% !important;
          table-layout: fixed;
        }
        img.max-width {
          height: auto !important;
          max-width: 480px !important;
        }
        a.bulletproof-button {
          display: block !important;
          width: auto !important;
          font-size: 80%;
          padding-left: 0 !important;
          padding-right: 0 !important;
        }
        .columns {
          width: 100% !important;
        }
        .column {
          display: block !important;
          width: 100% !important;
          padding-left: 0 !important;
          padding-right: 0 !important;
          margin-left: 0 !important;
          margin-right: 0 !important;
        }
      }
    </style>
    <!--user entered Head Start-->
    
     <!--End Head user entered-->
  </head>
  <body>
    <center class="wrapper" data-link-color="#1188e6" data-body-style="font-size: 14px; font-family: arial; color: #000000; background-color: #ececec;">
      <div class="webkit">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="wrapper" bgcolor="#ececec">
          <tr>
            <td valign="top" bgcolor="#ececec" width="100%">
              <table width="100%" role="content-container" class="outer" align="center" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td width="100%">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td>
                          <!--[if mso]>
                          <center>
                          <table><tr><td width="600">
                          <![endif]-->
                          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width:600px;" align="center">
                            <tr>
                              <td role="modules-container" style="padding: 0px 0px 0px 0px; color: #000000; text-align: left;" bgcolor="#FFFFFF" width="100%" align="left">
                                
    <table class="module preheader preheader-hide" role="module" data-type="preheader" border="0" cellpadding="0" cellspacing="0" width="100%"
           style="display: none !important; mso-hide: all; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0;">
      <tr>
        <td role="module-content">
          <p>This text will be used as a preview snippet in most modern email clients.</p>
        </td>
      </tr>
    </table>
  
    <table class="module" role="module" data-type="text" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
      <tr>
        <td style="padding:020px 0px 20px 0px;background-color:#dc1212;"
            height="100%"
            valign="top"
            bgcolor="#dc1212">
            <div style="text-align: center;"><span style="color:#FFFFFF;"><span style="font-size: 48px;"><span style="font-family: times new roman,times,serif;">SmartBox</span></span>
                                      </span>
                                    </div>
        </td>
      </tr>
    </table>
  
    <table class="wrapper" role="module" data-type="image" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
      <tr>
        <td style="font-size:6px;line-height:10px;padding:0px 0px 0px 0px;" valign="top" align="center">
          <img class="max-width" border="0" style="display:block;color:#000000;text-decoration:none;font-family:Helvetica, arial, sans-serif;font-size:16px;max-width:70% !important;width:70%;height:auto !important;" src="https://esperiasmartbox.altervista.org/email/mail_iconi.jpg" alt="" width="420">
        </td>
      </tr>
    </table>
  
    <table class="module" role="module" data-type="text" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
      <tr>
        <td style="padding:20px 30px 35px 30px;background-color:#ffffff;text-align:inherit;"
            height="100%"
            valign="top"
            bgcolor="#ffffff">
            <div style="text-align: center;"><span style="color:#626262;"><span style="font-size: 36px;"><span style="font-family: times new roman,times,serif;">Reimposta la Password</span></span>
                                      </span>
                                    </div>
        </td>
      </tr>
    </table>
  <table class="module" role="module" data-type="code" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
      <tr>
        <td height="100%" valign="top">
          <div style="text-align: center;"><span style="color:#626262;"><span style="font-size: 16px;"><span style="font-family: arial,helvetica,sans-serif;">Ciao <tt>' . $username . '</tt>,<br>
                              per reimpostare la password dell\'account SmartBox clicca sul link seguente: <br></span></span>
                                      </span>
                                    </div>
        </td>
      </tr>
    </table><table border="0" cellPadding="0" cellSpacing="0" class="module" data-role="module-button" data-type="button" role="module" style="table-layout:fixed" width="100%"><tbody><tr><td align="center" class="outer-td" style="padding:40px 20px 70px 20px"><table border="0" cellPadding="0" cellSpacing="0" class="button-css__deep-table___2OZyb wrapper-mobile" style="text-align:center"><tbody><tr><td align="center" bgcolor="#e0122d" class="inner-td" style="-webkit-border-radius:0px;-moz-border-radius:0px;border-radius:0px;font-size:21px;text-align:center;background-color:inherit"><a style="background-color:#e0122d;height:px;font-size:21px;font-family:Helvetica, Arial, sans-serif;color:#FFFFFF;padding:12px 18px 12px 18px;text-decoration:none;-webkit-border-radius:0px;-moz-border-radius:0px;border-radius:0px;border:1px solid #fc6265;display:inline-block;border-color:#ffffff" href="https://www.esperiasmartbox.altervista.org/login/recupera/reset.php?matricola=' . $hash_matricola . '" target="_blank">Recupera Password</a></td></tr></tbody></table></td></tr></tbody></table><table class="module" role="module" data-type="code" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
      <tr>
        <td height="100%" valign="top">
          <div class="contentEditable" style="padding:20px 10px 0 0;margin:0;font-family:Helvetica, Arial, sans-serif;font-size:15px;line-height:150%;">
<p style="color:#626262;text-align:center;">Per qualsiasi esigenza o dubbio, non esitare a scrivere allo staff.</p><p style="text-align:center;">La nostra mail è <a href="mailto:domenicogaeni@gmail.com">domenicogaeni@gmail.com</a></p><p>
</p></div>
        </td>
      </tr>
    </table>
                              </td>
                            </tr>
                          </table>
                          <!--[if mso]>
                          </td></tr></table>
                          </center>
                          <![endif]-->
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>
    </center>
  </body>
</html>
');
        $sendgrid->send($email);
    }
    //sendMail("FabioPalazzi", "feb.palazzi@gmail.com");
?>
