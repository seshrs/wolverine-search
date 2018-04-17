<?php

http_response_code(404);

?>
<!doctype html>
<html>
<head>
  <title>Error | Wolverine Search</title>
</head>
<body>
  <h1>Error – Invalid URL</h1>
  <p>
    Debugging info possibly useful?<br>
    <strong>REQUEST_URI:</strong> <?php echo $_SERVER['REQUEST_URI']; ?><br>
  </p>
</body>
</html>
