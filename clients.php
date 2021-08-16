<?php 
    require_once("linkai.php"); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
</head>
<body>
    
</body>
</html>

<?php

if (!isset($_COOKIE["prisijungta"])) { 
    header ("Location:index.php"); 
} else {
    echo "Sveikas prisijunges prie savo paskyros"; 
    echo "<form action='clients.php' method='get'>"; 
    echo "<button class='btn btn-primary' type='submit' name='logout'>"; 
    echo "</form>"; 

    if (isset($_GET["logout"])) {
        setcookie("logout", "", time() -3600, "/" ); 
        header("Location:index.php"); 
    }
}
?>