<?php

// GET paramenter:
//   q ==> Filter query (optional)

require('../sitevars.php');
require_once('../scripts/analytics.php');

// Analytics
Analytics::runAnalytics(Analytics::$USER_ACTION['LIST']);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Other meta tags should go below this line -->

    <title>List of Commands | <?php echo $_SITE['name']; ?></title>

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
      #back-button-container {
        margin-top: 14px;
      }
      
      #clear-button, #command-filter {
        margin-bottom: 9px;
      }
      
      .hidden {
        display: none;
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
          <h1>List of Commands</h1>
        </div>
      </div>
      <div id="filter-container" class="row hidden">
        <div class="col-xs-12">
          <p class="lead">
            Search through the commands:
          </p>
        </div>
        <div class="col-xs-12 col-sm-10">
          <input class="form-control" type="text" id="command-filter" placeholder="Type to search..." value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>" />
        </div>
        <button id="clear-button" class="col-xs-2 col-xs-offset-5 col-sm-2 col-sm-offset-0 btn btn-default" disabled>Clear</button>
      </div>
      
      <div class="row">
        <p class="col-xs-12 alert alert-info">
          All queries are case-insensitive.
        </p>
      </div>
      
      
      <div id="documentation">
        <div class="progress">
          <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
            <span class="sr-only">Documentation Loading</span>
          </div>
        </div>
      </div>
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
    
    <script type="text/javascript" src="<?php echo $_SITE['URL']; ?>/static/scripts/markdown/Markdown.Converter.js"></script>
    <script type="text/javascript" src="<?php echo $_SITE['URL']; ?>/static/scripts/markdown/Markdown.Sanitizer.js"></script>
    
    <script>
      var markdownConverter;
      var documentationSectionsMarkdown;
      var panelDisplayMap;
      var currentFilterText;
      
      (function () {
        $('#back-button').on('click', function () {
          window.location.href = '<?php echo $_SITE['URL']; ?>';
        });
        
        markdownConverter = Markdown.getSanitizingConverter();
        markdownConverter.hooks.chain("preBlockGamut", function (text, rbg) {
          return text.replace(/^ {0,3}""" *\n((?:.*?\n)+?) {0,3}""" *$/gm, function (whole, inner) {
            return "<blockquote>" + rbg(inner) + "</blockquote>\n";
          });
        });
        
        getDocumentation();
        
        $('#command-filter').focus().on('keyup', filterTextChanged);
        $('#clear-button').on('click', clearFilterText);
      })();
      
      function getDocumentation() {
        $.get('<?php echo $_SITE['URL']; ?>/api/v1/documentation')
          .done(generateDocumentation)
          .fail(fetchDocumentationFailed);
      }
      
      function fetchDocumentationFailed() {
        $failureElement = "<p>Whoops, something whent wrong. <a href='mailto:seshrs@umich.edu?subject=[<?php echo $_SITE['name']; ?>] Fetch Documentation Failed!'>Let me know that this happened.</a></p>";
        $('#documentation').html($failureElement);
      }
      
      function generateDocumentation(documentation_json) {
        resetDocumentation();
        
        var documentationSectionsMarkdownObject = JSON.parse(documentation_json);
        documentationSectionsMarkdown = [];
        for (var key in documentationSectionsMarkdownObject) {
          documentationSectionsMarkdown.push( documentationSectionsMarkdownObject[key] );
        }
        var documentationPanels = [];
        // First start with core documentation
        documentationPanels.push( generateDocumentationPanel(documentation.core) );
        documentation.core = [];
        var i = 0;
        for (documentationSectionIndex in documentationSectionsMarkdown) {
          var documentationSection = documentationSectionsMarkdown[documentationSectionIndex];
          if (!documentationSection.length) {
            ++i;
            continue;
          }
          var documentationPanelsInSection = [];
          for (documentationMarkdownIndex in documentationSection) {
            var panelID = getPanelID(i, documentationMarkdownIndex);
            var documentationMarkdown = documentationSection[documentationMarkdownIndex];
            documentationPanelsInSection.push( generateDocumentationPanel(documentationMarkdown, panelID) );
          }
          documentationPanels.push(documentationPanelsInSection);
          ++i;
        }
        
        displayDocumentation(documentationPanels);
        checkIfRequestWasInURL();
      }
      
      function resetDocumentation() {
        $('#documentation').html('');
      }
      
      function getPanelID(sectionIndex, documentationIndex) {
        return 'panel_' + sectionIndex + '_' + documentationIndex;
      }
      
      function getSectionID(sectionIndex) {
        return 'section_' + sectionIndex;
      }
      
      function generateDocumentationPanel(documentationMarkdown, panelID) {
        if (!documentationMarkdown || documentationMarkdown.length === 0) {
          return null;
        }
        
        var $html = $(markdownConverter.makeHtml(documentationMarkdown));
        var title = $html[0].innerText;
        $html.splice(0, 1);
        
        $panelContainer = $('<div/>').addClass('panel panel-default').attr('id', panelID);
        
        $panelHeader = $('<div/>').addClass('panel-heading');
        $panelTitle = $('<h3/>').addClass('panel-title').append(title);
        $panelHeader.append($panelTitle);
        
        $panelBody = $('<div/>').addClass('panel-body').append($html);
        
        return $panelContainer.append($panelHeader, $panelBody);
      }
      
      function displayDocumentation(documentationPanels) {
        $parentDocumentationDiv = $('#documentation');
        $listGroup = $('<ul/>').addClass('list-group');
        var i = 0;
        for (documentationSectionIndex in documentationPanels) {
          var documentationSection = documentationPanels[documentationSectionIndex];
          if (!documentationSection || documentationSection.length === 0) {
            continue;
          }
          var sectionID = getSectionID(i);
          $listItem = $('<li/>').addClass('list-group-item').attr('id', sectionID);
          for (panelIndex in documentationSection) {
            var panel = documentationSection[panelIndex];
            $listItem.append(panel);
          }
          $listGroup.append($listItem);
          ++i;
        }
        $parentDocumentationDiv.append($listGroup);
        $('#filter-container').removeClass('hidden');
      }
      
      function checkIfRequestWasInURL() {
        filterTextChanged();
      }
      
      function filterTextChanged() {
        var filterText = $('#command-filter').prop('value');
        if (filterText === currentFilterText) {
          return;
        }
        currentFilterText = filterText;
        
        if (filterText && filterText.length) {
          $('#clear-button').prop('disabled', false);
        }
        else {
          $('#clear-button').prop('disabled', true);
        }
        
        applyFilter(filterText);
      }
      
      function applyFilter(filterText) {
        var newPanelDisplayMap = getPanelDisplayMap(filterText);
        if (!panelDisplayMap) {
          panelDisplayMap = newPanelDisplayMap;
          applyInitialPanelDisplayMap();
        }
        else {
          applyNewPanelDisplayMap(newPanelDisplayMap);
        }
      }
      
      function applyInitialPanelDisplayMap() {
        for (var sectionIndex = 0; sectionIndex < panelDisplayMap.length; ++sectionIndex) {
          var allHidden = true;
          for (var documentationIndex = 0; documentationIndex < panelDisplayMap[sectionIndex].length; ++documentationIndex) {
            var panelID = getPanelID(sectionIndex, documentationIndex);
            if (!panelDisplayMap[sectionIndex][documentationIndex]) {
              $('#' + panelID).addClass('hidden');
            }
            else {
              allHidden = false;
            }
          }
          if (allHidden) {
            var sectionID = getSectionID(sectionIndex);
            $('#' + sectionID).addClass('hidden');
          }
        }
      }
      
      function applyNewPanelDisplayMap(newPanelDisplayMap) {
        for (var sectionIndex = 0; sectionIndex < panelDisplayMap.length; ++sectionIndex) {
          var sectionID = getSectionID(sectionIndex);
          var isSectionAlreadyHidden = $('#' + sectionID).hasClass('hidden');
          var allHidden = true;
          
          for (var documentationIndex = 0; documentationIndex < panelDisplayMap[sectionIndex].length; ++documentationIndex) {
            var panelID = getPanelID(sectionIndex, documentationIndex);
            var panelShouldBeHidden = !newPanelDisplayMap[sectionIndex][documentationIndex]
            var isPanelAlreadyHidden = !panelDisplayMap[sectionIndex][documentationIndex];
            if (panelShouldBeHidden && !isPanelAlreadyHidden) {
              $('#' + panelID).addClass('hidden');
            }
            else if (!panelShouldBeHidden && isPanelAlreadyHidden) {
              $('#' + panelID).removeClass('hidden');
            }
            if (!panelShouldBeHidden) {
              allHidden = false;
            }
          }
          
          if (allHidden && !isSectionAlreadyHidden) {
            $('#' + sectionID).addClass('hidden');
          }
          else if (!allHidden && isSectionAlreadyHidden) {
            $('#' + sectionID).removeClass('hidden');
          }
        }
        panelDisplayMap = newPanelDisplayMap;
      }
      
      function getPanelDisplayMap(filterText) {
        var newPanelDisplayMap = [];
        var pattern = new RegExp(escapeRegExp(filterText), 'i');
        for (var sectionIndex = 0; sectionIndex < documentationSectionsMarkdown.length; ++sectionIndex) {
          var displayMapRow = [];
          for (var documentationIndex = 0; documentationIndex < documentationSectionsMarkdown[sectionIndex].length; ++documentationIndex) {
            var markdown = documentationSectionsMarkdown[sectionIndex][documentationIndex];
            displayMapRow.push(markdown.search(pattern) !== -1);
          }
          newPanelDisplayMap.push(displayMapRow);
        }
        return newPanelDisplayMap;
      }
      
      // From: https://stackoverflow.com/questions/3446170/escape-string-for-use-in-javascript-regex
      function escapeRegExp(str) {
        return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
      }
      
      function clearFilterText() {
        $('#command-filter').prop('value', '');
        currentFilterText = '';
        applyFilter('');
        $('#clear-button').prop('disabled', true);
      }
    </script>
    
  </body>
</html>