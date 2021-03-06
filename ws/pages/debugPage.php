<?php

// GET Params
// $fallback (string, optional)
//   Fallback command to use. Defaults to 'g'.
// $defaultCommand (string, optional)
//   Default command to use. Default to none.
//

require_once('../__util__/Sitevars.php');

$fallback = isset($_REQUEST['fallback']) ? $_REQUEST['fallback'] : null;
if (!$fallback || !strlen($fallback)) {
  $fallback = Sitevars::FALLBACK_COMMAND;
}

$defaultCommand = isset($_REQUEST['defaultCommand']) ? $_REQUEST['defaultCommand'] : null;
if (!$defaultCommand || !strlen($defaultCommand)) {
  $defaultCommand = '';
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Other meta tags should go below this line -->

    <title>Debug | <?php echo Sitevars::SITE_NAME; ?></title>

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
        height: 80px;
      }
      
      .footer-container {
        width: 100%;
        /* position: absolute;
        bottom: 0; */
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

      #debug-text {
        color: red;
      }

      #request-stack-trace {
        margin-top: 1em;
      }
    </style>
  </head>

  <body>
    <div class="container container-table">

      <div class="row vertical-center-row text-center">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
          <h1><?php echo Sitevars::SITE_NAME; ?> <span id="debug-text">(DEBUG MODE)</span></h1>
          <form id="search-form">
            <div class="row">
              <div class="col-xs-12 col-sm-12">
                <input class="form-control" id="ws-search-bar" name="q" type="search" placeholder="Type a command..." value="<?php echo $defaultCommand; ?>" autofocus />
              </div>
            </div>
            <input type="hidden" id="ws-search-fallback" name="fallback" value="<?php echo $fallback; ?>" />
            <input type="hidden" name="debug" value="1" />
          </form>
        </div>
        <div class="col-xs-8 col-xs-offset-2 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
          <input class="btn btn-primary" id="ws-submit-button" type="submit" value="Go!" />
        </div>
        <div class="col-xs-12">
          <h3>
            Result:
            <div class="input-group">
              <input id="debug-search-result" class="form-control" type="text" disabled="disabled" />
              <span class="input-group-btn">
                <button class="btn btn-default" type="button" id="open-in-new-tab-btn">Open in new tab</button>
              </span>
            </div>
          </h3>
        </div>
        <div class="col-xs-12">
          <div class="row">
            <div class="col-xs-12">
              <a class="btn btn-default" role="button" data-toggle="collapse" href="#requestStackTraceDiv" aria-expanded="false" aria-controls="requestStackTraceDiv">
                Toggle Request Trace
              </a>
            </div>
            <div class="collapse col-xs-12" id="requestStackTraceDiv">
              <div class="well list-group" id="request-stack-trace">(Enter a query in the box above, and press the "Go" button.)</div>
            </div>
            </div>
          </div>
        </div>
        <div class="col-xs-12 push"></div>
      </div>

    </div><!-- /.container -->
    
    <div class="container-fluid footer-container">
      <div class="row">
        <footer class="footer text-center col-xs-12">
          <p class="footer-text">
            Developed for the <a href="https://www.umich.edu">University of Michigan</a> by Sesh Sadasivam. <a href="https://github.com/seshrs/wolverine-search/tree/master/ws">Want to contribute?</a>
          </p>
        </footer>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    
    <script>
      function displayTraceStackArray(trace_array) {
        /* Assumes each element of the array is of the shape:
         * {
         *   debug: boolean (should always be true)
         *   command: string (the command being executed)
         *   command_executor: array<string> ([ClassName, FunctionName])
         *   fallback_used: boolean (whether this was a fallback command)
         *   raw_query: The full query including the command
         *   request_trace_id: int An ordering of elements in the array starting from 0.
         *   query: string The query excluding the command.
         * }
         */
        
        // Sort the trace array
        trace_array.sort(function(trace_a, trace_b) {
          return trace_a.request_trace_id - trace_b.request_trace_id;
        });

        // Clear the old element
        var $trace_output_box = $('#request-stack-trace');
        $trace_output_box.html('');

        // Generate an element for each trace element
        var list_item_array = trace_array.map(function (stack_element) {
          // Clean the input if necessary
          if (stack_element.raw_query === '') {
            stack_element.raw_query = '(Empty Query)';
          }
          if (stack_element.query === '') {
            stack_element.query = '(Empty Query)';
          }
          if (stack_element.command === '') {
            stack_element.command = '(Empty Command)';
          }

          var $li_header =
            $('<h4/>')
              .addClass('list-group-item-header')
              .append(
                '' + stack_element.request_trace_id + '. ' + stack_element.raw_query
              );
          var $li_in_li =
            $('<div/>')
              .attr('border', 0)
              .append('<strong>Raw Query:</strong> ' + stack_element.raw_query + '<br>')
              .append('<strong>Command:</strong> ' + stack_element.command + '<br>')
              .append('<strong>Fallback Used:</strong> ' + stack_element.fallback_used + '<br>');
          var $li_body =
            $('<p/>')
              .addClass('list-group-item-text')
              .append($li_in_li);
          var $stack_body = $('<div/>').addClass('list-group-item').append($li_header).append($li_body);
          $trace_output_box.append($stack_body);
        });
      }

      function formSubmit() {
        $.get(
          '<?php echo Sitevars::DOMAIN_NAME; ?>/search/',
          {
            q: $('#ws-search-bar').val(),
            fallback: $('#ws-search-fallback').val(),
            debug: 1 
          }
        ).done(function (data) {
          console.log("Success, data: ", data);
          $('#debug-search-result').val(data.result);
          displayTraceStackArray(data.request_trace);
        }).fail(function () {
          $('#debug-search-result').val('Server Error');
          displayTraceStackArray([]);
        });

        // Prevent submission
        return false;
      }

      (function () {
        $('#search-form').submit(formSubmit);
        $('#ws-submit-button').click(formSubmit);

        $('#open-in-new-tab-btn').click(function () {
          var url = $('#debug-search-result').val();
          if (url === '') return;
          window.open(url, '_blank');
        });
      })();
    </script>
  </body>
</html>
