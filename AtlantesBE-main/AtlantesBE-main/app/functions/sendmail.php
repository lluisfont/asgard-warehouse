<?php
include_once( __DIR__ . '/../.env.php');

class SendMail{
    public function enviarMail($to, $tocc, $subject, $preview, $cuerpo, $attachments = false){
        /*
        $to=array(
            'louizloayza@gmail.com'
        );
        */
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("sistemas@kpogroup.bo", "ASGARD - KPO");
        $email->setSubject(ASUNTO_MAIL.$subject);

        $mailsbc=[
          "lloayza@kpogroup.bo",
        ];

        $mails = self::normalizarCorreos($to);
        $mailscc = self::normalizarCorreos($tocc);
        $mailsbc = self::normalizarCorreos($mailsbc);

        if (ENTORNO != 'prod') {
            $mails = self::filtrarDominio($mails, 'kpogroup.bo');
            $mailscc = self::filtrarDominio($mailscc, 'kpogroup.bo');
            $mailsbc = self::filtrarDominio($mailsbc, 'kpogroup.bo');
        }

        $mailscc = array_values(array_diff($mailscc, $mails));
        $mailsbc = array_values(array_diff($mailsbc, $mails, $mailscc));

        for($tt=0;$tt<count($mails);$tt++){
            $email->addTo($mails[$tt]);
        }

        for($tt=0;$tt<count($mailscc);$tt++){
            $email->addCc($mailscc[$tt]);
        }

        for($tt=0;$tt<count($mailsbc);$tt++){
            $email->addBcc($mailsbc[$tt]);
        }

        $email->addContent(
            "text/html", '<!doctype html>
            <html>
              <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>Simple Transactional Email</title>
                <style>
                  /* -------------------------------------
                      GLOBAL RESETS
                  ------------------------------------- */

                  /*All the styling goes here*/

                  img {
                    border: none;
                    -ms-interpolation-mode: bicubic;
                    max-width: 100%; 
                  }

                  body {
                    background-color: #f6f6f6;
                    font-family: sans-serif;
                    -webkit-font-smoothing: antialiased;
                    font-size: 14px;
                    line-height: 1.4;
                    margin: 0;
                    padding: 0;
                    -ms-text-size-adjust: 100%;
                    -webkit-text-size-adjust: 100%; 
                  }

                  table {
                    border-collapse: separate;
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                    width: 100%; }
                    table td {
                      font-family: sans-serif;
                      font-size: 14px;
                      vertical-align: top; 
                  }

                  /* -------------------------------------
                      BODY & CONTAINER
                  ------------------------------------- */

                  .body {
                    background-color: #f6f6f6;
                    width: 100%; 
                  }

                  /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                  .container {
                    display: block;
                    margin: 0 auto !important;
                    /* makes it centered */
                    max-width: 800px;
                    padding: 10px;
                    width: 800px; 
                  }

                  /* This should also be a block element, so that it will fill 100% of the .container */
                  .content {
                    box-sizing: border-box;
                    display: block;
                    margin: 0 auto;
                    max-width: 800px;
                    padding: 10px; 
                  }

                  /* -------------------------------------
                      HEADER, FOOTER, MAIN
                  ------------------------------------- */
                  .main {
                    background: #ffffff;
                    border-radius: 3px;
                    width: 100%; 
                  }

                  .wrapper {
                    box-sizing: border-box;
                    padding: 20px; 
                  }

                  .content-block {
                    padding-bottom: 10px;
                    padding-top: 10px;
                  }

                  .footer {
                    clear: both;
                    margin-top: 10px;
                    text-align: center;
                    width: 100%; 
                  }
                    .footer td,
                    .footer p,
                    .footer span,
                    .footer a {
                      color: #999999;
                      font-size: 12px;
                      text-align: center; 
                  }

                  /* -------------------------------------
                      TYPOGRAPHY
                  ------------------------------------- */
                  h1,
                  h2,
                  h3,
                  h4 {
                    color: #000000;
                    font-family: sans-serif;
                    font-weight: 400;
                    line-height: 1.4;
                    margin: 0;
                    margin-bottom: 30px; 
                  }

                  h1 {
                    font-size: 35px;
                    font-weight: 300;
                    text-align: center;
                    text-transform: capitalize; 
                  }

                  p,
                  ul,
                  ol {
                    font-family: sans-serif;
                    font-size: 14px;
                    font-weight: normal;
                    margin: 0;
                    margin-bottom: 15px; 
                  }
                    p li,
                    ul li,
                    ol li {
                      list-style-position: inside;
                      margin-left: 5px; 
                  }

                  a {
                    color: #3498db;
                    text-decoration: underline; 
                  }

                  /* -------------------------------------
                      BUTTONS
                  ------------------------------------- */
                  .btn {
                    box-sizing: border-box;
                    width: 100%; }
                    .btn > tbody > tr > td {
                      padding-bottom: 15px; }
                    .btn table {
                      width: auto; 
                  }
                    .btn table td {
                      background-color: #ffffff;
                      border-radius: 5px;
                      text-align: center; 
                  }
                    .btn a {
                      background-color: #ffffff;
                      border: solid 1px #3498db;
                      border-radius: 5px;
                      box-sizing: border-box;
                      color: #3498db;
                      cursor: pointer;
                      display: inline-block;
                      font-size: 14px;
                      font-weight: bold;
                      margin: 0;
                      padding: 12px 25px;
                      text-decoration: none;
                      text-transform: capitalize; 
                  }

                  .btn-primary table td {
                    background-color: #3498db; 
                  }

                  .btn-primary a {
                    background-color: #3498db;
                    border-color: #3498db;
                    color: #ffffff; 
                  }

                  /* -------------------------------------
                      OTHER STYLES THAT MIGHT BE USEFUL
                  ------------------------------------- */
                  .last {
                    margin-bottom: 0; 
                  }

                  .first {
                    margin-top: 0; 
                  }

                  .align-center {
                    text-align: center; 
                  }

                  .align-right {
                    text-align: right; 
                  }

                  .align-left {
                    text-align: left; 
                  }

                  .clear {
                    clear: both; 
                  }

                  .mt0 {
                    margin-top: 0; 
                  }

                  .mb0 {
                    margin-bottom: 0; 
                  }

                  .preheader {
                    color: transparent;
                    display: none;
                    height: 0;
                    max-height: 0;
                    max-width: 0;
                    opacity: 0;
                    overflow: hidden;
                    mso-hide: all;
                    visibility: hidden;
                    width: 0; 
                  }

                  .powered-by a {
                    text-decoration: none; 
                  }

                  hr {
                    border: 0;
                    border-bottom: 1px solid #f6f6f6;
                    margin: 20px 0; 
                  }

                  /* -------------------------------------
                      RESPONSIVE AND MOBILE FRIENDLY STYLES
                  ------------------------------------- */
                  @media only screen and (max-width: 620px) {
                    table.body h1 {
                      font-size: 28px !important;
                      margin-bottom: 10px !important; 
                    }
                    table.body p,
                    table.body ul,
                    table.body ol,
                    table.body td,
                    table.body span,
                    table.body a {
                      font-size: 16px !important; 
                    }
                    table.body .wrapper,
                    table.body .article {
                      padding: 10px !important; 
                    }
                    table.body .content {
                      padding: 0 !important; 
                    }
                    table.body .container {
                      padding: 0 !important;
                      width: 100% !important; 
                    }
                    table.body .main {
                      border-left-width: 0 !important;
                      border-radius: 0 !important;
                      border-right-width: 0 !important; 
                    }
                    table.body .btn table {
                      width: 100% !important; 
                    }
                    table.body .btn a {
                      width: 100% !important; 
                    }
                    table.body .img-responsive {
                      height: auto !important;
                      max-width: 100% !important;
                      width: auto !important; 
                    }
                  }

                  /* -------------------------------------
                      PRESERVE THESE STYLES IN THE HEAD
                  ------------------------------------- */
                  @media all {
                    .ExternalClass {
                      width: 100%; 
                    }
                    .ExternalClass,
                    .ExternalClass p,
                    .ExternalClass span,
                    .ExternalClass font,
                    .ExternalClass td,
                    .ExternalClass div {
                      line-height: 100%; 
                    }
                    .apple-link a {
                      color: inherit !important;
                      font-family: inherit !important;
                      font-size: inherit !important;
                      font-weight: inherit !important;
                      line-height: inherit !important;
                      text-decoration: none !important; 
                    }
                    #MessageViewBody a {
                      color: inherit;
                      text-decoration: none;
                      font-size: inherit;
                      font-family: inherit;
                      font-weight: inherit;
                      line-height: inherit;
                    }
                    .btn-primary table td:hover {
                      background-color: #34495e !important; 
                    }
                    .btn-primary a:hover {
                      background-color: #34495e !important;
                      border-color: #34495e !important; 
                    } 
                  }

                </style>
              </head>
              <body>
                <span class="preheader">'.$preview.'</span>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
                  <tr>
                    <td>&nbsp;</td>
                    <td class="container">
                      <div class="content">

                        <!-- START CENTERED WHITE CONTAINER -->
                        <table role="presentation" class="main">

                          <!-- START MAIN CONTENT AREA -->
                          <tr>
                            <td class="wrapper">
                              <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <img src="'.image_files.'logo_asgard_warehouse.png" width="100%" height="100%" />
                                    </td>
                                </tr>
                                <tr>
                                  <td>
                                    '.$cuerpo.'
                                    <p>Saludos Cordiales.</p>
                                  </td>
                                </tr>
                                
                                <!--
                                <tr>
                                    <td align="center">
                                        <img src="'.image_files.'banner.png" width="250" />
                                    </td>
                                </tr>
                                -->
                              </table>
                            </td>
                          </tr>

                        <!-- END MAIN CONTENT AREA -->
                        </table>
                        <!-- END CENTERED WHITE CONTAINER -->

                        <!-- START FOOTER -->
                        <div class="footer">
                          <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td class="content-block">
                                <span class="apple-link">Este correo electrónico se genera automáticamente. Por favor, no respondas a él. Si necesitas ayuda adicional, por favor, comuníquese con el coordinador asignado a su empresa.</span>
                                <br />
                                <br> Copyright &copy; 2022-'.date("Y").' ASGARD. Todos los derechos reservados.</a>.
                              </td>
                            </tr>
                            <tr>
                              <td class="content-block powered-by">
                                Desarrollado por <a href="https://kpogroup.bo/">KPO Group</a>.
                              </td>
                            </tr>
                          </table>
                        </div>
                        <!-- END FOOTER -->

                      </div>
                    </td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
              </body>
            </html>'
        );

