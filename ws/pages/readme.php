<?php

require('../sitevars.php');
require_once('../scripts/analytics.php');

// Analytics
Analytics::runAnalytics(Analytics::$USER_ACTION['ABOUT']);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Other meta tags should go below this line -->

    <title>Readme | Wolverine Search</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Open Search -->
    <link rel="search" type="application/opensearchdescription+xml" title="Wolverine Search" href="<?php echo $_SITE['URL']; ?>/pages/openSearch.xml.php">
    
    <style>
      #back-button-container {
        margin-top: 14px;
      }
      
      .featured {
        font-weight: bold;
      }
      
      .desktop-instructions, .mobile-instructions {
        display: none;
      }
      
      #mac-chars {
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
      }
      
      table {
        border: none;
        margin-top: 6px;
        margin-bottom: 12px;
      }
      
      table, tr {
        width: 100%;
      }
      
      .align-right {
        text-align: right;
        padding-right: 4px;
      }
      
      /* For the snackbar */
      #snackbar {
        visibility: hidden; /* Hidden by default. Visible on click */
        min-width: 250px; /* Set a default minimum width */
        margin-left: -125px; /* Divide value of min-width by 2 */
        background-color: #333; /* Black background color */
        color: #fff; /* White text color */
        text-align: center; /* Centered text */
        border-radius: 2px; /* Rounded borders */
        padding: 16px; /* Padding */
        position: fixed; /* Sit on top of the screen */
        z-index: 1; /* Add a z-index if needed */
        left: 50%; /* Center the snackbar */
        bottom: 30px; /* 30px from the bottom */
      }
      
      /* Show the snackbar when clicking on a button (class added with JavaScript) */
      #snackbar.show {
        visibility: visible; /* Show the snackbar */
        
        /* Add animation: Take 0.5 seconds to fade in and out the snackbar. 
         * However, delay the fade out process for 2.5 seconds
         */
        -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
        animation: fadein 0.5s, fadeout 0.5s 2.5s;
      }
      
      /* Animations to fade the snackbar in and out */
      @-webkit-keyframes fadein {
        from {bottom: 0; opacity: 0;} 
        to {bottom: 30px; opacity: 1;}
      }
      
      @keyframes fadein {
        from {bottom: 0; opacity: 0;}
        to {bottom: 30px; opacity: 1;}
      }
      
      @-webkit-keyframes fadeout {
        from {bottom: 30px; opacity: 1;}
        to {bottom: 0; opacity: 0;}
      }
      
      @keyframes fadeout {
        from {bottom: 30px; opacity: 1;}
        to {bottom: 0; opacity: 0;}
      }
      
      .chrome-find-search-failed {
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
      }

      /*.footer-container {
        width: 100%;
        position: absolute;
        bottom: 0;
      }*/
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
    <div id="back-button-container" class="container-fluid">
      <div class="row">
        <div class="col-xs-12">
          <button id="back-button" type="button" class="btn btn-default">
            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Search
          </button>
        </div>
      </div>
    </div>
    
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <h1>Wolverine Search</h1>
          <p class="lead">
            Jump to <a href="#setup">Setup</a>
          </p>
          
          <p>
            Wolverine search is like a command-line for your browser. You can quickly visit websites by typing keywords or executing smart queries.<br>
          </p>
          <p>
            For example, try some of these commands:
            <a href="#" class="example-command featured">cg eecs280</a>,
            <a href="#" class="example-command featured">mc seshrs</a>,
            <a href="#" class="example-command">piazza eecs485</a>,
            <a href="#" class="example-command">gradescope</a>,
            <a href="#" class="example-command featured">student</a>,
            <a href="#" class="example-command featured">g recursion</a>,
            <a href="#" class="example-command">canvas polsci 101</a>,
            <a href="#" class="example-command featured">cg german hu</a>,
            <a href="#" class="example-command">mfile</a> or
            <a href="#" class="example-command featured">bing snake game</a>.
            For the full documentation of commands, see <a href="<?php echo $_SITE['URL']; ?>/list">List of Commands</a>.
          </p>
          <div class="row">
            <form action="<?php echo $_SITE['URL']; ?>/search" method="GET">
              <div class="col-xs-8">
                <input class="form-control" type="search" id="ws-search-bar" name="q" placeholder="Type a command, or click one of the examples above." />
              </div>
              <div class="col-xs-4">
                <input class="btn btn-primary" type="submit" value="Go!" />
              </div>
            </form>
          </div>
          <p>
            <br>
            Wolverine Search was inspired by <a href="http://www.bunny1.org/">bunny1</a>, developed for use by Facebook employees.
          </p>
          
        </div>
      </div>
      
      <div id="setup" class="row">
        <div class="col-xs-12">
          <h2>Setup</h2>
          <p>
            Wolverine Search tries to make it quicker to visit websites. To make it even more efficient, follow the instructions to setup Wolverine Search in your browser. <a href="#" id="show-mobile-instructions-link">(Show Mobile Instructions instead.)</a><a href="#" id="show-desktop-instructions-link">(Show Desktop Instructions instead.)</a>
          </p>
        </div>
      </div>
      <div class="alert alert-info">
        For advanced configurations, <a href="#advanced-configuration" class="alert-link">scroll down first</a>.
      </div>
      <div class="desktop-instructions">
        <ul id="browsers" class="nav nav-tabs" role="tablist">
          <li role="presentation"><a href="#chrome" aria-controls="home" role="tab" data-toggle="tab">Google Chrome</a></li>
          <li role="presentation"><a href="#firefox" aria-controls="profile" role="tab" data-toggle="tab">Mozilla Firefox</a></li>
          <li role="presentation"><a href="#safari" aria-controls="profile" role="tab" data-toggle="tab">Safari</a></li>
          <li role="presentation"><a href="#edge" aria-controls="profile" role="tab" data-toggle="tab">Microsoft Edge</a></li>
          <li role="presentation"><a href="#ie" aria-controls="profile" role="tab" data-toggle="tab">Internet Explorer</a></li>
          <li role="presentation"><a href="#opera" aria-controls="profile" role="tab" data-toggle="tab">Opera</a></li>
        </ul>
        
        <div class="tab-content">
          <!-- Chrome -->
          <div role="tabpanel" class="tab-pane panel panel-default" id="chrome">
            <div class="panel-body">
              <ol>
                <li>
                  Click the Google Chrome Menu button on the right. Click <code>Settings</code>. Scroll down to the <code>Search engine</code> section. Click <code>Manage search engines</code>.<br>
                  <em>(Alternatively, paste this into the search bar: <code>chrome://settings/searchEngines</code>.)</em><br><br> 
                </li>
                <li class="chrome-find-search">
                  Find <code>Wolverine Search</code> in the <code>Other search engines</code> section.<br>
                  <a href="#" id="chrome-manual-add">Can&apos;t find it?</a><br><br> 
                </li>
                <li class="chrome-find-search">
                  Click the menu icon next to Wolverine Search, and click <code>Edit</code>. Change the <code>Keyword</code> to <kbd>w</kbd>. Click <code>Save</code>.<br>
                  <em>(You can change it to some other keyword if you like.)</em><br><br> 
                </li>
                <li class="chrome-find-search-failed hidden">Scroll to the bottom of the <code>Other search engines</code> section. Click the <code>Add</code> button.</li>
                <li class="chrome-find-search-failed hidden">Fill in the fields. <span id="click-to-copy"><em>(Click each box to copy the text.)</em></span>
                  <table>
                    <tr>
                      <td class="align-right" width="15%"><strong>Search Engine: </strong></td>
                      <td><input type="text" value="Wolverine Search" class="clipboard-inputs form-control" readonly /></td>
                    </tr>
                    <tr>
                      <td class="align-right" width="15%"><strong>Keyword: </strong></td>
                      <td><input type="text" value="w" class="clipboard-inputs form-control" data-toggle="tooltip" data-placement="right" title="You can change this if you like." readonly /></td>
                    </tr>
                    <tr>
                      <td class="align-right" width="15%"><strong>URL: </strong></td>
                      <td><input type="text" value="Loading..." class="clipboard-inputs form-control" id="chrome-url" readonly /></td>
                    </tr>
                  </table>
                </li>
                <li class="chrome-find-search-failed hidden">Click <code>Add</code>.<br><br> </li>
                <li>Now, you can use Wolverine Search from your search bar by prefixing each command with <kbd>w</kbd>. For example, try typing <kbd>w list</kbd> in the search bar.<br></li>
              </ol>
              <p>
                <strong><em>Optional:</em></strong><br>
                Find <code>Wolverine Search</code> from the list of search engines, click the menu button next to it and click the <code>Make Default</code> option to make Wolverine Search your default search engine.<br>
                Wolverine Search automatically falls back to Google, so you can continue to use the search bar as you used to.
              </p>
            </div>
          </div>
          
          <!-- Firefox -->
          <div role="tabpanel" class="tab-pane panel panel-default" id="firefox">
            <div class="panel-body">
              <ol>
                <li>
                  Click the button to install Wolverine Search in your browser. <a id="firefox-add" class="btn btn-default btn-sm" href="#">Install Wolverine Search</a><br> 
                  <em>(Alternatively, click the search icon with the plus symbol in the search bar. Then click <code>Add "Wolverine Search"</code>.)</em><br><br> 
                </li>
                <li>
                  Click the menu icon to the right of the sidebar and choose <code>Preferences</code>. On the left, click <code>Search</code>.<br> 
                  <em>(Alternatively, enter <code>about:preferences#search</code> in a new tab.)</em><br><br> 
                </li>
                <li>
                  Select <code>Wolverine Search</code> in the table. Double-click the row under the <code>Keyword</code> column. Enter the keyword <kbd>w</kbd>.<br> 
                  (<em>You can change this to some other keyword, if you like.)</em><br><br> 
                </li>
                <li>
                  Now, you can use Wolverine Search from your URL bar by prefixing each command with <kbd>w</kbd>. For example, try typing <kbd>w list</kbd> in the URL bar.<br> 
                </li>
              </ol>
            </div>
          </div>
          
          <!-- Safari -->
          <div role="tabpanel" class="tab-pane panel panel-default" id="safari">
            <div class="panel-body">
              Drag this bookmarklet <a id="safari-bookmarklet" class="btn btn-default btn-sm" href="#">Wolverine Search</a> to your bookmarks bar.<br>
              Now, click the bookmarklet, and in the box that pops up, type <code>list</code> or <code>wa student</code> and hit enter.<br><br>
              
              <strong><em>Optional:</em></strong>
              <ul>
                <li>
                  You could make the bookmarklet the leftmost bookmark in your bookmarks bar, and then use the keyboard shortcut <kbd><span id="mac-chars">&#8997;&#8984;</span>1</kbd> to get to it.
                </li>
                <li>
                  Safari supports the search bar behavior of Chrome and Firefox to some extent. You need to begin typing the entire URL of this site (<code><?php echo $_SITE['URL']; ?></code>), followed by your query.
                </li>
              </ul>
            </div>
          </div>
          
          <!-- Edge -->
          <div role="tabpanel" class="tab-pane panel panel-default" id="edge">
            <div class="panel-body">
              edge
            </div>
          </div>
          
          <!-- Internet Explorer -->
          <div role="tabpanel" class="tab-pane panel panel-default" id="ie">
            <div class="panel-body">
              ie
            </div>
          </div>
          
          <!-- Opera -->
          <div role="tabpanel" class="tab-pane panel panel-default" id="opera">
            <div class="panel-body">
              <ol>
                <li>
                  Navigate to Opera&apos;s settings page. Click <code>Browser</code> in the sidebar on the left. Click <code>Manage search engines</code>.<br>
                  <em>(Alternatively, paste this into the search bar: <code>opera://settings/searchEngines</code>.)</em><br><br> 
                </li>
                <li>Scroll to the bottom of the <code>Other search engines</code> section. Click the <code>Add New Search</code> button.</li>
                <li>Fill in the fields. <span id="click-to-copy"><em>(Click each box to copy the text.)</em></span>
                  <table>
                    <tr>
                      <td class="align-right" width="15%"><strong>Name: </strong></td>
                      <td><input type="text" value="Wolverine Search" class="clipboard-inputs form-control" readonly /></td>
                    </tr>
                    <tr>
                      <td class="align-right" width="15%"><strong>Keyword: </strong></td>
                      <td><input type="text" value="ws" class="clipboard-inputs form-control" data-toggle="tooltip" data-placement="right" title="You can change this if you like." readonly /></td>
                    </tr>
                    <tr>
                      <td class="align-right" width="15%"><strong>Address: </strong></td>
                      <td><input type="text" id="opera-url" value="Loading..." class="clipboard-inputs form-control" readonly /></td>
                    </tr>
                  </table>
                </li>
                <li>Click <code>Save</code>, then click <code>Done</code>.<br><br> </li>
                <li>Now, you can use Wolverine Search from your search bar by prefixing each command with <kbd>ws</kbd>. For example, try typing <kbd>ws list</kbd> in the search bar.<br></li>
              </ol>
              <p>
                <strong><em>Optional:</em></strong><br>
                TODO: Make default. And is POST supported?
              </p>
            </div>
          </div>
          
          <h3 id="advanced-configuration">Advanced Configuration</h3>
          
          <div class="panel panel-default">
            <div class="panel-body">
              <h4>Fallback Command</h4>
              <p>By default, Wolverine Search falls back to performing a Google Search if the query does not begin with a valid command. However, you can change this behavior. For instance, making the fallback command <kbd>bing</kbd> will make Wolverine Search perform a Bing Search if the query does not begin with a valid command.</p>
              <p>Choose the fallback command you wish to use here. Then scroll up and follow the instructions in the <code>Setup</code> section.</p>
              <select id="fallback-select" class="form-control">
                <option value="g" selected>Google (g)</option>
                <option value="bing">Bing (bing)</option>
                <option value="yubnub">Yubnub (yn)</option>
              </select>
              <br>
              <p><em><kbd>bing</kbd> supports search suggestions in the URL bar!</em></p>
              <!-- <input class="form-control" type="text" id="fallback-box" placeholder="g (Google)" /> -->
            </div>
          </div>
          
          <div class="panel panel-default">
            <div class="panel-body">
              <h4>Default Command</h4>
              <p class="alert alert-warning">Not to be confused with Fallback Commands above.</p>
              <p>Some commands are capable of being used as a default command. For instance, if you choose to use <kbd>eecs280</kbd> as your default command, then you can simply use the command <kbd>calendar</kbd> instead of specifying the entire command <kbd>eecs280 calendar</kbd>. If the default command cannot parse your query, it will fall back to Wolverine Search, which in turn falls back to the specified fallback command. <em>(Read above for more information on <code>Fallback Commands</code>.)</em></p>
              <p>Choose from the available default commands. Then scroll up and follow the instructions in the <code>Setup</code> section.</p>
              <select id="default-command-select" class="form-control">
                <option value="" selected>None</option>
                <option value="eecs280%20">eecs280</option>
              </select>
            </div>
          </div>
          
        </div>
      </div>
      
      <div class="mobile-instructions">
        Mobile instructions
      </div>
      
      <div id="snackbar"></div>
    </div>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/is_js/0.9.0/is.min.js"></script>
    
    <script>
      // Global variable used to generate URLs
      var fallback = "g";
      var defaultCommand = "";
      
      (function () {
        $('#back-button').on('click', function () {
          window.location.href = '<?php echo $_SITE['URL']; ?>';
        });
        
        $('[data-toggle="tooltip"]').tooltip();
        
        $('.example-command').on('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          var commandText = $(e.target).text();
          $('#ws-search-bar').focus().prop('value', commandText);
        });
      })();
    </script>
    
    <script>
      function showMobileInstructions(show = true) {
        var desktopDisplay, mobileDisplay;
        if (show) {
          desktopDisplay = 'none';
          mobileDisplay = 'block';
          $('#show-mobile-instructions-link').css('display', 'none');
          $('#show-desktop-instructions-link').css('display', 'inline');
        }
        else {
          desktopDisplay = 'block';
          mobileDisplay = 'none';
          $('#show-mobile-instructions-link').css('display', 'inline');
          $('#show-desktop-instructions-link').css('display', 'none');
        }
        $('.desktop-instructions').css('display', desktopDisplay);
        $('.mobile-instructions').css('display', mobileDisplay);
      }
      
      (function () {
        if (is.desktop()) {
          showMobileInstructions(false);
          var tab = 'chrome';
          if (is.firefox()) {
            tab = 'firefox';
          }
          else if (is.safari()) {
            tab = 'safari';
          }
          else if (is.edge()) {
            tab = 'edge';
          }
          else if (is.ie()) {
            tab = 'ie';
          }
          else if (is.opera()) {
            tab = 'opera';
          }
          $('#browsers a[href="#' + tab + '"]').tab('show');
        }
        else {
          showMobileInstructions(true);
        }
        
        $('#show-mobile-instructions-link').on('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          showMobileInstructions(true);
        });
        
        $('#show-desktop-instructions-link').on('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          showMobileInstructions(false);
        });
      })();
    </script>
    
    <script>
      function showToast(text) {
        // Get the snackbar DIV
        var $snackbar = $("#snackbar");
        $snackbar.html(text);
        
        // Add the "show" class to DIV
        $snackbar.addClass("show");
        
        // After 3 seconds, remove the show class from DIV
        setTimeout(function() {
          $snackbar.removeClass('show');
        }, 3000);
      }
    </script>
    
    <script>
      var copyWorks = true;
      
      function copyToClipboard(elem) {
        // select the content
        elem.focus();
        elem.setSelectionRange(0, elem.value.length);
        
        // copy the selection
        if (copyWorks) {
          var succeed;
          try {
            succeed = document.execCommand("copy");
          } catch(e) {
            succeed = false;
            elem.setSelectionRange(0, 0);
            copyWorks = false;
            $('#click-to-copy').css('display', 'none');
          }
        }
    
        return succeed;
      }
      
      (function () {
        $('.clipboard-inputs').on('click', function (e) {
          var inputElement = e.target;
          var success = copyToClipboard(inputElement);
          if (success) {
            showToast('Copied to clipboard');
          }
        });
      })();
    </script>
    
    <script>
      // CHROME-related functions
      (function () {
        $('#chrome-manual-add').on('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          chrome_manualAdd();
        });
        
        chrome_updateURL(false);
      })();
      
      function chrome_manualAdd() {
        $('.chrome-find-search').addClass('hidden');
        $('.chrome-find-search-failed').removeClass('hidden').addClass('bg-success');
        setTimeout(function () {
          $('.chrome-find-search-failed').removeClass('bg-success');
        }, 1000);
      }
      
      function chrome_updateURL(shouldShowManualAdd) {
        shouldShowManualAdd = (shouldShowManualAdd == null) ? true : false;
        if (shouldShowManualAdd) {
          chrome_manualAdd();
        }
        
        var fallbackStr = 'fallback=' + fallback + '&';
        if (fallback === 'g') {
          fallbackStr = '';
        }
        var chromeURL = "<?php echo $_SITE['URL']; ?>/search?" + fallbackStr + "q=" + defaultCommand + "%s";
        $('#chrome-url').prop('value', chromeURL);
      }
    </script>
    
    <script>
      // FIREFOX-related functions
      (function () {
        $('#firefox-add').on('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          firefox_install();
        });
      })();
      
      function firefox_install() {
        var xmlURL = "<?php echo $_SITE['URL']; ?>/pages/openSearch.xml.php?fallback=" + fallback + "&defaultCommand=" + defaultCommand;
        window.external.AddSearchProvider(xmlURL);
      }
    </script>
    
    <script>
      // SAFARI-related functions
      (function () {
        safari_updateBookmarklet();
        $('#safari-bookmarklet').on('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
        });
      })();
      
      function safari_updateBookmarklet() {
        var defaultText = defaultCommand;
        var bookmarkletCode = "javascript:ws_url='<?php echo $_SITE['URL']; ?>/search?fallback=" + fallback + "&q=" + defaultCommand + "';cmd=prompt('Wolverine Search: Type \"help\" to get help or \"list\" to see commands you can use.','" + defaultText + "');if(cmd){window.location=ws_url+escape(cmd);}else{void(0);}";
        $('#safari-bookmarklet').prop('href', bookmarkletCode);
      }
    </script>
    
    <script>
      // OPERA-related functions
      (function () {
        opera_updateURL();
      })();
      
      function opera_updateURL() {
        var fallbackStr = 'fallback=' + fallback + '&';
        if (fallback === 'g') {
          fallbackStr = '';
        }
        var operaURL = "<?php echo $_SITE['URL']; ?>/search?" + fallbackStr + "q=" + defaultCommand + "%s";
        $('#opera-url').prop('value', operaURL);
      }
    </script>
    
    <script>
      // ADVANCED CONFIG
      (function () {
        $('#fallback-select').on('change', function () {
          var newFallbackCommand = $('#fallback-select').prop('value');
          if (!newFallbackCommand || !newFallbackCommand.length) {
            fallback = "g";
          }
          else if (newFallbackCommand !== fallback) {
            fallback = newFallbackCommand;
          }
          
          chrome_updateURL();
          safari_updateBookmarklet();
          opera_updateURL();
        });
        
        $('#default-command-select').on('change', function () {
          defaultCommand = $('option:selected').val();
          chrome_updateURL();
          safari_updateBookmarklet();
          opera_updateURL();
        });
      })();
    </script>
  </body>
</html>
