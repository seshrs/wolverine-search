<?php

// GET Params
// $fallback (string, optional)
//   Fallback command to use. Defaults to 'g'.
// $defaultCommand (string, optional)
//   Default command to use. Defaults to ''.
//   See About page for supported default commands.
//

require('../sitevars.php');

$fallback = isset($_REQUEST['fallback']) ? $_REQUEST['fallback'] : null;
if (!$fallback || !strlen($fallback)) {
  $fallback = 'g';
}

$defaultCommandProvided = false;
$defaultCommand = isset($_REQUEST['defaultCommand']) ? $_REQUEST['defaultCommand'] : null;
if (!$defaultCommand || !strlen($defaultCommand)) {
  $defaultCommand = '';
}
else {
  $defaultCommandProvided = true;
}

header('Content-Type: text/xml');

?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/" xmlns:moz="http://www.mozilla.org/2006/browser/search/">
<ShortName><?php echo $_SITE['name']; ?></ShortName>
<Description>
A mashup of services useful to students at the University of Michigan.
</Description>
<InputEncoding>UTF-8</InputEncoding>
<Image width="256" height="256" type="image/x-icon">"<?php echo $_SITE['URL']; ?>/Search-button.png"</Image>
<Url type="text/html" method="get" template="<?php echo $_SITE['URL']; ?>/search?fallback=<?php echo $fallback; ?>&amp;q=<?php echo $defaultCommand; ?>{searchTerms}"/>
<Url type="application/opensearchdescription+xml" rel="self" template="<?php echo $_SITE['URL']; ?>/pages/openSearch.xml.php" />
<?php
// if ($fallback == 'g') {
//
// <Url type="application/x-suggestions+json" template="http://suggestqueries.google.com/complete/search?output=toolbar&amp;hl=en&amp;q={searchTerms}"/>
// <?php
// }
if ($fallback == 'bing') {
?>
<Url type="application/x-suggestions+json" template="http://api.bing.com/osjson.aspx?query={searchTerms}"/>
<?php
}
?>
<moz:SearchForm><?php echo $_SITE['URL']; ?>?fallback=<?php echo $fallback; ?><?php if ($defaultCommandProvided) echo "&amp;defaultCommand=" . $defaultCommand . "%20"; ?></moz:SearchForm>
</OpenSearchDescription>
