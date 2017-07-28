<?php

// GET Params
// $fallback (string, optional)
//   Fallback command to use. Defaults to 'g'.
// $defaultCommand (string, optional)
//   Default command to use. Default to none.
// $debug (string, optional)
//   If set to '1', then will run WS in debug mode. (The final redirection URL
//   will be printed instead of actually redirecting the browser.
//

require('../sitevars.php');

$fallback = isset($_REQUEST['fallback']) ? $_REQUEST['fallback'] : null;
if (!$fallback || !strlen($fallback)) {
  $fallback = 'g';
}

$defaultCommand = isset($_REQUEST['defaultCommand']) ? $_REQUEST['defaultCommand'] : null;
if (!$defaultCommand || !strlen($defaultCommand)) {
  $defaultCommand = '';
}

$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : null;
if ($debug !== '1') {
  $debug = null;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Other meta tags should go below this line -->

    <title>Wolverine Search</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <style>
      html, body {
        margin: 0;
      }
      
      html, body, .container-table {
        height: 100%;
      }
      
      .container-table {
        display: table;
      }
      
      .vertical-center-row {
        display: table-cell;
        vertical-align: middle;
      }
      
      #ws-search-bar {
        width: 100%;
        margin-bottom: 12px;
      }
      
      #ws-submit-button {
        width: 100%;
      }
      
      .push {
        height: 50px;
      }
      
      .footer-container {
        width: 100%;
        position: absolute;
        bottom: 0;
      }
      .footer {
        height: 50px;
        width: 100%;
      }
      .footer {
        background-color: #f4f4f4;
      }
      .footer-text {
        margin-top: 4px;
        margin-bottom: 4px;
      }
    </style>
  </head>

  <body>
    <div class="container container-table">

      <div class="row vertical-center-row text-center">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
          <h1>Wolverine Search</h1>
          <form action="./search" method="GET">
            <div class="row">
              <div class="col-xs-12 col-sm-12">
                <input class="form-control" id="ws-search-bar" name="q" type="search" placeholder="Type a command..." value="<?php echo $defaultCommand; ?>" autofocus />
              </div>
              <div class="col-xs-8 col-xs-offset-2 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                <input class="btn btn-primary" id="ws-submit-button" type="submit" value="Go!" />
              </div>
            </div>
            <input type="hidden" name="fallback" value="<?php echo $fallback; ?>" />
            <?php
              if ( $debug ) {
            ?>
            <input type="hidden" name="debug" value="1" />
            <?php
              }
            ?>
          </form>
        </div>
        <div class="col-xs-12">
          <h3>First time here? <a href="<?php echo $_SITE['URL']; ?>/about">Read this.</a></h3>
        </div>
        <div class="col-xs-12">
          <p>You can find a full list of supported commands <a href="<?php echo $_SITE['URL']; ?>/list">here</a>.</p>
        </div>
        <div class="col-xs-12 push"></div>
      </div>

    </div><!-- /.container -->
    
    <div class="container-fluid footer-container">
      <div class="row">
        <footer class="footer text-center col-xs-12">
          <p class="footer-text">
            Developed for the <a href="https://www.umich.edu">University of Michigan</a> by Sesh Sadasivam. <a href="#">Want to contribute?</a>
          </p>
        </footer>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    
    <script>
      (function () {
        var textbox = document.getElementById('ws-search-bar');
        var textboxValueLength = textbox.value.length;
        textbox.setSelectionRange(textboxValueLength, textboxValueLength);
      })();
    </script>
  </body>
</html>