        $sendgrid = new \SendGrid(SENDGRID_API_KEY);
        try {
            if ($attachments) {
              $email->addAttachments($attachments);
            }
            $response = $sendgrid->send($email);
            return array(
                'statusCode'=>$response->statusCode(),
                'headers'=>$response->headers(),
                'body'=>$response->body()
            );
            //return $response->statusCode();
            /*
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
             *
             */
        } catch (Exception $e) {
            return array(
                'statusCode'=>400,
                'headers'=>'',
                'body'=>'Caught exception: '. $e->getMessage()
            );
            /*
            echo 'Caught exception: '. $e->getMessage() ."\n";
             *
             */
        }


    }

    private function normalizarCorreos($correos){
        $tmp = array();
        foreach ($correos as $correo) {
            $tmp[] = strtolower(trim($correo));
        }
        return array_values(array_unique($tmp));
    }

    private function filtrarDominio($correos, $dominio){
        $resultado = array();
        $sufijo = '@' . strtolower($dominio);

        foreach ($correos as $correo) {
            $correo = strtolower(trim($correo));
            if (substr($correo, -strlen($sufijo)) === $sufijo) {
                $resultado[] = $correo;
            }
        }

        return array_values($resultado);
    }
}


/*
<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
    <tbody>
      <tr>
        <td align="left">
          <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td> <a href="http://htmlemail.io" target="_blank">Call To Action</a> </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
 *
 */
